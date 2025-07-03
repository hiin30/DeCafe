<?php
// Mulai sesi
session_start();

// Masukkan koneksi database Anda
require_once 'connect.php';

// Atur header untuk format JSON
header('Content-Type: application/json');

// Fungsi untuk merespon permintaan dengan JSON
function jsonResponse($status, $message, $data = []) {
    echo json_encode(array_merge(['status' => $status, 'message' => $message], $data));
    exit;
}

// Pastikan metode permintaan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Invalid request method');
}

// Validasi input
$kodeOrder = isset($_POST['kode_order']) ? trim($_POST['kode_order']) : null;
$total = isset($_POST['total']) ? (float)$_POST['total'] : null;

if (!$kodeOrder || !$total) {
    jsonResponse('error', 'Kode order dan total harus diisi');
}

try {
    // Masukkan data ke tabel tb_bayar
    $stmt = $conn->prepare("INSERT INTO tb_bayar (kode_order, metode, total, status, created_at) VALUES (?, ?, ?, ?, NOW())");
    $metode = 'tunai'; // Metode tunai
    $status = 'success'; // Status pembayaran langsung sukses
    $stmt->bind_param('ssds', $kodeOrder, $metode, $total, $status);

    if ($stmt->execute()) {
        jsonResponse('success', 'Pembayaran tunai berhasil');
    } else {
        jsonResponse('error', 'Gagal menyimpan data pembayaran');
    }
} catch (Exception $e) {
    jsonResponse('error', 'Terjadi kesalahan: ' . $e->getMessage());
}
