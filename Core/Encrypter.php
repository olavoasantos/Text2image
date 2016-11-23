<?php

namespace Core;

class Encrypter {

	/**
	 *	Number of layers to which the text will be encoded.
	 *	
	 *	@var Int $layers
	 */
	private $layers;

	/**
	 *	Number of bits to which the character will be encoded.
	 *	
	 *	@var Int $binaryLength
	 */
	private $binaryLength;

	/**
	 *	Constructor method
	 *	
	 * 	@var 	Int $layers 		Number of layers 				(optional)
	 * 	@var 	Int $binaryLength 	Size of encoded binary 			(optional)
	 *	@return Encrypter object 	Initialized encryption object
	 */
	public function __construct($layers = 2, $binaryLength = 8) {
		$this->layers = $layers;
		$this->binaryLength = $binaryLength;

		return $this;
	}

	/**
	 *  Encodes text input into a pixel code array.
	 *	
	 *	@var 	String/Int 	$text 	Message to be encrypted
	 *	@return Array 		Pixel codes
	 */
	public function encode($text)
	{
		$encrypted = $this->convert($text);
		$binaryString = $this->toBinary($encrypted);
		$layer = $this->sortLayers($binaryString);
		$encoded = $this->encodeLayers($layer);

		return $this->makeCode($encoded);
	}

	/**
	 *  Generate pixel codes.
	 *	
	 *	@var 	Array 	$encoded 	Encoded layers
	 *	@return Array 	Pixel codes
	 */
	private function makeCode(Array $encoded)
	{
		$code = [];
		for ($i=0; $i < sizeof($encoded); $i+=2) {
			$code[] = (isset($encoded[$i+1])) ? '0' . $encoded[$i] . $encoded[$i+1] : '1' . $encoded[$i] . 0;
		}

		return $code;
	}

	/**
	 *  Encode values to layers.
	 *	
	 *	@var 	Array 	$layer 	Sorted layers
	 *	@return Array 	Encoded layers
	 */
	private function encodeLayers(Array $layer)
	{
		$encoded = [];
		for ($i=0; $i < sizeof($layer[0]); $i++) {
			$str = "";
			for($j = 0; $j < $this->layers; $j++) {
				$str .= $layer[$j][$i];
			}
			$encoded[] = bindec($str);
		}

		return $encoded;
	}

	/**
	 *  Sorts layers.
	 *	
	 *	@var 	String 	$binaryString 	Binary string sequence
	 *	@return Array 	Sorted layers
	 */
	private function sortLayers($binaryString)
	{
		$layer = [];
		$length = strlen($binaryString);
		for($i = 0; $i < $this->layers; $i++) {
			$layer[] = str_split(substr($binaryString, $i * $length/$this->layers, $length/$this->layers));
		}

		return $layer;
	}

	/**
	 *  Converts ASCII codes into a binary string sequence.
	 *	
	 *	@var 	Array 	$encrypted	ASCII codes 	
	 *	@return String 	Binary string sequence
	 */
	private function toBinary(Array $encrypted)
	{
		$binary = [];
		foreach($encrypted as $dec) {
			$binary[] = str_pad(decbin($dec), $this->binaryLength, '0', STR_PAD_LEFT);
		}

		return implode('', $binary);
	}

	/**
	 *  Converts a text input into ASCII codes.
	 *	
	 *	@var 	String/Int 	$text 	Message to be encrypted	 	
	 *	@return Array 	ASCII codes
	 */
	private function convert($text) {
		foreach (str_split($text) as $key => $c)
		{
			$coded[$key] = ord($c);
		}

		return $coded;
	}

}