<?php
require_once("AnkiAppXMLGenerator.class.php");

$parser = new AnkiAppXMLGenerator('tests/My Clippings.txt');
$parser->setLanguage('it'); // Set language of file as it's different from Kindle device language

$parser->parse();

$xml = $parser->generateAnkiAppXML();

echo $xml;

// Salva il file XML
//file_put_contents('anki_decks.xml', $xml);
