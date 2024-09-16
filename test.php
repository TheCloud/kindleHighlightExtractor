<?php

include("KindleClippingsParser.class.php");

$parser = new KindleClippingsParser('My Clippings2.txt');
$parser->parse();

$allClippings = $parser->getClippings();
print_r($allClippings);

$bookClippings = $parser->getClippingsByTitle('Titolo del libro');
