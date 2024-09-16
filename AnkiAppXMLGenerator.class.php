<?php
require_once 'KindleClippingsParser.class.php';

class AnkiAppXMLGenerator extends KindleClippingsParser {
    public function generateAnkiAppXML() {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        
        foreach ($this->clippings as $book => $entries) {
            $xml .= "<deck name=\"" . htmlspecialchars($book) . "\">\n";
            $xml .= "  <fields>\n";
            $xml .= "    <text lang=\"en-US\" name=\"Note\" sides=\"11\"></text>\n";
            $xml .= "    <text lang=\"en-US\" name=\"Highlight\" sides=\"01\"></text>\n";
            $xml .= "  </fields>\n";
            $xml .= "  <cards>\n";
            
            foreach ($entries as $entry) {
                if (!empty($entry['note']) && !empty($entry['content'])) {
                    $xml .= "    <card>\n";
                    $xml .= "      <field name=\"Note\">" . htmlspecialchars($entry['note']) . "</field>\n";
                    $xml .= "      <field name=\"Highlight\">" . htmlspecialchars($entry['content']) . "</field>\n";
                    $xml .= "    </card>\n";
                }
            }
            
            $xml .= "  </cards>\n";
            $xml .= "</deck>\n";
        }
        
        return $xml;
    }
}
