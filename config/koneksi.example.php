<?php
$host = "localhost";
$user = "NAMA USER";
$pass = "PASSWORD AKUN";
$db   = "NAMA DATABASE";

$koneksi = new mysqli($host, $user, $pass, $db);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}