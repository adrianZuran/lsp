<?php
session_start();
include '../include/header.php';
include '../database/koneksi.php';
include '../database/functions.php';
AdminLogin();

// Handle update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $item = $_POST['item'];
    $nomor_invoice = $_POST['nomor_invoice'];
    $quantity = $_POST['quantity'];
    updateVendor($id, $name, $contact, $item, $nomor_invoice, $quantity);
    header('Location: vendors.php');
    exit();
}

// Handle fetch for edit form
if (isset($_GET['edit'])) {
    $vendor_id = $_GET['edit'];
    $vendor = getVendorById($vendor_id);
} else {
    header('Location: vendors.php');
    exit();
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <?php include '../include/sidebar.php'; ?>
        </div>
        <div class="col-9">
        <h2>Update Vendor</h2>
            <form method="POST" action="edit.php">
                <input type="hidden" name="id" value="<?= htmlspecialchars($vendor['id']); ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Vendor Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($vendor['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="contact" class="form-label">Contact</label>
                    <input type="text" class="form-control" id="contact" name="contact" value="<?= htmlspecialchars($vendor['contact']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="item" class="form-label">Item</label>
                    <input type="text" class="form-control" id="item" name="item" value="<?= htmlspecialchars($vendor['item']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="nomor_invoice" class="form-label">Invoice Number</label>
                    <input type="text" class="form-control" id="nomor_invoice" name="nomor_invoice" value="<?= htmlspecialchars($vendor['nomor_invoice']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($vendor['quantity']); ?>" required>
                </div>
                <button type="submit" name="update" class="btn btn-primary">Update Vendor</button>
            </form>
        </div>
    </div>

</div>


<?php include '../include/footer.php'; ?>
