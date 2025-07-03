<?php

require_once 'vendor/autoload.php';


include "proses/connect.php";
$level_user = $_SESSION['level_decafe'];
$user_id = $_SESSION['id_decafe'];
$query = mysqli_query($conn, "SELECT *, SUM(harga*jumlah) AS harganya, tb_order.waktu_order FROM tb_list_order
    LEFT JOIN tb_order ON tb_order.id_order = tb_list_order.kode_order
    LEFT JOIN tb_daftar_menu ON tb_daftar_menu.id = tb_list_order.menu
    LEFT JOIN tb_bayar ON tb_bayar.id_bayar = tb_order.id_order
    GROUP BY id_list_order
    HAVING tb_list_order.kode_order = $_GET[order]");

$kode = $_GET['order'];
$meja = $_GET['meja'];
$pelanggan = $_GET['pelanggan'];

$result = []; // Inisialisasi array kosong
while ($record = mysqli_fetch_array($query)) {
    $result[] = $record; // Tambahkan hasil query ke array
    // $kode = $record['id_order'];
    // $meja = $record['meja'];
    // $pelanggan = $record['pelanggan'];
}

$select_menu = mysqli_query($conn, "SELECT id,nama_menu FROM tb_daftar_menu");
?>
<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            Halaman Order Item
        </div>
        <div class="card-body">
            <a href="order" class="btn btn-info mb-3"><i class="bi bi-arrow-left"></i></a>
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-floating mb-3">
                        <input disabled type="text" class="form-control" id="kodeorder" value="<?php echo $kode; ?>">
                        <label for="uploadfoto">Kode Order</label>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-floating mb-3">
                        <input disabled type="text" class="form-control" id="meja" value="<?php echo $meja; ?>">
                        <label for="uploadfoto">Meja</label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-floating mb-3">
                        <input disabled type="text" class="form-control" id="pelanggan"
                            value="<?php echo $pelanggan; ?>">
                        <label for="uploadfoto">Pelanggan</label>
                    </div>
                </div>
            </div>
            <!-- Modal Tambah Item Baru-->
            <div class="modal fade" id="tambahItem" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Menu Makanan dan Minuman</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="proses/proses_input_orderitem.php"
                                method="POST">
                                <input type="hidden" name="kode_order" value="<?php echo $kode ?>">
                                <input type="hidden" name="meja" value="<?php echo $meja ?>">
                                <input type="hidden" name="pelanggan" value="<?php echo $pelanggan ?>">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="menu" id="">
                                                <option selected hidden value="">Pilih Menu</option>
                                                <?php
                                                foreach ($select_menu as $value) {
                                                    echo "<option value=$value[id]>$value[nama_menu]</option>";
                                                }
                                                ?>
                                            </select>
                                            <label for="menu">Menu
                                                Makanan/Minuman</label>
                                            <div class="invalid-feedback">
                                                Pilih Menu
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="floatingInput"
                                                placeholder="Jumlah Porsi" name="jumlah" required>
                                            <label for="floatingInput">Jumlah Porsi</label>
                                            <div class="invalid-feedback">
                                                Masukkan Jumlah Porsi.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput"
                                                placeholder="Catatan" name="catatan">
                                            <label for="floatingPassword">Catatan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="input_orderitem_validate"
                                        value="12345">Save
                                        changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Modal Tambah Item Baru-->

            <?php
            if (empty($result)) {
                echo "Data menu makanan atau minuman tidak ada";
            } else {
                foreach ($result as $row) {
                    ?>

                    <!-- Modal Edit-->
                    <div class="modal fade" id="ModalEdit<?php echo $row['id_list_order'] ?>" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-fullscreen-md-down">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Menu Makanan dan Minuman</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="needs-validation" novalidate action="proses/proses_edit_orderitem.php"
                                        method="POST">
                                        <input type="hidden" name="id" value="<?php echo $row['id_list_order'] ?>">
                                        <input type="hidden" name="kode_order" value="<?php echo $kode ?>">
                                        <input type="hidden" name="meja" value="<?php echo $meja ?>">
                                        <input type="hidden" name="pelanggan" value="<?php echo $pelanggan ?>">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="form-floating mb-3">
                                                    <select class="form-select" name="menu" id="">
                                                        <option selected hidden value="">Pilih Menu</option>
                                                        <?php
                                                        foreach ($select_menu as $value) {
                                                            if ($row['menu'] == $value['id']) {
                                                                echo "<option selected value=$value[id]>$value[nama_menu]</option>";
                                                            } else {
                                                                echo "<option value=$value[id]>$value[nama_menu]</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <label for="menu">Menu
                                                        Makanan/Minuman</label>
                                                    <div class="invalid-feedback">
                                                        Pilih Menu
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <input type="number" class="form-control" id="floatingInput"
                                                        placeholder="Jumlah Porsi" name="jumlah" required
                                                        value="<?php echo $row['jumlah'] ?>">
                                                    <label for="floatingInput">Jumlah Porsi</label>
                                                    <div class="invalid-feedback">
                                                        Masukkan Jumlah Porsi.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="floatingInput"
                                                        placeholder="Catatan" name="catatan"
                                                        value="<?php echo $row['catatan'] ?>">
                                                    <label for="floatingPassword">Catatan</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" name="edit_orderitem_validate"
                                                value="12345">Save
                                                changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Akhir Modal Edit-->

                    <!-- Modal Delete-->
                    <div class="modal fade" id="ModalDelete<?php echo $row['id_list_order'] ?>" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-fullscreen-md-down">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Data User</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="needs-validation" novalidate action="proses/proses_delete_orderitem.php"
                                        method="POST">
                                        <input type="hidden" value="<?php echo $row['id_list_order'] ?>" name="id">
                                        <input type="hidden" name="kode_order" value="<?php echo $kode ?>">
                                        <input type="hidden" name="meja" value="<?php echo $meja ?>">
                                        <input type="hidden" name="pelanggan" value="<?php echo $pelanggan ?>">
                                        <div class="col-lg-12">
                                            Apakah anda ingin menghapus menu <b><?php echo $row['nama_menu'] ?></b>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger" name="delete_orderitem_validate"
                                                value="12345">Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Akhir Modal Delete-->

                    <?php
                }
                ?>

                <!-- Modal Bayar ADMIN-->
<div class="modal fade" id="bayar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Pembayaran</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-nowrap">
                                <th scope="col">Menu</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Status</th>
                                <th scope="col">Catatan</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            foreach ($result as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $row['nama_menu'] ?></td>
                                    <td><?php echo number_format($row['harga'], 0, ',', '.') ?></td>
                                    <td><?php echo $row['jumlah'] ?></td>
                                    <td><?php echo $row['status'] ?></td>
                                    <td><?php echo $row['catatan'] ?></td>
                                    <td><?php echo number_format($row['harganya'], 0, ',', '.') ?></td>
                                </tr>
                                <?php
                                $total += $row['harganya'];
                            }
                            ?>
                            <tr>
                                <td colspan="5" class="fw-bold">Total Harga</td>
                                <td class="fw-bold"><?php echo number_format($total, 0, ',', '.') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <span class="text-danger fs-5 fw-semibold">Apakah Anda Yakin Ingin Melakukan Pembayaran?</span>
                <form class="needs-validation" id="payment-form" novalidate>
                    <input type="hidden" name="kode_order" value="<?php echo $kode ?>">
                    <input type="hidden" name="meja" value="<?php echo $meja ?>">
                    <input type="hidden" name="pelanggan" value="<?php echo $pelanggan ?>">
                    <input type="hidden" name="total" value="<?php echo $total ?>">

                    <!-- Pilihan Metode Pembayaran -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Metode Pembayaran:</label>
                        <?php if ($level_user >= 1 && $level_user <= 4) { ?>
                            <!-- Opsi untuk Kasir -->
                            <div>
                                <input type="radio" id="cash" name="payment_method" value="cash" required>
                                <label for="cash">Tunai</label>
                            </div>
                            <div>
                                <input type="radio" id="qris" name="payment_method" value="qris" required>
                                <label for="qris">QRIS</label>
                            </div>  
                        <?php } elseif ($level_user == 5) { ?>
                            <!-- Opsi untuk Customer -->
                            <div>
                                <input type="radio" id="qris" name="payment_method" value="qris" required>
                                <label for="qris">QRIS</label>
                            </div>
                            <div>
                                <input type="radio" id="transfer" name="payment_method" value="transfer" required>
                                <label for="transfer">Transfer Bank</label>
                            </div>
                        <?php } ?>
                        <div class="invalid-feedback">Pilih metode pembayaran.</div>
                    </div>

                    <!-- QRIS Section -->
                    <div id="qris-section" class="d-none">

                    </div>

                    <!-- Transfer Bank Section -->
                    <div id="transfer-section" class="d-none">
   
                        <ul>
                        </ul>
                    </div>

                    <!-- Nominal Input -->
                    <div class="row">
                        <div class="col-lg-12">
                            <?php if ($level_user >= 1 && $level_user <= 4) { ?>
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="floatingInput" placeholder="Nominal Uang" name="uang" required>
                                <label for="floatingInput">Nominal Uang</label>
                                <div class="invalid-feedback">Masukkan Nominal Uang.</div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="pay-button" name="bayar_validate" value="12345">Bayar</button>
                    </div>
                    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo getenv('MIDTRANS_CLIENT_ID'); ?>"></script>
                    <script>
                        document.getElementById('pay-button').addEventListener('click', function (e) {
                            e.preventDefault();
                            
                            const form = document.getElementById('payment-form');
                            const formData = new FormData();
                            
                            // Ambil data dari form
                            const kodeOrder = form.querySelector('input[name="kode_order"]').value;
                            const total = <?php echo $total; ?>;
                            const paymentMethod = form.querySelector('input[name="payment_method"]:checked');
                            
                            if (!kodeOrder || !total || !paymentMethod) {
                                alert('Mohon lengkapi semua data pembayaran');
                                return;
                            }

                            // Tambahkan data ke FormData
                            formData.append('kode_order', kodeOrder);
                            formData.append('total', total);
                            formData.append('payment_method', paymentMethod.value);
                            
                            fetch('proses/proses_midtrans.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    snap.pay(data.token, {
                                        onSuccess: function(result) {
                                            // Update status pembayaran
                                            fetch('proses/proses_midtrans.php', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/x-www-form-urlencoded',
                                                },
                                                body: 'update_payment=true&kode_order=' + kodeOrder + '&status=success&total=' + total
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                alert('Pembayaran berhasil!');
                                                window.location.href = 'order'; // Redirect ke halaman order
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                alert('Pembayaran berhasil, tetapi gagal mengupdate status. Silakan hubungi admin.');
                                                window.location.href = 'order';
                                            });
                                        },
                                        onPending: function(result) {
                                            alert('Menunggu pembayaran');
                                            console.log(result);
                                        },
                                        onError: function(result) {
                                            alert('Pembayaran gagal');
                                            console.log(result);
                                        },
                                        onClose: function() {
                                            alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                                        }
                                    });
                                } else {
                                    alert('Terjadi kesalahan: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan sistem');
                            });
                        });
                    </script>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    // Menangani perubahan metode pembayaran
    document.querySelectorAll('input[name="payment_method"]').forEach((radio) => {
        radio.addEventListener('change', function () {
            document.getElementById('qris-section').classList.add('d-none');
            document.getElementById('transfer-section').classList.add('d-none');

            if (this.value === 'qris') {
                document.getElementById('qris-section').classList.remove('d-none');
            } else if (this.value === 'transfer') {
                document.getElementById('transfer-section').classList.remove('d-none');
            }
        });
    });
