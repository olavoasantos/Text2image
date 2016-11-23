<?php

namespace Core;

class Decrypter {
	
	/**
	 *	Number of layers to which the text will be decoded.
	 *	
	 *	@var Int $layers
	 */
	private $layers;

	/**
	 *	Number of bits to which the character will be decoded.
	 *	
	 *	@var Int $binaryLength
	 */
	private $binaryLength;

	/**
	 *	Constructor method
	 *	
	 * 	@var 	Int $layers 		Number of layers 				(optional)
	 * 	@var 	Int $binaryLength 	Size of encoded binary 			(optional)
	 *	@return Decrypter object 	Initialized decryption object
	 */
	public function __construct($layers = 2, $binaryLength = 8) {
		$this->layers = $layers;
		$this->binaryLength = $binaryLength;

		return $this;
	}

	/**
	 *  Decodes a sequence of pixel codes into a readable message.
	 *	
	 *	@var 	Array 		$code 	Pixel codes
	 *	@return String/Int 	Decoded message
	 */
	public function decode(Array $code) {
		$values = $this->getValues($code);
		$layer = $this->sortLayer($values);
		$binaryArray = $this->makeBinaryArray($layer);

		return $this->decodeMessage($binaryArray);
	}

	/**
	 *  Decodes message.
	 *	
	 *	@var 	Array 	$binaryArray 	Ordered sequence of bits
	 *	@return String 	Decoded message
	 */
	private function decodeMessage(Array $binaryArray)
	{
		$msg = '';
		for ($i=0; $i < sizeof($binaryArray); $i+=$this->binaryLength) {
			$str = '';
			for ($j=0; $j < $this->binaryLength; $j++) { 
				$str .= (isset($binaryArray[$i + $j])) ? $binaryArray[$i + $j] : '';
			}

			$msg .= chr(bindec($str));
		}

		return $msg;
	}

	/**
	 *  Generates an ordered binary array from sorted layers.
	 *	
	 *	@var 	Array 	$layer 	Sorted layers
	 *	@return Array 	Ordered sequence of bits
	 */
	private function makeBinaryArray(Array $layer)
	{
		$binaryArray = [];
		for ($i=0; $i < $this->layers; $i++) { 
			$binaryArray = array_merge($binaryArray, $layer[$i]);
		}

		return $binaryArray;
	}

	/**
	 *  Sorts layers from decoded values.
	 *	
	 *	@var 	Array 	$values 	Decoded values
	 *	@return Array 	Sorted layers
	 */
	private function sortLayer(Array $values)
	{
		$layer = [];
		foreach ($values as $key => $value) {
			$val = str_pad(decbin($value), $this->layers, '0', STR_PAD_LEFT);
			for ($j=0; $j < $this->layers; $j++) { 
				$layer[$j][] = intval(substr($val, $j,1));
			}
		}

		return $layer;
	}

	/**
	 *  Decodes values from pixel code.
	 *	
	 *	@var 	Array 	$code 	Pixel code
	 *	@return Array 	Decoded values
	 */
	private function getValues(Array $code)
	{
		$values = [];
		foreach ($code as $value) {			
			if(substr($value, 0, 1) === '0') {
				$values[] = intval(substr($value, 1,1));
				$values[] = intval(substr($value, 2,1));
			} elseif (substr($value, 0, 1) === '1') {
				$values[] = intval(substr($value, 1,1));
			}
		}

		return $values;
	}

}