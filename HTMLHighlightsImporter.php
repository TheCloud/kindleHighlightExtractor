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
        $this->extractHighlights($dom);

        return $this;
    }

    private function extractBookTitle($dom) {
        $xpath = new DOMXPath($dom);
        $titleElement = $xpath->query("//div[contains(@class, 'bookTitle')]")->item(0);
        if ($titleElement) {
            $this->bookTitle = trim($titleElement->textContent);
        }
    }

    private function extractHighlights($dom) {
        $xpath = new DOMXPath($dom);
        $highlights = $xpath->query("//div[contains(text(), 'Evidenziazione')]");

        foreach ($highlights as $highlight) {
            $content = $highlight->nextSibling;
            $note = null;

            // Cerca la nota associata
            $nextElement = $content->nextSibling;
            while ($nextElement) {
                if (strpos(trim($nextElement->textContent), 'Nota') === 0) {
                    $note = $nextElement->nextSibling->nextSibling;
                    break;
                } else $content=$nextElement;
                $nextElement = $nextElement->nextSibling;
            }

            if ($content) {
                $highlightData = [
                    'title' => $this->bookTitle,
                    'content' => trim($content->textContent),
                    'note' => trim($note->textContent)
                ];
                $this->highlights[] = $highlightData;
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
