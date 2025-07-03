<?php
// Mulai session hanya jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include koneksi ke database
include "proses/connect.php";

// Periksa apakah session level user sudah diatur
$level_user = isset($_SESSION['level_decafe']) ? $_SESSION['level_decafe'] : 5; // Default level 5 jika tidak ada session

// Debugging untuk memastikan session level user
// Uncomment jika perlu debugging
// echo "Level User: " . $level_user;

// Query untuk mendapatkan data menu beserta informasi kategori
$query = mysqli_query($conn, "
    SELECT 
        tb_daftar_menu.id, 
        tb_daftar_menu.nama_menu, 
        tb_daftar_menu.foto, 
        tb_daftar_menu.keterangan,
        tb_daftar_menu.harga, 
        tb_daftar_menu.kategori, 
        tb_daftar_menu.jenis_menu, 
        tb_daftar_menu.stock, 
        tb_kategori_menu.kategori_menu, 
        tb_kategori_menu.jenis_menu AS jenis_kategori_menu, 
        tb_daftar_menu.createddate, 
        tb_daftar_menu.createdby, 
        tb_daftar_menu.lastupdatedby, 
        tb_daftar_menu.lastupdateddate  
    FROM 
        tb_daftar_menu
    LEFT JOIN 
        tb_kategori_menu 
    ON 
        tb_daftar_menu.kategori = tb_kategori_menu.id_kat_menu
");

// Masukkan hasil query ke dalam array
$result = [];
while ($record = mysqli_fetch_array($query)) {
    $result[] = $record;
}



$select_kat_menu = mysqli_query($conn, "SELECT id_kat_menu,kategori_menu FROM tb_kategori_menu");
?>
<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">   
            Halaman Menu
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col d-flex justify-content-end">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalTambahUser"> Tambah
                        Menu</button>
                </div>
            </div>
            <!-- Modal Tambah Menu Baru-->
            <div class="modal fade" id="ModalTambahUser" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Menu Makanan dan Minuman</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="proses/proses_input_menu.php"
                                method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-group mb-3">
                                            <input type="file" class="form-control py-3" id="uploadfoto"
                                                placeholder="Your Name" name="foto" required>
                                            <label class="input-group-text" for="uploadfoto">Upload Foto Menu</label>
                                            <div class="invalid-feedback">
                                                Masukkan File Foto Menu
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput"
                                                placeholder="Nama Menu" name="nama_menu" required>
                                            <label for="floatingInput">Nama Menu</label>
                                            <div class="invalid-feedback">
                                                Masukkan Nama Menu.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput"
                                                placeholder="Keterangan" name="keterangan">
                                            <label for="floatingPassword">Keterangan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" aria-label="Default select example"
                                                name="kat_menu" required>
                                                <option selected hidden value="">Pilih Kategori Menu</option>
                                                <?php
                                                foreach ($select_kat_menu as $value) {
                                                    echo "<option value=" . $value['id_kat_menu'] . ">$value[kategori_menu]</option>";
                                                }
                                                ?>
                                            </select>
                                            <label for="floatingInput">Kategori Makanan atau Minuman</label>
                                            <div class="invalid-feedback">
                                                Pilih Kategori Makanan atau Minuman.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="floatingInput"
                                                placeholder="Harga" name="harga" required>
                                            <label for="floatingInput">Harga</label>
                                            <div class="invalid-feedback">
                                                Masukkan Harga Menu
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="floatingInput"
                                                placeholder="Stock" name="stock" required>
                                            <label for="floatingInput">Stock</label>
                                            <div class="invalid-feedback">
                                                Masukkan Stock
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="input_menu_validate"
                                        value="12345">Save
                                        changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Modal Tambah Menu Baru-->

            <?php
            if (empty($result)) {
                echo "Data menu makanan atau minuman tidak ada";
            } else {
                foreach ($result as $row) {
                    ?>
                    <!-- Modal View-->
                    <div class="modal fade" id="ModalView<?php echo $row['id'] ?>" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Menu Makanan dan Minuman</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="needs-validation" novalidate action="proses/proses_input_menu.php"
                                        method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-floating mb-3">
                                                    <input disabled type="text" class="form-control" id="floatingInput"
                                                        value="<?php echo $row['nama_menu'] ?>">
                                                    <label for="floatingInput">Nama Menu</label>
                                                    <div class="invalid-feedback">
                                                        Masukkan Nama Menu.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-floating mb-3">
                                                    <input disabled type="text" class="form-control" id="floatingInput"
                                                        value="<?php echo $row['keterangan'] ?>">
                                                    <label for="floatingPassword">Keterangan</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <select disabled class="form-select" aria-label="Default select example">
                                                        <option selected hidden value="">Pilih Kategori Menu</option>
                                                        <?php
                                                        foreach ($select_kat_menu as $value) {
                                                            if ($row['kategori'] == $value['id_kat_menu']) {
                                                                echo "<option selected value=" . $value['id_kat_menu'] . ">$value[kategori_menu]</option>";
                                                            } else {
                                                                echo "<option value=" . $value['id_kat_menu'] . ">$value[kategori_menu]</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <label for="floatingInput">Kategori Makanan atau Minuman</label>
                                                    <div class="invalid-feedback">
                                                        Pilih Kategori Makanan atau Minuman.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <input disabled type="number" class="form-control" id="floatingInput"
                                                        value="<?php echo $row['harga'] ?>">
                                                    <label for="floatingInput">Harga</label>
                                                    <div class="invalid-feedback">
                                                        Masukkan Harga Menu
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <input disabled type="number" class="form-control" id="floatingInput"
                                                        value="<?php echo $row['stock'] ?>">
                                                    <label for="floatingInput">Stock</label>
                                                    <div class="invalid-feedback">
                                                        Masukkan Stock
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" name="input_menu_validate"
                                                value="12345">Save
                                                changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Akhir Modal View-->
<!-- Modal Edit-->
<div class="modal fade" id="ModalEdit<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Menu Makanan dan Minuman</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate action="proses/proses_edit_menu.php" method="POST"
                    enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group mb-3">
                                <input type="file" class="form-control py-3" id="uploadfoto" name="foto">
                                <label class="input-group-text" for="uploadfoto">Upload Foto Menu</label>
                                <small class="form-text text-muted">*Kosongkan jika tidak ingin mengganti foto</small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" placeholder="Nama Menu"
                                    name="nama_menu" required value="<?php echo $row['nama_menu'] ?>">
                                <label for="floatingInput">Nama Menu</label>
                                <div class="invalid-feedback">Masukkan Nama Menu.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" placeholder="Keterangan"
                                    name="keterangan" value="<?php echo $row['keterangan'] ?>">
                                <label for="floatingInput">Keterangan</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" aria-label="Default select example" name="kat_menu" required>
                                    <option selected hidden value="">Pilih Kategori Menu</option>
                                    <?php
                                    foreach ($select_kat_menu as $value) {
                                        if ($row['kategori'] == $value['id_kat_menu']) {
                                            echo "<option selected value=" . $value['id_kat_menu'] . ">$value[kategori_menu]</option>";
                                        } else {
                                            echo "<option value=" . $value['id_kat_menu'] . ">$value[kategori_menu]</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="floatingInput">Kategori Makanan atau Minuman</label>
                                <div class="invalid-feedback">Pilih Kategori Makanan atau Minuman.</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="floatingInput" placeholder="Harga"
                                    name="harga" required value="<?php echo $row['harga'] ?>">
                                <label for="floatingInput">Harga</label>
                                <div class="invalid-feedback">Masukkan Harga Menu</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="floatingInput" placeholder="Stock"
                                    name="stock" required value="<?php echo $row['stock'] ?>">
                                <label for="floatingInput">Stock</label>
                                <div class="invalid-feedback">Masukkan Stock</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="input_menu_validate" value="12345">Save
                            changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Modal Edit-->


                    <!-- Modal Delete-->
                    <div class="modal fade" id="ModalDelete<?php echo $row['id'] ?>" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-fullscreen-md-down">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Data User</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="needs-validation" novalidate action="proses/proses_delete_menu.php"
                                        method="POST">
                                        <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                                        <input type="hidden" value="<?php echo $row['foto'] ?>" name="foto">
                                        <div class="col-lg-12">
                                            Apakah anda ingin menghapus menu <b><?php echo $row['nama_menu'] ?></b>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger" name="input_user_validate"
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
                <th scope="col">Foto Menu</th>
                <th scope="col">Nama Menu</th>
                <th scope="col">Keterangan</th>
                <th scope="col">Jenis Menu</th>
                <th scope="col">Kategori</th>
                <th scope="col">Harga</th>
                <th scope="col">Stock</th>
                <th scope="col">Createdby</th>
                <th scope="col">Created Date</th>
                <th scope="col">Last Update By</th>
                <th scope="col">Last Update Date</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $no = 1;
            foreach ($result as $row) {
                ?>
                <tr>
                    <th scope="row">
                        <?php echo $no++ ?>
                    </th>
                    <?php if ($level_user >= 1 && $level_user <= 4) { ?>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal"
                                    data-bs-target="#ModalView<?php echo $row['id'] ?>"><i
                                        class="bi bi-eye"></i></button>
                                <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal"
                                    data-bs-target="#ModalEdit<?php echo $row['id'] ?>"><i
                                        class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal"
                                    data-bs-target="#ModalDelete<?php echo $row['id'] ?>"><i
                                        class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    <?php } ?>
                    <td>
                        <div style="width: 90px">
                            <img src="assets/img/<?php echo $row['foto']; ?>" class="img-thumbnail" alt="...">
                        </div>
                    </td>
                    <td>
                        <?php echo $row['nama_menu'] ?>
                    </td>
                    <td>
                        <?php echo $row['keterangan'] ?>
                    </td>
                    <td>
                        <?php echo ($row['jenis_menu'] == 1) ? "Makanan" : "Minuman" ?>
                    </td>
                    <td>
                        <?php echo $row['kategori_menu'] ?>
                    </td>
                    <td>
                        <?php echo $row['harga'] ?>
                    </td>
                    <td>
                        <?php echo $row['stock'] ?>
                    </td>
                    <td>
                        <?php echo $row['createdby']; ?>
                    </td>
                    <td>
                        <?php echo $row['createddate']; ?>
                    </td>
                    <td><?php echo $row['lastupdatedby']; ?>
                    </td>
                    <td><?php echo $row['lastupdateddate']; ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

                <?php
            }
            ?>
        </div>
    </div>
</div>