<?php

class BookHighlightsImporter {
    private $filePath;
    private $bookTitle;
    private $highlights = [];

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function import() {
        $file = fopen($this->filePath, 'r');
        
        // Leggi il titolo del libro dalla cella B1 (seconda colonna, prima riga)
        $firstRow = fgetcsv($file);
        $firstRow = fgetcsv($file);
        $this->bookTitle = $firstRow[0] ?? '';

        // Salta le prime 6 righe
        for ($i = 0; $i < 4; $i++) {
            fgetcsv($file);
        }

        $currentHighlight = null;

        while (($row = fgetcsv($file)) !== false) {
            $type = $row[0];
            $text = $row[3] ?? '';

            if ($type === 'Highlight (Yellow)') {
                if ($currentHighlight !== null) {
                    $this->highlights[] = $currentHighlight;
                }
                $currentHighlight = ['highlight' => $text, 'note' => ''];
            } elseif ($type === 'Note' && $currentHighlight !== null) {
                $currentHighlight['note'] = $text;
            }
        }

        // Aggiungi l'ultimo highlight se presente
        if ($currentHighlight !== null) {
            $this->highlights[] = $currentHighlight;
        }

        fclose($file);

        return $this;
    }

    public function getBookTitle() {
        return $this->bookTitle;
    }

    public function getHighlights() {
        return $this->highlights;
    }
}
