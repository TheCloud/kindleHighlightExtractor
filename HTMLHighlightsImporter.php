<?php

class HTMLHighlightsImporter {
    private $filePath;
    private $bookTitle;
    private $highlights = [];

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function import() {
        $html = file_get_contents($this->filePath);
        $dom = new DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR);

        $this->extractBookTitle($dom);
        $this->extractHighlightsAndNotes($dom);

        return $this;
    }

    private function extractBookTitle($dom) {
        $xpath = new DOMXPath($dom);
        $titleElement = $xpath->query("//div[contains(@class, 'bookTitle')]")->item(0);
        if ($titleElement) {
            $this->bookTitle = trim($titleElement->textContent);
        }
    }

private function extractHighlightsAndNotes($dom) {
    $xpath = new DOMXPath($dom);
    $elements = $xpath->query("//div[contains(@class, 'noteHeading') or contains(@class, 'noteText')]");

    $currentHighlight = null;

    foreach ($elements as $element) {
        if (strpos($element->getAttribute('class'), 'noteHeading') !== false) {
            if (strpos(trim($element->textContent), 'Evidenziazione') === 0) {
                $currentHighlight = [
                    'content' => '',
                    'note' => ''
                ];
            } elseif (strpos(trim($element->textContent), 'Nota') === 0 && $currentHighlight !== null) {
                // Abbiamo trovato una nota, ma aspettiamo il suo contenuto
                continue;
            }
        } elseif (strpos($element->getAttribute('class'), 'noteText') !== false) {
            if ($currentHighlight !== null) {
                if (empty($currentHighlight['content'])) {
                    $currentHighlight['content'] = trim($element->textContent);
                } else {
                    $currentHighlight['note'] = trim($element->textContent);
                    // Aggiungiamo l'highlight solo se ha sia il contenuto che la nota
                    if (!empty($currentHighlight['content']) && !empty($currentHighlight['note'])) {
                        $this->highlights[] = $currentHighlight;
                    }
                    $currentHighlight = null;
                }
            }
        }
    }
}

    public function getBookTitle() {
        return $this->bookTitle;
    }

    public function getHighlights() {
        return $this->highlights;
    }
}
