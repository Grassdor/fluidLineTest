<?php

class DatabaseWriter
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function writeToDatabase()
    {
        $directory = 'library';
        $folders = glob($directory . '/*', GLOB_ONLYDIR);

        foreach ($folders as $folder) {
            $letter = basename($folder);

            $countFile = $folder . '/count.txt';
            if (file_exists($countFile)) {
                $countValue = trim(file_get_contents($countFile));
                $this->insertCount($letter, $countValue);
            }
        }
    }

    private function insertCount($letter, $count)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO dictionary (letter, count) VALUES (?, ?)");
            $stmt->execute([$letter, $count]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
