<?php

require_once("BookHighlightsImporter.php");

$importer = new BookHighlightsImporter('tests/title.csv');
$importer->import();

echo $importer->generateAnkiXML();

$bookTitle = $importer->getBookTitle();
$highlights = $importer->getHighlights();

echo "Titolo del libro: " . $bookTitle . "\n";

foreach ($highlights as $highlight) {
    if (!empty($highlight['note'])) {
            print_r($highlight);
	}
}

?>
