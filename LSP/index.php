<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
include 'include/header.php';
?>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-3">
            <?php include 'include/sidebar.php'; ?>
        </div>
        <div class="col-9">
            <h1>Selamat Datang <?= htmlspecialchars($_SESSION['admin_name']); ?>,di Dashboard Inventory</h1>
            <p>Anda dapat memantau stok barang dan mengelola vendor di sini.</p>
            <div class="row">
                <div class="col-4">
                    <div class="card text-white bg-success">
                        <div class="card-body text-center">
                            <a href="pages/vendors.php" class="btn btn-light">ADD VENDORS</a>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card text-white bg-info">
                        <div class="card-body text-center">
                            <!-- Optional: Add more buttons or content -->
                            <a href="pages/inventory.php" class="btn btn-light">ADD Inventory</a>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card text-white bg-warning">
                        <div class="card-body text-center">
                            <!-- Optional: Add more buttons or content -->
                            <a href="pages/history.php" class="btn btn-light">history</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
