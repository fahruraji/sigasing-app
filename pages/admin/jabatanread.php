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
                <h1 class="m-0">Jabatan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="?page=home">Home</a>
                    </li>
                    <li class="breadcrumb-item">Jabatan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Jabatan</h3>
            <a href="?page=jabatancreate" class="btn btn-success btn-sm float-right">
                <i class="fa fa-plus-circle"></i> Tambah Data
            </a>
        </div>
        <div class="card-body">
            <table id="mytable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Jabatan</th>
                        <th>Gapok</th>
                        <th>Tunjangan</th>
                        <th>Uang Makan</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tfoot>
                    <th>No</th>
                    <th>Nama Jabatan</th>
                    <th>Gapok</th>
                    <th>Tunjangan</th>
                    <th>Uang Makan</th>
                    <th>Opsi</th>
                </tfoot>                
                <tbody>
                    <?php
                         
                        $database = new Database();
                        $db = $database->getConnection();

                        $selectSql = "SELECT * FROM jabatan";

                        $stmt = $db->prepare($selectSql);
                        $stmt->execute();

                        $no = 1;
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nama_jabatan'] ?></td>
                        <td style="text-align:right"><?= number_format($row['gapok_jabatan']) ?></td>
                        <td style="text-align:right"><?= number_format($row['tunjangan_jabatan']) ?></td>
                        <td style="text-align:right"><?= number_format($row['uang_makan_perhari']) ?></td>
                        <td>
                            <a href="?page=jabatanupdate&id=<?= $row['id'] ?>" class="btn btn-primary btn-sm mr-1">
                                <i class="fa fa-edit"></i> Ubah
                            </a>
                            <a href="?page=jabatandelete&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm mr-1" onclick="javascript: return confirm('Konfirmasi data akan dihapus?');">
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