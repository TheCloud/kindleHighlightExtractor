<?php

// Example on how to use the class
include("KindleClippingsParser.class.php");

// Specify the name of the file
$parser = new KindleClippingsParser('tests/My Clippings.txt');
$parser->setLanguage('it'); // Set language of file as it's different from Kindle device language
$parser->parse();

$allClippings = $parser->getClippings();

// Where the CSV will be written
$fn='kindleToAnki_'.date("d-m-Y_His").'.csv';
$fp = fopen($fn, 'w');

$x=0;
foreach ($allClippings as $clip) {
    $campi=array($clip['note'],$clip['content'],str_replace(",","/",$clip['title']));
    if (!empty($clip['note']) AND !empty($clip['content'])) {
		fputcsv($fp, $campi);
		$x++;
	}
}
echo "Written $x notes to $fn.\n";
