<?php


namespace Fw\Components\Services\Mailer;

use \Exception;

/**
 * Class Mailer
 * @package Fw\services\Mailer
 */
class Mailer
{

	private $LOGIN;
	private $PASSWORD;
	private $HOST;
	private $PORT;

	private $files = [];
	private $contentType = 'text/plain';
	private $smtp_charset = "utf-8";
	private $boundary;


	/**
	 * Mailer constructor.
	 * @param array $config
	 */
	function __construct(array $config)
	{
		$this->LOGIN    = $config['LOGIN'];
		$this->PASSWORD = $config['PASSWORD'];
		$this->HOST     = $config['HOST'];
		$this->PORT     = $config['PORT'];
	}


	/**
	 * @param $to
	 * @param $subject
	 * @param $message
	 * @param bool $from
	 * @param bool $files
	 * @param string $contentType
	 * @return bool|string
	 * @throws Exception
	 */
	public function send($to, $subject, $message, $from=false, $files=false, $contentType='text/plain')
	{
		$this->files = [];
		$this->contentType = $contentType;
		$this->boundary = "--".md5(uniqid(time()));

		$this->addFiles($files);
		$contentMail = $this->getContentMail($subject, $message, $from);
		$result = $this->sending($to,$contentMail);

		return $result;
	}


	/**
	 * @param $subject
	 * @param $message
	 * @param $from
	 * @return string
	 * @throws Exception
	 */
	private function getContentMail($subject, $message, $from)
	{
		$contentMail = "Date: " . date("D, d M Y H:i:s") . " UT\r\n";
		$contentMail .= 'Subject: =?' . $this->smtp_charset . '?B?'  . base64_encode($subject) . "=?=\r\n";

		// заголовок письма
		$headers = "MIME-Version: 1.0\r\n";

		// кодировка письма
		if(!empty($this->files))
			$headers .= "Content-Type: multipart/mixed; boundary=\"{$this->boundary}\"\r\n";
		else
			$headers .= "Content-type: text/plain; charset={$this->smtp_charset}\r\n";

		// от кого письмо
		if (!empty($from) )
			$headers .= "From: {$from} <{$this->LOGIN}>\r\n"; // от кого письмо
		else
			$headers .= "From: {$this->LOGIN}\r\n"; // от кого письмо

		// Добавляем заголовки в тело письма
		$contentMail .= $headers . "\r\n";

		// если есть файлы
		if(!empty($this->files))
		{
			$multipart  = "--{$this->boundary}\r\n";
			$multipart .= "Content-Type: {$this->contentType}; charset=utf-8\r\n";
			$multipart .= "Content-Transfer-Encoding: base64\r\n";
			$multipart .= "\r\n";
			$multipart .= chunk_split(base64_encode($message));

			// файлы
			foreach ($this->files as $file)
			{
				$multipart .= $this->getFileContent($file);
			}

			$multipart .= "\r\n--{$this->boundary}--\r\n";

			$contentMail .= $multipart;
		}
		else
		{
			$contentMail .= $message . "\r\n";
		}

		return $contentMail;
	}


	/**
	 * @param $files
	 */
	private function addFiles($files)
	{
		if ( !empty($files) )
		{
			if ( is_array($files) )
			{
				foreach ($files as $file)
				{
					if (file_exists($file))
					{
						$this->files[] = $file;
					}
				}
			}
			else
			{
				$file = $files;
				if (file_exists($file))
				{
					$this->files[] = $file;
				}
			}
		}
	}


	/**
	 * @param $path
	 * @return string
	 * @throws Exception
	 */
	private function getFileContent($path)
	{
		$file = @fopen($path, "rb");
		if(!$file) {
			throw new Exception("File `{$path}` didn't open");
		}
		$data = fread($file,  filesize( $path ) );
		fclose($file);
		$filename = basename($path);
		$multipart =  "\r\n--{$this->boundary}\r\n";
		$multipart .= "Content-Type: application/octet-stream; name=\"$filename\"\r\n";
		$multipart .= "Content-Transfer-Encoding: base64\r\n";
		$multipart .= "Content-Disposition: attachment; filename=\"$filename\"\r\n";
		$multipart .= "\r\n";
		$multipart .= chunk_split(base64_encode($data));

		return $multipart;
	}


	/**
	 * @param $to
	 * @param string $contentMail
	 * @return bool|string
	 */
	private function sending($to, string $contentMail)
	{
		try {
			if(!$socket = @fsockopen($this->HOST, $this->PORT, $errorNumber, $errorDescription, 30)){
				throw new Exception($errorNumber.".".$errorDescription);
			}
			if (!$this->_parseServer($socket, "220")){
				throw new Exception('Connection error');
			}

			$server_name = $_SERVER["SERVER_NAME"];

			fputs($socket, "EHLO $server_name\r\n");
			if(!$this->_parseServer($socket, "250")){
				// если сервер не ответил на EHLO, то отправляем HELO
				fputs($socket, "HELO $server_name\r\n");
				if (!$this->_parseServer($socket, "250")) {
					fclose($socket);
					throw new Exception('Error of command sending: HELO');
				}
			}
			fputs($socket, "AUTH LOGIN\r\n");
			if (!$this->_parseServer($socket, "334")) {
				fclose($socket);
				throw new Exception('Auth error');
			}
			fputs($socket, base64_encode($this->LOGIN) . "\r\n");
			if (!$this->_parseServer($socket, "334")) {
				fclose($socket);
				throw new Exception('Auth error');
			}
			fputs($socket, base64_encode($this->PASSWORD) . "\r\n");
			if (!$this->_parseServer($socket, "235")) {
				fclose($socket);
				throw new Exception('Auth error');
			}
			fputs($socket, "MAIL FROM: <".$this->LOGIN.">\r\n");
			if (!$this->_parseServer($socket, "250")) {
				fclose($socket);
				throw new Exception('Error of command sending: MAIL FROM');
			}


			$mailTo = str_replace(" ", "", $to);
			$emails_to_array = explode(',', $mailTo);
			foreach($emails_to_array as $email) {
				fputs($socket, "RCPT TO: <{$email}>\r\n");
				if (!$this->_parseServer($socket, "250")) {
					fclose($socket);
					throw new Exception('Error of command sending: RCPT TO');
				}
			}


			fputs($socket, "DATA\r\n");
			if (!$this->_parseServer($socket, "354")) {
				fclose($socket);
				throw new Exception('Error of command sending: DATA');
			}
			fputs($socket, $contentMail."\r\n.\r\n");
			if (!$this->_parseServer($socket, "250")) {
				fclose($socket);
				throw new Exception("E-mail didn't sent");
			}
			fputs($socket, "QUIT\r\n");
			fclose($socket);
		} catch (Exception $e) {
			return  $e->getMessage();
		}
		return true;
	}

	private function _parseServer($socket, $response)
	{
//		debug('test parser ',$socket, $response);
		$responseServer = null; //  fix fix fix fix fix

		while (@substr($responseServer, 3, 1) != ' ') {
			if (!($responseServer = fgets($socket, 256))) {
				return false;
			}
		}
		if (!(substr($responseServer, 0, 3) == $response)) {
			return false;
		}
		return true;
	}
}