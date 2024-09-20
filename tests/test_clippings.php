<?php

// Examble on how to use the class
include("KindleClippingsParser.class.php");

// Specify the name of the file
$parser = new KindleClippingsParser('tests/My Clippings.txt');
$parser->setLanguage('it'); // Set language of file as it's different from Kindle device language
$parser->parse();

$allClippings = $parser->getClippings();
print_r($allClippings);

// Search a clipping by Title
// $bookClippings = $parser->getClippingsByTitle('Titolo del libro');
