<?php

// Import classes
foreach (glob("Core/*.php") as $filename)
{
	include $filename;
}
use Core\Encrypter, Core\Decrypter, Core\Pixels;

// Get input text to be encoded
$msg = file_get_contents('msg.txt');

/**
 *	Encrypt
 *	@Requires	$msg	Input text
 *	@Returns 	Encoded file name
 */
$encrypter = new Encrypter;				// Initializes Encrypter class
$encrypt = $encrypter->encode($msg); 	// Encodes the text into pixel codes
$fileName = Pixels::make($encrypt);		// Saves pixel codes into file


/**
 *	Decrypt
 *	@Requires 	$fileName	Encoded file name
 *	@Returns 	Input text
 */
$code = Pixels::read($fileName);		// Gets pixel codes from file
$decrypter = new Decrypter;				// Initializes Decrypter class
echo $decrypter->decode($code);			// Decodes pixel codes into text