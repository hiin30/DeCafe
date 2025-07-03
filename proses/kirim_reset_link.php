<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Koneksi ke database
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "db_decafe");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}


    // Ambil email dari form
    $email = $conn->real_escape_string($_POST['email']);

    // Cek apakah email terdaftar
    $query = "SELECT * FROM tb_user WHERE username = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Generate password random
        $new_password = bin2hex(random_bytes(4)); // Contoh password: 8 karakter heksadesimal
        $hashed_password = hash('md5', $new_password);

        // Update password di database
        $update_query = "UPDATE tb_user SET password = '$hashed_password' WHERE username = '$email'";
        if ($conn->query($update_query) === TRUE) {
            echo "
                <script>
                    alert('Password baru Anda adalah: $new_password');
                    window.location.href = '../index.php';
                </script>
            ";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "
            <script>
                alert('Email tidak ditemukan.');
                window.history.back();
            </script>
        ";
    }

    $conn->close();
}
?>
