<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 04.06.2020
 */

namespace Fw\Components\Modules\TemplateEngine;


class Palette
{
	protected $hex;
	protected $iHex;
	protected $rgb;
	protected $iRgb;

	const TARGET     = 0;
	const MAIN       = 1;
	const ACCENT     = 2;
	const ADDITIONAL = 3;

	public function __construct($target, $main, $accent, $additional, $white='#ffffff', $black="#000000")
	{
		$this->initHexColor(self::TARGET, $target);         // Target color
		$this->initHexColor(self::MAIN, $main);             // Main color
		$this->initHexColor(self::ACCENT, $accent);         // Accent color
		$this->initHexColor(self::ADDITIONAL, $additional); // Additional color
	}

	public function setHex(int $colorNumber, string $hex)
	{
		$this->initHexColor($colorNumber, $hex);
	}

	public function setRgb(int $colorNumber, array $rgb)
	{
		$this->initRgbColor($colorNumber, $rgb);
	}

	protected function initHexColor(int $colorNumber, string $hex)
	{
		$this->hex[$colorNumber] = $hex;
		$this->rgb[$colorNumber] = $this->hexToRgb($this->hex[$colorNumber]);
		$this->iRgb[$colorNumber] = $this->getInvertRgb($this->rgb[$colorNumber]);
		$this->iHex[$colorNumber] = $this->rgbToHex($this->iRgb[$colorNumber]);
	}

	protected function initRgbColor(int $colorNumber, array $rgb)
	{
		$this->rgb[$colorNumber] = $rgb;
		$this->hex[$colorNumber] = $this->hexToRgb($this->rgb[$colorNumber]);
		$this->iRgb[$colorNumber] = $this->getInvertRgb($this->rgb[$colorNumber]);
		$this->iHex[$colorNumber] = $this->rgbToHex($this->iRgb[$colorNumber]);
	}

	public function getHexColors()
	{
		return [
			[$this->hex[0], $this->iHex[0]],
			[$this->hex[1], $this->iHex[1]],
			[$this->hex[2], $this->iHex[2]],
			[$this->hex[3], $this->iHex[3]]
		];
	}

	public function getRgbColors()
	{
		return [
			[$this->rgb[0], $this->iRgb[0]],
			[$this->rgb[1], $this->iRgb[1]],
			[$this->rgb[2], $this->iRgb[2]],
			[$this->rgb[3], $this->iRgb[3]]
		];
	}

	/**
	 * @param string $hex
	 * @return string $hex
	 */
	private function getInvertHex(string $hex)
	{
		$rgb = $this->hexToRgb($hex);
		$iRgb = $this->getInvertRgb($rgb);
		$iHex = $this->rgbToHex($iRgb);
		return $iHex;
	}

	/**
	 * @param array $rgb
	 * @return array $rgb
	 */
	private function getInvertRgb(array $rgb)
	{
		return 1 - ($rgb[0]+$rgb[1]+$rgb[2]) / 3 / 255 > .5 ? [255,255,255] : [0,0,0];
	}

	/**
	 * @param array $rgb
	 * @return string $hex
	 */
	function rgbToHex(array $rgb)
	{
		return '#'
			.str_pad(dechex($rgb[0]),2,0)
			.str_pad(dechex($rgb[1]),2,0)
			.str_pad(dechex($rgb[2]),2,0);
	}

	/**
	 * @param string $hex
	 * @return array $rgb
	 */
	function hexToRgb(string $hex)
	{
		$rgb = [
			hexdec($hex[1].$hex[2]),
			hexdec($hex[3].$hex[4]),
			hexdec($hex[5].$hex[6]),
		];
		return $rgb;
	}
}