<?php

class KindleClippingsParser {
  private $clippingsFile;
  private $clippings = [];

  public function __construct($filePath) {
    $this->clippingsFile = $filePath;
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
        
        if ($type === 'nota') {
          if (isset($this->clippings[$key])) {
            $this->clippings[$key]['note'] = $content;
          } else {
            $this->findAndAssociateNote($title, $position, $content);
          }
        } else {
          if (isset($this->clippings[$key])) {
            $this->clippings[$key]['content'] = $content;
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
    if (strpos($metadata, 'Your Note') !== false || strpos($metadata, 'La tua nota') !== false) {
      return 'nota';
    } elseif (strpos($metadata, 'Your Highlight') !== false || strpos($metadata, 'La tua evidenziazione') !== false) {
      return 'evidenziazione';
    } else {
      return 'altro';
    }
  }

  private function extractPosition($metadata) {
    if (preg_match('/posizione (\d+)-?(\d+)?/', $metadata, $matches)) {
      return isset($matches[2]) ? $matches[2] : $matches[1];
    } elseif (preg_match('/location (\d+)-?(\d+)?/', $metadata, $matches)) {
      return isset($matches[2]) ? $matches[2] : $matches[1];
    }
    return '';
  }

  private function findAndAssociateNote($title, $notePosition, $noteContent) {
    foreach ($this->clippings as $key => $clipping) {
      list($clipTitle, $clipPosition) = explode('|', $key);
      if ($clipTitle === $title && $clipPosition === $notePosition) {
        $this->clippings[$key]['note'] = $noteContent;
        return;
      }
    }
    // Se non trova una corrispondenza, crea una nuova entry per la nota
    $this->clippings[$title . '|' . $notePosition] = [
      'title' => $title,
      'metadata' => '',
      'content' => '',
      'type' => 'nota',
      'position' => $notePosition,
      'note' => $noteContent
    ];
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
