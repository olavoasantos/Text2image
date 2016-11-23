<?php

namespace Core;

class Pixels {

	/**
	 * 	Creates a PNG image with pixel code passed to the method.
	 *	
	 *	@var 	Array 	$pixelCodes Pixel codes
	 *	@var 	String 	$path 		File path 	(optional)
	 *	@return String 	File name
	 */
	public static function make($pixelCodes, $path = '') {
		$w = sizeof($pixelCodes);
		$r = $w%3;
		$f = floor($w/3);
		$s = ($r) ? $f + 1 : $f;


		$im = imagecreatetruecolor($s, 1);
		for ($i = 0; $i < $f; $i++)
		{
			$color = imagecolorallocate($im, $pixelCodes[$i*3], $pixelCodes[$i*3 + 1], $pixelCodes[$i*3 + 2]);
			imagesetpixel($im, $i, 0, $color);
		}


		if($r) {
			switch ($r) {
				case 2:
					$color = imagecolorallocate($im, $pixelCodes[$w-2], $pixelCodes[$w-1], 255);
					break;
				
				default:
					$color = imagecolorallocate($im, $pixelCodes[$w-1], 255, 255);
					break;
			}
			imagesetpixel($im, $s-1, 0, $color);
		}

		$name = time() . "_.png";

		if($path !== '') {
			$path = (substr($path, -1,1) == '/') ? substr($path, 0, -1) : $path;
		}

		imagepng($im, $path . $name, 9);
		imagedestroy($im);

		return $name;
	}

	/**
	 * 	Reads the pixel codes from a PNG image.
	 *	
	 *	@var 	String 	$name 	File name
	 *	@var 	String 	$path 	File path 	(optional)
	 *	@return Array 	Pixel codes
	 */
	public static function read($name, $path = '') {
		if($path !== '') {
			$path = (substr($path, -1,1) == '/') ? substr($path, 0, -1) : $path;
		}
		$im = imagecreatefrompng($path . $name);
		$a = imagesx($im);

		$code = [];
		for ($i=0; $i < $a; $i++) { 
			$rgb = imagecolorat($im, $i, 0);
			$clr = imagecolorsforindex($im, $rgb);
			$code[] = str_pad($clr['red'], 3, '0', STR_PAD_LEFT);
			$code[] = str_pad($clr['green'], 3, '0', STR_PAD_LEFT);
			$code[] = str_pad($clr['blue'], 3, '0', STR_PAD_LEFT);
		}

		return $code;
	}

}