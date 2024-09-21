<?php
require_once("HTMLHighlightsImporter.php");
$importer = new HTMLHighlightsImporter('tests/title.html');
$importer->import();

$bookTitle = $importer->getBookTitle();
$highlights = $importer->getHighlights();

echo "Titolo del libro: " . $bookTitle . "\n";

print_r($highlights);

foreach ($highlights as $highlight) {
    echo "Highlight: " . $highlight['content'] . "\n";
    if (!empty($highlight['note'])) {
        echo "Nota: " . $highlight['note'] . "\n";
    }
    echo "\n";
}

?>
