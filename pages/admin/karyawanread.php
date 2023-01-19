<?php include_once "partials/cssdatatables.php" ?>

<div class="content-header">
    <div class="container-fluid">
        <?php
        if (isset($_SESSION["hasil"])) {
            if ($_SESSION['hasil']) {
        ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h5><i class="icon fas fa-check"></i> Berhasil</h5>
                <?= $_SESSION["pesan"] ?>
            </div>
        <?php
            } else {
        ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                <?= $_SESSION["pesan"] ?>
            </div>
        <?php
            }
            unset($_SESSION["hasil"]);
            unset($_SESSION["pesan"]);
        }
        ?>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Karyawan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="?page=home">Home</a>
                    </li>
                    <li class="breadcrumb-item">Karyawan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Karyawan</h3>
            <a href="?page=karyawancreate" class="btn btn-success btn-sm float-right">
                <i class="fa fa-plus-circle"></i> Tambah Data
            </a>
        </div>
        <div class="card-body">
            <table id="mytable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Bagian</th>
                        <th>Jabatan</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tfoot>
                    <th>NIK</th>
                    <th>Nama Karyawan</th>
                    <th>Bagian</th>
                    <th>Jabatan</th>
                    <th>Opsi</th>
                </tfoot>                
                <tbody>
                    <?php
                         
                        $database = new Database();
                        $db = $database->getConnection();

                        $selectSql = "SELECT K.*,
                        (
                        SELECT J.nama_jabatan FROM jabatan_karyawan JK
                        INNER JOIN jabatan J ON JK.jabatan_id = J.id
                        WHERE JK.karyawan_id = K.id ORDER BY JK.tanggal_mulai DESC
                        LIMIT 1
                        ) jabatan_terkini,
                        (
                        SELECT B.nama_bagian FROM bagian_karyawan BK
                        INNER JOIN bagian B ON BK.bagian_id = B.id
                        WHERE BK.karyawan_id = K.id ORDER BY BK.tanggal_mulai DESC
                        LIMIT 1
                        ) bagian_terkini
                        FROM karyawan K";

                        $stmt = $db->prepare($selectSql);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?= $row['nik'] ?></td>
                        <td><?= $row['nama_lengkap'] ?></td>
                        <td>
                            <?php
                                $bagian_terkini = $row['bagian_terkini'] == "" ? "Belum ada" : $row['bagian_terkini'];
                            ?>
                            <a href="?page=karyawanbagian&id=<?= $row['id'] ?>" class="btn bg-fuchsia btn-sm mr-1">
                                <i class="fa fa-building"></i> <?= $bagian_terkini ?>
                            </a>
                        </td>
                        <td>
                            <?php
                                $jabatan_terkini = $row['jabatan_terkini'] == "" ? "Belum ada" : $row['jabatan_terkini'];
                            ?>
                            <a href="?page=karyawanjabatan&id=<?= $row['id'] ?>" class="btn bg-blue btn-sm mr-1">
                                <i class="fa fa-building"></i> <?= $jabatan_terkini ?>
                            </a>
                        </td>
                        <td>
                            <a href="?page=karyawanupdate&id=<?= $row['id'] ?>" class="btn btn-primary btn-sm mr-1">
                                <i class="fa fa-edit"></i> Ubah
                            </a>
                            <a href="?page=karyawandelete&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm mr-1" onclick="javascript: return confirm('Konfirmasi data akan dihapus?');">
                                <i class="fa fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>                
            </table>
        </div>
    </div>
</div>

<?php include_once "partials/scripts.php" ?>
<?php include_once "partials/jsdatatables.php" ?>
<script>
    $(function() {
        $('#mytable').DataTable()
    });
</script>