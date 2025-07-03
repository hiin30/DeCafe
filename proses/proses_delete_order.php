<?php
include "connect.php";
$kode_order = (isset($_POST['kode_order'])) ? htmlentities($_POST['kode_order']) : "";

// Validasi input tidak boleh kosong
if (!empty($_POST['delete_order_validate'])) {
    $select = mysqli_query($conn, "SELECT kode_order FROM tb_list_order WHERE kode_order = '$kode_order'");
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>alert("Order telah memiliki item order, data order ini tidak dapat di hapus");
                    window.location="../order"</script>';
    } else {  // Query update
        $query = mysqli_query($conn, "DELETE FROM tb_order WHERE id_order ='$kode_order'");

        if ($query) { // Jika query berhasil
            $message = '<script>alert("Data berhasil dihapus");
                    window.location="../order"</script>';
        } else { // Jika query gagal
            $message = '<script>alert("Data gagal dihapus: ' . mysqli_error($conn) . '");
                    window.location="../order"</script>';
        }
    }
}
echo $message;
?>