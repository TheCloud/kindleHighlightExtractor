<?php
require_once("src/HTMLHighlightsImporter.php");

$importer = new HTMLHighlightsImporter('tests/title2.html');
$importer->import();

$bookTitle = $importer->getBookTitle();
$highlights = $importer->getHighlights();

echo $importer->generateAnkiXML();

exit;
foreach ($highlights as $highlight) {
	print_r($highlight);
    echo "\n";
}

