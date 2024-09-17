<?php

require_once 'KindleClippingsParser.class.php';

class AnkiAppXMLGenerator extends KindleClippingsParser {
    public function generateAnkiAppXML() {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        
        $books = $this->organizeClippingsByBook();
        
        foreach ($books as $bookTitle => $clippings) {
	    if (count($clippings)==0) continue;
            $xml .= "<deck name=\"" . htmlspecialchars($bookTitle) . "\">\n";
            $xml .= "  <fields>\n";
            //$xml .= "    <text lang=\"en-US\" name=\"Note\" sides=\"11\"></text>\n";
            $xml .= "    <text lang=\"en-US\" name=\"Count\" sides=\"01\">".count($clippings)."</text>\n";
            $xml .= "  </fields>\n";
            $xml .= "  <cards>\n";
            
            foreach ($clippings as $clipping) {
                if (!empty($clipping['note']) && !empty($clipping['content'])) {
                    $xml .= "    <card>\n";
                    $xml .= "      <field name=\"Text\">" . htmlspecialchars($clipping['note']) . "</field>\n";
                    $xml .= "      <field name=\"Translation\">" . htmlspecialchars($clipping['content']) . "</field>\n";
                    $xml .= "    </card>\n";
                }
            }
            
            $xml .= "  </cards>\n";
            $xml .= "</deck>\n";
        }
        
        return $xml;
    }

    private function organizeClippingsByBook() {
        $books = [];
        foreach ($this->clippings as $clipping) {
            $bookTitle = $clipping['title'];
            if (!isset($books[$bookTitle])) {
                $books[$bookTitle] = [];
            }
            $books[$bookTitle][] = $clipping;
        }
        return $books;
    }
}
