<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 07.05.2020
 */

namespace Fw\Components\Services\Files;


use Fw\Components\Services\Entity\Entity;
use Fw\Components\Services\Files\File;

class Image extends File
{

	protected $webp;

	public function __construct(Entity $Entity, $id)
	{
		if (!empty($id))
			parent ::__construct($Entity, $id);
	}


	public function getResizeUri(int $max)
	{
		$name = $this->get('name');
		if (!$name) return null;
		$mime = $this->get('mime');
		$path = $this->get('path');
		$extension = $this->get('extension');
		$uri = $path.'/'.$name.'.'.$extension;
		$dir = ROOT_DIR . $uri;

		$uriNew = $this->getUriNew($path,$name.'__'.$max);
		$dirNew = ROOT_DIR . $uriNew;

		if (file_exists($dir) && !file_exists($dirNew))
		{
			$im = $this->imageCreate($dir,$mime);
			if ($im)
			{
				$size[0] = imagesx($im);
				$size[1] = imagesy($im);
				$sizeNew = $this->getSizeNew($size[0],$size[1],$max);

				$imNew = imagecreatetruecolor($sizeNew[0], $sizeNew[1]);
				imagecopyresampled($imNew,$im,0,0,0,0,$sizeNew[0],$sizeNew[1],$size[0],$size[1]);

				$this->imageSave($imNew,$dirNew);
				imagedestroy($im);
				imagedestroy($imNew);
			}
		}
		return $uriNew;
	}

	private function imageSave($im,$path)
	{
		if (!file_exists($path))
		{
			if ($this->checkWebP())
				imagewebp($im,$path,75);
			else
				imagejpeg($im,$path,85);
		}
	}

	private function getUriNew($path,$name)
	{
		if ($this->checkWebP())
			$uri = $path . '/' . $name . '.' . 'webp';
		else
			$uri = $path . '/' . $name . '.' . 'jpg';

		return $uri;
	}

	private function checkWebP()
	{
		if ($this->webp === null)
		{
			$this->webp = (strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false)
				&& (function_exists('imagecreatefromwebp')
				&& function_exists('imagewebp'));
		}
		return $this->webp;
	}

	private function getSizeNew($w,$h,$max)
	{
		$x = $w > $h ? $max : $max / $h * $w;
		$y = $w > $h ? $max / $w * $h : $max;

		return [$x,$y];
	}

	private function imageCreate($path,$mime)
	{
		switch ($mime)
		{
			case "image/jpeg":
				return imagecreatefromjpeg($path);
				break;
			case "image/png":
				return imagecreatefrompng($path);
				break;
			case "image/webp":
				return imagecreatefromwebp($path);
				break;
			default:
				return null;
				break;
		}
	}
}