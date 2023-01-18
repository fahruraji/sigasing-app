<?php include_once "partials/cssdatatables.php" ?>

<div class="content-header">
    <div class="container-fluid">
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
                        <td><?= $row['bagian_terkini'] ?></td>
                        <td><?= $row['jabatan_terkini'] ?></td>
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