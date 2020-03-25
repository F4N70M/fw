<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 25.03.2020
 */

namespace Fw\Components\Services\Uploader;


use Exception;
use Fw\Components\Services\Entity\Entity;

class Uploader
{
	protected $Entity;


	/**
	 * Uploader constructor.
	 * @param Entity $Entity
	 */
	public function __construct(Entity $Entity)
	{
		$this->Entity = $Entity;
	}


	/**
	 * @return array
	 * @throws Exception
	 */
	public function upload()
	{
		$files = $this->getFiles();
		$uploadedFiles = $this->moveFiles($files);
		$result = [];
		foreach ($uploadedFiles as $uploadedFile)
		{
			$uploadedFile['url'] = $uploadedFile['uri'];
			$result[] = $this -> Entity -> insert('files',$uploadedFile);
		}
		return $result;
	}


	/**
	 * @return array
	 */
	private function getFiles()
	{
		$files = [];
		foreach ($_FILES as $inputKey => $list)
		{
			if (is_array($list['name']))
			{
				$tmps = [];
				foreach ($list as $column => $values)
				{
					foreach ($values as $key => $value)
					{
						$tmps[$key][$column] = $value;
					}
				}
				foreach ($tmps as $tmp)
				{
					if (!$tmp['error'])
						$files[] = $tmp;
				}
			}
			else
			{
				if (!$list['error'])
					$files[] = $list;
			}
		}
		return $files;
	}


	/**
	 * @param array $files
	 * @return array
	 * @throws Exception
	 */
	private function moveFiles(array $files)
	{
		$uploadedFiles = [];
		foreach ($files as $file)
		{
			$info = $this->getUploadInfo($file);
			$this->createDir($info['uri']);
			$result = move_uploaded_file($file['tmp_name'],ROOT_DIR.$info['uri']);
			if (!$result)
			{
				debug($file, $file['tmp_name'], ROOT_DIR.$info['uri']);
				throw new Exception("File {$file['tmp_name']} didn't upload");
			}
			$uploadedFiles[] = $info;
		}
		return $uploadedFiles;
	}


	/**
	 * @param $file
	 * @return mixed
	 */
	private function getUploadInfo($file)
	{
		$explodeName = $this->explodeName($file['name']);
		$name = $explodeName[0];
		$extension = $explodeName[1];
		$mime = $this->getUploadMimeDir($file['type']);
		$explodeUri = $this->getExplodeUri($name,$extension,$mime);

		$result = [
			'title'  =>  $explodeUri['title'],
			'name'  =>  $explodeUri['name'],
			'extension' => $explodeUri['extension'],
			'type'  => $mime,
			'mime'  => $file['type'],
			'size'  => $file['size'],
			'uri'   => $explodeUri['full']
		];
		return $result;
	}

	private function explodeName($name)
	{
		$expName = explode('.',$name);
		$extension = array_pop($expName);
		$name = implode('.',$expName);

		return [$name,$extension];
	}


	/**
	 * @param string $name
	 * @param string $extension
	 * @param string $mime
	 * @return string
	 */
	private function getExplodeUri(string $name, string $extension, string $mime)
	{
		$i = 0;
		do
		{
			$result['path'] = UPLOADS_URI
				. '/' . $mime
				. '/' . date('Y-m-d');
			$result['title'] = $name;
			$result['name'] = date('YmdHis')
				. '__' . $this->translit(mb_substr($name,0,10))
				. ($i > 0 ? '__'.str_pad($i, 2, "0", STR_PAD_LEFT) : null);
			$result['extension'] = $extension;
			$result['full'] = $result['path'].'/'.$result['name'].'.'.$result['extension'];

			$i++;
		}
		while (file_exists(ROOT_DIR.$result['full']));

		return $result;
	}


	/**
	 * @param string $string
	 * @return string
	 */
	public function translit(string $string)
	{
		$translit = [
			'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'zj', 'з' => 'z',
			'и' => 'i', 'й' => 'i', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
			'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'z', 'ч' => 'ch', 'ш' => 'sh',
			'щ' => 'sch','э' => 'e', 'ю' => 'yu', 'я' => 'ya', ' ' => '-', '–' => '-', '—' => '-', '_' => '-'];
		foreach ($translit as $key => $value)
		{
			$pattern = '#['.$key.']#ui';
			$string = preg_replace($pattern,$value,$string);
		}

		$string = preg_replace('#([^A-Za-z0-9\-])#ui','',$string);
		$string = preg_replace('#(-+)#ui','-',$string);
		$string = trim($string,"-");
		return $string;
	}


	/**
	 * @param $mime
	 * @return string
	 */
	private function getUploadMimeDir($mime)
	{
		$result = explode('/',$mime)[0];
		return $result;
	}


	/**
	 * @param string $filename
	 */
	private function createDir(string $filename)
	{
		$dirs = explode('/',$filename);
		array_pop($dirs);
		$currentDir = ROOT_DIR.'/'.array_shift($dirs);
		foreach ($dirs as $dir)
		{
			$currentDir .= '/'.$dir;
			if (!file_exists($currentDir)) mkdir($currentDir);
		}
	}
}