</script>
                <!-- Akhir Modal Bayar ADMIN-->

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-nowrap">
                                <th scope="col">Menu</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Status</th>
                                <th scope="col">Catatan</th>
                                <th scope="col">Total</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            foreach ($result as $row) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $row['nama_menu'] ?>
                                    </td>
                                    <td>
                                        <?php echo 'Rp. ' . number_format($row['harga'], 0, ',', '.') ?>
                                    </td>
                                    <td>
                                        <?php echo $row['jumlah'] ?>
                                    </td>
                                    <td>
                                        <?php
                                            if ($row['status'] == 1) {
                                                echo "<span class='badge text-bg-secondary'>Menunggu</span>";
                                            } elseif ($row['status'] == 2) {
                                                echo "<span class='badge text-bg-warning'>Masuk ke dapur</span>";
                                            } elseif ($row['status'] == 3) {
                                                echo "<span class='badge text-bg-primary'>Selesai</span>";
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $row['catatan'] ?>
                                    </td>
                                    <td>
                                        <?php echo 'Rp. ' . number_format($row['harganya'], 0, ',', '.') ?>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <button
                                                class="<?php echo (!empty($row['id_bayar'])) ? "btn btn-secondary btn-sm me-1 disabled" : "btn btn-warning btn-sm me-1"; ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalEdit<?php echo $row['id_list_order'] ?>"><i
                                                    class="bi bi-pencil-square"></i></button>

                                            <button
                                                class="<?php echo (!empty($row['id_bayar'])) ? "btn btn-secondary btn-sm me-1 disabled" : "btn btn-danger btn-sm me-1"; ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalDelete<?php echo $row['id_list_order'] ?>"><i
                                                    class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                $total += $row['harganya'];
                            }

                            ?>
                            <tr>
                                <td colspan="5" class="fw-bold">
                                    Total Harga
                                </td>
                                <td class="fw-bold">
                                    <?php echo 'Rp. ' . number_format($total, 0, ',', '.') ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            ?>
            <div class="p-3">
                <button
                    class="<?php echo (!empty($row['id_bayar'])) ? "btn btn-secondary disabled" : "btn btn-success"; ?>"
                    data-bs-toggle="modal" data-bs-target="#tambahItem"><i class="bi bi-plus-circle-fill"></i>
                    Item</button>
                <button
                    class="<?php echo (!empty($row['id_bayar'])) ? "btn btn-secondary disabled" : "btn btn-primary"; ?>"
                    data-bs-toggle="modal" data-bs-target="#bayar"><i class="bi bi-cash-coin"></i> Bayar</button>
                <button onclick="printStruk()" class="btn btn-info">Cetak Struk</button>
            </div>
        </div>
    </div>
