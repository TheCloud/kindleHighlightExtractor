<?php

include("KindleClippingsParser.class.php");

$parser = new KindleClippingsParser('My Clippings2.txt');
$parser->setLanguage('it'); // Set language of file as it's different from Kindle device language
$parser->parse();

$allClippings = $parser->getClippings();
print_r($allClippings);

$bookClippings = $parser->getClippingsByTitle('Titolo del libro');
