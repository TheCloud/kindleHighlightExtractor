<?php

// Examble on how to use the class
include("KindleClippingsParser.class.php");

// Specify the name of the file
$parser = new KindleClippingsParser('tests/My Clippings.txt');
$parser->setLanguage('it'); // Set language of file as it's different from Kindle device language
$parser->parse();

$allClippings = $parser->getClippings();

/*
[title] => Process Communication model® (Italian Edition) (Collignon, Gérard)
            [metadata] => - La tua nota a pagina 14 | posizione 125 | Aggiunto in data lunedì 16 settembre 2024 16:13:54
            [content] => trentacinque anni.
            [type] => highlight
            [position] => 125
            [note] => nota testo 35 anni
*/
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