</div>

<div id="strukContent" class="d-none">
    <style>
        #struk {
            font-family: "Arial", sans-serif;
            font-size: 12px;
            max-width: 300px;
            border: 1px solid #ccc;
            padding: 10px;
            width: 70mm;
        }

        #struk h2 {
            text-align: center;
            color: #333;
        }

        #struk p {
            margin: 5px 0;
        }

        #struk table {
            font-size: 12px;
            border-collapse: collapse;
            margin-top: 10px;
            width: 100%;
        }

        #struk th,
        #struk td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #struk .total {
            font-weight: bold;
        }
    </style>
    <div id="struk">
        <h2>Struk Pembayaran DeCafe</h2>
        <P>Kode Order: <?php echo $kode ?></P>
        <P>Meja: <?php echo $meja ?></P>
        <P>Pelanggan: <?php echo $pelanggan ?></P>
        <P>Waktu Order: <?php echo date('d/m/Y H:i:s', strtotime($result[0]['waktu_order'])) ?></P>

        <table>
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($result as $row) { ?>
                    <tr>
                        <td><?php echo $row['nama_menu'] ?></td>
                        <td><?php echo 'Rp. ' . number_format($row['harga'], 0, ',', '.') ?></td>
                        <td><?php echo $row['jumlah'] ?></td>
                        <td><?php echo 'Rp. ' . number_format($row['harganya'], 0, ',', '.') ?></td>
                    </tr>
                    <?php
                    $total += $row['harganya'];
                } ?>
                <tr class="total">
                    <td colspan="3">Total Harga</td>
                    <td><?php echo 'Rp. ' . number_format($total, 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function printStruk() {
        var strukContent = document.getElementById("strukContent").innerHTML;

        var printFrame = document.createElement('iframe');
        printFrame.style.display = 'none';
        document.body.appendChild(printFrame);
        printFrame.contentDocument.write(strukContent);
        printFrame.contentWindow.print();
        document.body.removeChild(printFrame);
    }
</script>