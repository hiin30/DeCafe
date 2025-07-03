<?php
if (isset($_GET['newpass'])) {
    $newpass = htmlspecialchars($_GET['newpass']);
    echo "<script>
        alert('Password baru kamu: $newpass');
    </script>";
}

if (isset($_GET['error']) && $_GET['error'] == 'emailnotfound') {
    echo "<script>alert('Email tidak ditemukan!');</script>";
}
?>
