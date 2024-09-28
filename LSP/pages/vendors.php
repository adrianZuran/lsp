<?php
session_start();

include '../include/header.php';
include '../database/koneksi.php';
include '../database/functions.php';
AdminLogin();
// Menangani form penambahan vendor
if (isset($_POST['addVen'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $item = $_POST['item'];
    $nomor_invoice = $_POST['nomor_invoice'];
    $quantity = $_POST['quantity'];
    addVendor($name, $contact, $item, $nomor_invoice, $quantity);   
}

if (isset($_GET['delete'])) {
    $vendor_id = $_GET['delete'];
    deleteVendor($vendor_id);
    header('Location: vendors.php');
    exit();
}

$vendors = getAllVendors();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <?php include '../include/sidebar.php'; ?>
        </div>
        <div class="col-9">
        <h2>Vendors</h2>
    <form method="POST" action="vendors.php">
        <div class="mb-3">
            <label for="name" class="form-label">Vendor Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="contact" class="form-label">Contact</label>
            <input type="text" class="form-control" id="contact" name="contact" required>
        </div>
        <div class="mb-3">
            <label for="item" class="form-label">Item</label>
            <input type="text" class="form-control" id="item" name="item" required>
        </div>
        <div class="mb-3">
            <label for="nomor_invoice" class="form-label">Invoice Number</label>
            <input type="text" class="form-control" id="nomor_invoice" name="nomor_invoice" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <button type="submit" name="addVen" class="btn btn-primary">Add Vendor</button>
    </form>

    <h3 class="mt-5">Vendor List</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact</th>
                <th>Item</th>
                <th>Invoice Number</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vendors as $vendor): ?>
                <tr>
                    <td><?= htmlspecialchars($vendor['name']); ?></td>
                    <td><?= htmlspecialchars($vendor['contact']); ?></td>
                    <td><?= htmlspecialchars($vendor['item']); ?></td>
                    <td><?= htmlspecialchars($vendor['nomor_invoice']); ?></td>
                    <td><?= htmlspecialchars($vendor['quantity']); ?></td>
                    <td>
                        <a href="edit_vendors.php?edit=<?= $vendor['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="vendors.php?delete=<?= $vendor['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>
