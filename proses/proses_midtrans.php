<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../config/midtrans.php';
require_once 'connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Debug log
        error_log('POST Data: ' . print_r($_POST, true));

        // Handle update payment status
        if (isset($_POST['update_payment']) && $_POST['update_payment'] === 'true') {
            $kode_order = $_POST['kode_order'];
            $status = $_POST['status'];
            $total = $_POST['total'];

            // Update status pembayaran dan status pesanan
            $update_query = "UPDATE tb_order SET
                status = 'dibayar' 
                WHERE id_order = '$kode_order'";

            if (!mysqli_query($conn, $update_query)) {
                throw new Exception('Gagal mengupdate status pembayaran');
            }

            // insert ke tb_bayar
            $insert_bayar = "INSERT INTO tb_bayar (id_bayar, total_bayar, status, waktu_bayar, createdby, createddate) 
                            VALUES ('$kode_order', $total, 1, NOW(), " . $_SESSION['id_decafe'] . ", NOW())";

            if (!mysqli_query($conn, $insert_bayar)) {
                throw new Exception('Gagal menyimpan data pembayaran');
            }

            echo json_encode(['status' => 'success', 'message' => 'Status pembayaran berhasil diupdate']);
            exit;
        }
        
        // Validasi input
        if (!isset($_POST['kode_order']) || !isset($_POST['total']) || !isset($_POST['payment_method'])) {
            throw new Exception('Data pembayaran tidak lengkap');
        }

        $kode_order = $_POST['kode_order'];
        $total = $_POST['total'];
        $payment_method = $_POST['payment_method'];

        // Validasi order exists
        $query = mysqli_query($conn, "SELECT * FROM tb_order WHERE id_order = '$kode_order'");
        if (!$query || mysqli_num_rows($query) === 0) {
            throw new Exception('Order tidak ditemukan');
        }
        $order = mysqli_fetch_assoc($query);

        // Ambil data items
        $items_query = mysqli_query($conn, "SELECT tlo.*, tdm.nama_menu, tdm.harga 
                                          FROM tb_list_order tlo 
                                          JOIN tb_daftar_menu tdm ON tlo.menu = tdm.id 
                                          WHERE tlo.kode_order = '$kode_order'");
        
        if (!$items_query || mysqli_num_rows($items_query) === 0) {
            throw new Exception('Item order tidak ditemukan');
        }

        $items = [];
        while ($item = mysqli_fetch_assoc($items_query)) {
            $items[] = [
                'id' => $item['menu'],
                'price' => $item['harga'],
                'quantity' => $item['jumlah'],
                'name' => $item['nama_menu']
            ];
        }

        // Buat order ID unik dengan format: ORDER-{kode_order}-{random_string}
        $random_string = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);
        $midtrans_order_id = 'ORDER-' . $kode_order . '-' . $random_string;
        
        $transaction_details = array(
            'order_id' => $midtrans_order_id,
            'gross_amount' => (int)$total
        );

        $customer_details = array(
            'first_name' => $order['pelanggan'],
            'email' => 'customer@test.com',
            'phone' => '08111222333'
        );

        $transaction = array(
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $items
        );

        // Set enabled payments berdasarkan metode yang dipilih
        if ($payment_method === 'qris') {
            $transaction['enabled_payments'] = ['qris', 'gopay', 'shopeepay'];
        } elseif ($payment_method === 'transfer') {
            $transaction['enabled_payments'] = ['bca_va', 'bni_va', 'bri_va', 'permata_va'];
        }

        // Set custom expiry
        $transaction['custom_expiry'] = [
            'order_time' => date('Y-m-d H:i:s O'),
            'expiry_duration' => 60,
            'unit' => 'minute'
        ];

        // Validasi Midtrans Config
        if (empty(\Midtrans\Config::$serverKey)) {
            throw new Exception('Midtrans Server Key belum dikonfigurasi');
        }

        // Get Snap Payment Page URL
        $snapToken = \Midtrans\Snap::getSnapToken($transaction);
        
        // Simpan token ke database
        $update_query = "UPDATE tb_order SET snap_token = '$snapToken' WHERE id_order = '$kode_order'";
        if (!mysqli_query($conn, $update_query)) {
            throw new Exception('Gagal menyimpan token pembayaran');
        }

        echo json_encode(['status' => 'success', 'token' => $snapToken]);
    } catch (\Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

// Handle Midtrans notification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_MIDTRANS_NOTIFICATION'])) {
    try {
        $notif = new \Midtrans\Notification();
        
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $midtrans_order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        // Extract original order ID from Midtrans order ID
        // Format: ORDER-{kode_order}-{random_string}
        $order_id = explode('-', $midtrans_order_id)[1];

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if($fraud == 'challenge') {
                    $payment_status = 'challenge';
                } else {
                    $payment_status = 'success';
                }
            }
        } else if ($transaction == 'settlement') {
            $payment_status = 'settlement';
        } else if($transaction == 'pending') {
            $payment_status = 'pending';
        } else if ($transaction == 'deny') {
            $payment_status = 'deny';
        } else if ($transaction == 'expire') {
            $payment_status = 'expire';
        } else if ($transaction == 'cancel') {
            $payment_status = 'cancel';
        }

        // Update status pembayaran di database
        $status_pembayaran = ($payment_status == 'settlement' || $payment_status == 'success') ? 'dibayar' : 'belum_dibayar';
        $status_order = ($payment_status == 'settlement' || $payment_status == 'success') ? 'dibayar' : 'belum_dibayar';
        
        $update_query = "UPDATE tb_order SET 
            payment_status = '$payment_status',
            status = '$status_order'
            WHERE id_order = '$order_id'";

        if ($status_order == 'dibayar') {
            $insert_bayar = "INSERT INTO tb_bayar (id_bayar, total_bayar, status, waktu_bayar, createdby, createddate) 
                            VALUES ('$order_id', $total_bayar, 3, NOW(), " . $_SESSION['id_decafe'] . ", NOW())";

            if (!mysqli_query($conn, $insert_bayar)) {
                throw new Exception('Gagal menyimpan data pembayaran');
            }
        }

        if (!mysqli_query($conn, $update_query)) {
            throw new Exception('Gagal mengupdate status pembayaran');
        }

        echo json_encode(['status' => 'success']);
    } catch (\Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        http_response_code(500);
    }
}
?> 