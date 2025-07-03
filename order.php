<?php
include "proses/connect.php";

// Ambil level dan ID pengguna dari session
$level_user = $_SESSION['level_decafe'];
$user_id = $_SESSION['id_decafe'];

// Query berdasarkan level user
if ($level_user >= 1 && $level_user <= 4) {
    // Tampilkan semua pesanan untuk level 1-4
    $query = mysqli_query($conn, "
        SELECT tb_order.*, tb_bayar.*, tb_user.nama, 
               SUM(tb_daftar_menu.harga * tb_list_order.jumlah) AS harganya 
        FROM tb_order
        LEFT JOIN tb_user ON tb_user.id = tb_order.pelayan
        LEFT JOIN tb_list_order ON tb_list_order.kode_order = tb_order.id_order
        LEFT JOIN tb_daftar_menu ON tb_daftar_menu.id = tb_list_order.menu
        LEFT JOIN tb_bayar ON tb_bayar.id_bayar = tb_order.id_order
        GROUP BY tb_order.id_order 
        ORDER BY tb_order.waktu_order DESC
    ");
} else if ($level_user == 5) {
    // Tampilkan hanya pesanan milik user untuk level 5
    $query = mysqli_query($conn, "
        SELECT tb_order.*, tb_bayar.*, tb_user.nama, 
               SUM(tb_daftar_menu.harga * tb_list_order.jumlah) AS harganya 
        FROM tb_order
        LEFT JOIN tb_user ON tb_user.id = tb_order.pelayan
        LEFT JOIN tb_list_order ON tb_list_order.kode_order = tb_order.id_order
        LEFT JOIN tb_daftar_menu ON tb_daftar_menu.id = tb_list_order.menu
        LEFT JOIN tb_bayar ON tb_bayar.id_bayar = tb_order.id_order
        WHERE tb_order.id_user = '$user_id' 
        GROUP BY tb_order.id_order 
        ORDER BY tb_order.waktu_order DESC
    ");
}

// Ambil hasil query
$result = [];
while ($row = mysqli_fetch_array($query)) {
    $result[] = $row;
}

// $select_kat_menu = mysqli_query($conn, "SELECT id_kat_menu,kategori_menu FROM tb_kategori_menu");
?>
<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            Halaman Order
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col d-flex justify-content-end">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalTambahUser"> Tambah
                        Order</button>
                </div>
            </div>
            <!-- Modal Tambah Order Baru-->
            <div class="modal fade" id="ModalTambahUser" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Order Makanan dan Minuman</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="proses/proses_input_order.php"
                                method="POST">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="uploadfoto" name="kode_order"
                                                value="<?php echo date('ymdHi') . rand(100, 999) ?>" readonly>
                                            <label for="uploadfoto">Kode Order</label>
                                            <div class="invalid-feedback">
                                                Masukkan Kode Order
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="meja" placeholder="Nomor Meja"
                                                name="meja" min="1" max="80" required>
                                            <label for="meja">Meja</label>
                                            <div class="invalid-feedback">
                                                Masukkan nomor meja antara 1 dan 80.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-7">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="pelanggan"
                                                placeholder="Nama Pelanggan" name="pelanggan" required>
                                            <label for="pelanggan">Nama Pelanggan</label>
                                            <div class="invalid-feedback">
                                                Masukkan Nama Pelanggan.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="input_order_validate"
                                        value="12345">Buat Order</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Modal Tambah Order Baru-->

            <?php
            if (empty($result)) {
                echo "Data menu makanan atau minuman tidak ada";
            } else {
                foreach ($result as $row) {
                    ?>

                    <!-- Modal Edit-->
                    <div class="modal fade" id="ModalEdit<?php echo $row['id_order'] ?>" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Menu Makanan dan Minuman</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="needs-validation" novalidate action="proses/proses_edit_order.php"
                                        method="POST">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-floating mb-3">
                                                    <input readonly type="text" class="form-control" id="uploadfoto"
                                                        name="kode_order" value="<?php echo $row['id_order'] ?>">
                                                    <label for="uploadfoto">Kode Order</label>
                                                    <div class="invalid-feedback">
                                                        Masukkan Kode Order
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-floating mb-3">
                                                    <input type="number" class="form-control" id="meja" placeholder="Nomor Meja"
                                                        name="meja" required value="<?php echo $row['meja'] ?>">
                                                    <label for="meja">Meja</label>
                                                    <div class="invalid-feedback">
                                                        Masukkan Nomor Meja.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="pelanggan"
                                                        placeholder="Nama Pelanggan" name="pelanggan" required
                                                        value="<?php echo $row['pelanggan'] ?>">
                                                    <label for="pelanggan">Nama Pelanggan</label>
                                                    <div class="invalid-feedback">
                                                        Masukkan Nama Pelanggan.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" name="edit_order_validate"
                                                value="12345">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Akhir Modal Edit-->

                    <!-- Modal Delete-->
                    <div class="modal fade" id="ModalDelete<?php echo $row['id_order'] ?>" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-fullscreen-md-down">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Data User</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="needs-validation" novalidate action="proses/proses_delete_order.php"
                                        method="POST">
                                        <input type="hidden" value="<?php echo $row['id_order'] ?>" name="kode_order">
                                        <div class="col-lg-12">
                                            Apakah anda ingin menghapus order atas nama <b><?php echo $row['pelanggan'] ?></b>
                                            dengan kode order <b><?php echo $row['id_order'] ?></b>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger" name="delete_order_validate"
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
    
<div class="table-responsive">
    <table class="table table-hover" id="example">
        <thead>
            <tr class="text-nowrap">
                <th scope="col">No</th>
                <?php if ($level_user >= 1 && $level_user <= 4) { ?>
                    <th scope="col">Aksi</th>
                <?php } ?>
                <th scope="col">Kode Order</th>
                <th scope="col">Pelanggan</th>
                <th scope="col">Meja</th>
                <th scope="col">Total Harga</th>
                <?php if ($level_user >= 1 && $level_user <= 4) { ?>
                <th scope="col">Kasir</th>
                <?php } ?>
                <th scope="col">Status</th>
                <th scope="col">Waktu Order</th>
                <th scope="col">Bayar</th>
                <th scope="col">Status pesanan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($result as $row) {
            ?>
                <tr>
                    <td>
                        <?= $no++; ?>
                    </td>
                    <?php if ($level_user >= 1 && $level_user <= 4) { ?>
                        <td>
                            <div class="d-flex">
                                <a class="btn btn-info btn-sm me-1"
                                    href="./?x=orderitem&order=<?= $row['id_order'] . "&meja=" . $row['meja'] . "&pelanggan=" . $row['pelanggan']; ?>">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button
                                    class="<?= !empty($row['id_bayar']) ? "btn btn-secondary btn-sm me-1 disabled" : "btn btn-warning btn-sm me-1"; ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalEdit<?= $row['id_order']; ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button
                                    class="<?= !empty($row['id_bayar']) ? "btn btn-secondary btn-sm me-1 disabled" : "btn btn-danger btn-sm me-1"; ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalDelete<?= $row['id_order']; ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    <?php } ?>
                    <td>
                        <?= $row['id_order']; ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($row['pelanggan'] ?? 'Tidak Ada'); ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($row['meja'] ?? 'Tidak Ada'); ?>
                    </td>
                    <td>
                        <?= 'Rp. ' . number_format((int)($row['harganya'] ?? 0), 0, ',', '.'); ?>
                    </td>
                    <?php if ($level_user >= 1 && $level_user <= 4) { ?>
                    <td>
                        <?= htmlspecialchars($row['nama'] ?? 'Tidak Ada'); ?>
                    </td>
                    <?php } ?>
                    <td>
                        <?php 
                        if (!empty($row['id_bayar'])) {
                            echo "<span class='badge text-bg-success'>Dibayar</span>";
                        } else {
                            echo "<span class='badge text-bg-danger'>Belum Dibayar</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <?= !empty($row['waktu_order']) ? date('d/m/Y H:i:s', strtotime($row['waktu_order'])) : 'Tidak Ada'; ?>
                    </td>
                    <td>
                        <?php if (empty($row['id_bayar'])) { ?>
                            <a href="./?x=orderitem&order=<?= $row['id_order'] . "&meja=" . $row['meja'] . "&pelanggan=" . $row['pelanggan']; ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-wallet2"></i> Bayar
                            </a>
                        <?php } else { ?>
                            <button class="btn btn-secondary btn-sm disabled">
                                <i class="bi bi-check-circle"></i> Sudah Dibayar
                            </button>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="./?x=orderitem&order=<?= $row['id_order'] . "&meja=" . $row['meja'] . "&pelanggan=" . $row['pelanggan']; ?>" 
                           class="btn btn-success btn-sm">
                            <i class="bi bi-list"></i> Status pesanan
                        </a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>




    </table>
</div>

                <?php
            }
            ?>
        </div>
    </div>
</div>