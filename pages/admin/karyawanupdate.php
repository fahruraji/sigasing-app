<!-- Karyawan Update -->

<?php
if (isset($_GET['id'])) {
   
    $database = new Database();
    $db = $database->getConnection();

    $id = $_GET['id'];
    $findSql = "SELECT * FROM karyawan K
    INNER JOIN pengguna P ON K.pengguna_id = P.id 
    WHERE P.id = ?";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(1, $_GET['id']);
    $stmt->execute();
    $row = $stmt->fetch();

    if (isset($row['id'])) {
        if (isset($_POST['button_update'])) {
        
            $database = new Database();
            $db = $database->getConnection();
        
            $validateSql = "SELECT * FROM karyawan WHERE nik = ? AND id != ?";
            $stmt = $db->prepare($validateSql);
            $stmt->bindParam(1, $_POST['nik']);
            $stmt->bindParam(2, $_POST['id']);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
            ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                    NIK sama sama sudah ada
                </div>
            <?php
            } else {
                $validateSQL = "SELECT * FROM pengguna WHERE username = ? AND id != ?";
                $stmt = $db->prepare($validateSQL);
                $stmt->bindParam(1, $_POST['username']);
                $stmt->bindParam(2, $_POST['id']);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                        <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                        Username sama sudah ada
                    </div>
                <?php
                } else {
                    if ($_POST['password'] != $_POST['password_ulangi']) {
                    ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                            Password tidak sama
                        </div>
                    <?php 
                    } else {
                        $md5password = md5($_POST['password']);
        
                        $updateSQL = "UPDATE pengguna SET username = ?, password = ?, peran = ?";
                        $stmt = $db->prepare($updateSQL);
                        $stmt->bindParam(1, $_POST['username']);
                        $stmt->bindParam(2, $md5password);
                        $stmt->bindParam(3, $_POST['peran']);
                        
                        if ($stmt->execute()) {
        
                            // $pengguna_id = $db->lastInsertId();
        
                            $updateKaryawanSQL = "UPDATE karyawan SET nik = ?, nama_lengkap = ?, handphone = ?, email = ?, tanggal_masuk = ?";
                            $stmtKaryawan = $db->prepare($updateKaryawanSQL);
                            $stmtKaryawan->bindParam(1, $_POST['nik']);
                            $stmtKaryawan->bindParam(2, $_POST['nama_lengkap']);
                            $stmtKaryawan->bindParam(3, $_POST['handphone']);
                            $stmtKaryawan->bindParam(4, $_POST['email']);
                            $stmtKaryawan->bindParam(5, $_POST['tanggal_masuk']);
        
                            if ($stmtKaryawan->execute()) {
                                $_SESSION['hasil'] = true;
                                $_SESSION['pesan'] = "Berhasil ubah data";
                            } else {
                                $_SESSION['hasil'] = false;
                                $_SESSION['pesan'] = "Gagal ubah data";
                            }                
                        } else {
                            $_SESSION['hasil'] = false;
                            $_SESSION['pesan'] = "Gagal ubah data";
                        }
                        echo "<meta http-equiv='refresh' content='0;url=?page=karyawanread'>";
                    }
                }
            }
        }
    
?>
<section class="content-header">
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
                <h1>Ubah Data Karyawan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
                    <li class="breadcrumb-item"><a href="?page=karyawanread">Karyawan</a></li>
                    <li class="breadcrumb-item active">Ubah Data</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Ubah Karyawan</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <div class="form-group">
                    <label for="nik">Nomor Induk Karyawan</label>
                    <input type="text" class="form-control" name="nik" value="<?= $row['nik'] ?>">
                </div>
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" value="<?= $row['nama_lengkap'] ?>">
                </div>
                <div class="form-group">
                    <label for="handphone">Handphone</label>
                    <input type="text" class="form-control" name="handphone" value="<?= $row['handphone'] ?>">
                </div><div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" value="<?= $row['email'] ?>">
                </div>
                <div class="form-group">
                    <label for="tanggal_masuk">Tanggal Masuk</label>
                    <input type="date" class="form-control" name="tanggal_masuk" value="<?= $row['tanggal_masuk'] ?>">
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" value="<?= $row['username'] ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" value="<?= $row['password'] ?>">
                </div>
                <div class="form-group">
                    <label for="password_ulangi">Password (Ulangi)</label>
                    <input type="password" class="form-control" name="password_ulangi" value="<?= $row['password'] ?>">
                </div>
                <div class="form-group">
                    <label for="peran">Peran</label>
                    <select class="form-control" name="peran">
                        <option value="">-- Pilih Peran --</option>
                        <?php ?>
                        <option value="ADMIN" <?php echo $row['peran'] == 'admin' ? 'selected' : '' ?>>ADMIN</option>
                        <option value="USER" <?php echo $row['peran'] == 'user' ? 'selected' : '' ?>>USER</option>
                    </select>
                </div>
                <a href="?page=karyawanread" class="btn btn-danger btn-sm float-right">
                    <i class="fa fa-times"></i> Batal
                </a>
                <button type="submit" name="button_update" class="btn btn-success btn-sm float-right">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </form>
        </div>
    </div>
</section>

<?php
    } else {
        echo "<meta http-equiv='refresh' content='0;url=?page=karyawanread'>";
    }
} else {
    echo "<meta http-equiv='refresh' content='0;url=?page=karyawanread'>";
}
?>