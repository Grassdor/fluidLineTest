<?php

require_once('src/DatabaseWriter.php');

try {
    $dsn = "localhost";
    $username = "user";
    $password = "pass";

    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $databaseWriter = new DatabaseWriter($pdo);
    $databaseWriter->writeToDatabase();
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
