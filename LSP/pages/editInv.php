<?php 
session_start();
include '../include/header.php';
include '../database/koneksi.php';
include '../database/functions.php';
AdminLogin();

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $storage_unit_id = $_POST['storage_unit_id'];
    $barcode = $_POST['barcode'];
    $vendor_id = $_POST['vendor_id'];
    updateInv($id,$name,$category,$quantity,$price,$storage_unit_id,$barcode,$vendor_id);
    header('location: history.php');
    exit();
}

// Ambil data inventaris jika `edit` parameter ada
if (isset($_GET['edit'])) {
    $InvId = $_GET['edit'];
    $Inv = getInvById($InvId);
    
    // Definisikan variabel dari hasil query
    $storage_unit_id = $Inv['storage_unit_id'];
    $vendor_id = $Inv['vendor_id'];
} else {
    header('location: history.php');
    exit();
}


$storage_units = getAllStorageUnits();
$vendors = getAllVendors();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <?php include '../include/sidebar.php' ?>
        </div>
        <div class="col-9">
            <h2>Update Inventory</h2>
            <form method="post" action="editInv.php">
                <input type="hidden" name="id" value="<?= htmlspecialchars($Inv['id']); ?>">
                <input type="hidden" name="storage_unit_id" value="<?= htmlspecialchars($Inv['storage_unit_id']); ?>">
                <input type="hidden" name="vendor_id" value="<?= htmlspecialchars($Inv['vendor_id']); ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">Item Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($Inv['name']); ?>" required readonly>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" class="form-control" id="category" name="category" value="<?= htmlspecialchars($Inv['category']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($Inv['quantity']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($Inv['price']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="barcode" class="form-label">Barcode</label>
                    <input type="text" class="form-control" id="barcode" name="barcode" value="<?= htmlspecialchars($Inv['barcode']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="storage_unit_id" class="form-label">Storage Unit</label>
                    <select class="form-control" id="storage_unit_id" name="storage_unit_id" required>
                        <option value="">Select Storage Unit</option>
                        <?php foreach ($storage_units as $unit): ?>
                            <option value="<?= htmlspecialchars($unit['id']); ?>" <?= $storage_unit_id == $unit['id'] ? 'selected' : '' ?>><?= htmlspecialchars($unit['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="vendor_id" class="form-label">Vendor</label>
                    <select class="form-control" id="vendor_id" name="vendor_id" required readonly >
                        <option value="">Select Vendor</option>
                        <?php foreach ($vendors as $vendor): ?>
                            <option value="<?= htmlspecialchars($vendor['id']); ?>" <?= $vendor_id == $vendor['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($vendor['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="update" class="btn btn-primary">Update Inventory</button>
            </form>
        </div>
    </div>
</div>
