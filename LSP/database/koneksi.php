<?php 
$host = 'localhost';
$user = 'root';  // Username MySQL
$pass = '';      // Password MySQL
$db   = 'inventory_db';  // Nama database kamu

// Membuat koneksi ke database
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
    if (!$conn) {
         die("Connection failed: " . mysqli_connect_error());
    }