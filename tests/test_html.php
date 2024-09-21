<?php
require_once("HTMLHighlightsImporter.php");

$importer = new HTMLHighlightsImporter('tests/title2.html');
$importer->import();

$bookTitle = $importer->getBookTitle();
$highlights = $importer->getHighlights();

echo "Titolo del libro: " . $bookTitle . "\n\n";

foreach ($highlights as $highlight) {
    echo "Evidenziazione: " . $highlight['content'] . "\n";
    if (!empty($highlight['note'])) {
        echo "Nota: " . $highlight['note'] . "\n";
    }
    echo "\n";
}

