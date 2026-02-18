<?php
try {
    $dsn = "mysql:host=127.0.0.1;port=3306;dbname=absenmfls;charset=utf8mb4";
    $pdo = new PDO($dsn, "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Koneksi Berhasil!\n";
    
    // Coba query sederhana
    $stmt = $pdo->query("SELECT VERSION()");
    echo "MySQL Version: " . $stmt->fetchColumn() . "\n";
} catch (PDOException $e) {
    echo "Koneksi GAGAL: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
}
