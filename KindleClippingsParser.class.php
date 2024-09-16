<?php

class KindleClippingsParser {
  private $clippingsFile;
  private $clippings = [];
  private $language;
  private $languageStrings = [
    'en' => [
      'note' => 'Your Note',
      'highlight' => 'Your Highlight',
      'bookmark' => 'Your Bookmark',
      'location' => 'location',
      'page' => 'page'
    ],
    'it' => [
      'note' => 'La tua nota',
      'highlight' => 'La tua evidenziazione',
      'bookmark' => 'Il tuo segnalibro',
      'location' => 'posizione',
      'page' => 'pagina'
    ]
    // Add more languages please
  ];

  public function __construct($filePath, $language = 'en') {
    $this->clippingsFile = $filePath;
    $this->setLanguage($language);
  }

  public function setLanguage($language) {
    if (!isset($this->languageStrings[$language])) {
      throw new Exception("Lingua non supportata: $language");
    }
    $this->language = $language;
  }

  public function parse() {
    $content = file_get_contents($this->clippingsFile);
    $entries = explode("==========", $content);
    
    foreach ($entries as $entry) {
      $lines = explode("\n", trim($entry));
      if (count($lines) >= 4) {
        $title = trim($lines[0]);
        $metadata = trim($lines[1]);
        $type = $this->getClippingType($metadata);
        $content = trim(implode("\n", array_slice($lines, 3)));
        
        $position = $this->extractPosition($metadata);
        
        $key = $title . '|' . $position;
        
        if ($type === 'note') {
          if (isset($this->clippings[$key])) {
            $this->clippings[$key]['note'] = $content;
          } else {
            $this->clippings[$key] = [
              'title' => $title,
              'metadata' => $metadata,
              'content' => '',
              'type' => $type,
              'position' => $position,
              'note' => $content
            ];
          }
        } else {
          if (isset($this->clippings[$key])) {
            $this->clippings[$key]['content'] = $content;
            $this->clippings[$key]['type'] = $type;
          } else {
            $this->clippings[$key] = [
              'title' => $title,
              'metadata' => $metadata,
              'content' => $content,
              'type' => $type,
              'position' => $position,
              'note' => null
            ];
          }
        }
      }
    }
  }

  private function getClippingType($metadata) {
    $strings = $this->languageStrings[$this->language];
    if (strpos($metadata, $strings['note']) !== false) {
      return 'note';
    } elseif (strpos($metadata, $strings['highlight']) !== false) {
      return 'highlight';
    } elseif (strpos($metadata, $strings['bookmark']) !== false) {
      return 'bookmark';
    } else {
      return 'other';
    }
  }

  private function extractPosition($metadata) {
    $strings = $this->languageStrings[$this->language];
    $pattern = '/' . $strings['location'] . ' (\d+)-?(\d+)?/i';
    if (preg_match($pattern, $metadata, $matches)) {
      return isset($matches[2]) ? $matches[2] : $matches[1];
    }
    return '';
  }

  public function getClippings() {
    return array_values($this->clippings);
  }

  public function getClippingsByTitle($title) {
    return array_filter($this->getClippings(), function($clipping) use ($title) {
      return stripos($clipping['title'], $title) !== false;
    });
  }
}
