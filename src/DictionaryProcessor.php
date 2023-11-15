<?php

class DictionaryProcessor
{
    private $letterCounts = [];

    public function processFile($url, $sourceEncoding = 'CP1251')
    {
        $content = file_get_contents($url);
        $content = mb_convert_encoding($content, 'UTF-8', $sourceEncoding);

        $words = explode("\n", $content);

        foreach ($words as $word) {
            $this->processWord($word);
        }

        $this->saveCounts();
    }

    private function processWord($word)
    {
        preg_match('/^.+/u', $word, $matches);
    
        if (!empty($matches)) {
            $firstLetter = mb_strtolower(mb_substr($matches[0], 0, 1), 'UTF-8');
            $wordLetters = preg_split('//u', mb_strtolower($matches[0], 'UTF-8'), -1, PREG_SPLIT_NO_EMPTY);
            foreach ($wordLetters as $wordLetter) {
                
                if ($wordLetter === $firstLetter) {
                    $this->letterCounts[$firstLetter] = isset($this->letterCounts[$firstLetter]) ? $this->letterCounts[$firstLetter] + 1 : 1;
                }
            }

            $folderPath = "library/{$firstLetter}";
            $filePath = "{$folderPath}/words.txt";
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
            file_put_contents($filePath, $word . PHP_EOL, FILE_APPEND);
        } else {
            echo "No match found for word: $word\n";
        }
    }
  
    private function saveCounts()
    {
        foreach ($this->letterCounts as $letter => $count) {
            $folderPath = "library/{$letter}";
            $countFilePath = "{$folderPath}/count.txt";

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            file_put_contents($countFilePath, $count, LOCK_EX);
        }
    }

    public function handleWebUpload($file)
    {
        if (! empty($file)) {
            $content = file_get_contents($file);
            $content = mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content));

            $words = explode("\n", $content);

            foreach ($words as $word) {
                $this->processWord($word);
            }

            $this->saveCounts();
        }
    }
}
