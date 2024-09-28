<?php
session_start();

include '../include/header.php';
include '../database/functions.php';
include '../database/koneksi.php';
AdminLogin();
// Initialize variables
$item_name = '';
$vendor_id = '';
$storage_unit_id = '';  
$barcode = '';
$category = '';
$price = '';
$quantity = ''; // Add quantity variable
$stock = ''; // Add stock variable

// Handle form submission
if (isset($_POST['addInv'])) {
    $vendor_id = $_POST['vendor_id'];
    $storage_unit_id = $_POST['storage_unit_id'];
    $barcode = $_POST['barcode'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity']; // Retrieve quantity from POST data

    // Fetch vendor details
    $vendor = getVendorById($vendor_id);

    if ($vendor) {
        $item_name = $vendor['item']; // Set item_name from vendor details
        $stock = $vendor['quantity']; // Set stock from vendor details

        // Check if the quantity exceeds stock
        if ($quantity > $stock) {
            $quantity = $stock; // Adjust quantity to maximum stock
            echo "<p class='text-danger'>Quantity adjusted to maximum stock of $stock.</p>";
        }

        // Add inventory
        if (addInventory($item_name, $category, $quantity,$price, $storage_unit_id, $barcode, $vendor_id)) {
            echo "Inventory added successfully.";
        } else {
            echo "Error adding inventory: " . mysqli_error($conn);
        }
    } else {
        echo "Vendor not found.";
    }
} else {
    // Handle vendor selection via GET or POST to auto-populate item_name and quantity
    if (isset($_GET['vendor_id']) || isset($_POST['vendor_id'])) {
        $vendor_id = $_GET['vendor_id'] ?? $_POST['vendor_id'];
        $vendor = getVendorById($vendor_id);
        if ($vendor) {
            $item_name = $vendor['item']; // Set item_name from vendor details
            $stock = $vendor['quantity']; // Set stock from vendor details
            $quantity = $stock; // Set quantity to stock value
        }
    }
}

// Fetch all vendors and storage units
$vendors = getAllVendors();
$storage_units = getAllStorageUnits();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <?php include '../include/sidebar.php'; ?>
        </div>
        <div class="col-9">
            <h2>Add Inventory</h2>
            <form method="POST" action="inventory.php">
                <div class="mb-3">
                    <label for="vendor_id" class="form-label">Vendor</label>
                    <select class="form-control" id="vendor_id" name="vendor_id" required onchange="this.form.submit()">
                        <option value="">Select Vendor</option>
                        <?php foreach ($vendors as $vendor): ?>
                            <option value="<?= htmlspecialchars($vendor['id']); ?>" <?= $vendor_id == $vendor['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($vendor['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="item_name" class="form-label">Item Name</label>
                    <input type="text" class="form-control" id="item_name" name="item_name" value="<?= htmlspecialchars($item_name); ?>" required readonly>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($quantity); ?>" required>
                    <small id="stockHelp" class="form-text text-muted">Available stock: <?= htmlspecialchars($stock); ?></small>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($price); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" class="form-control" id="category" name="category" value="<?= htmlspecialchars($category); ?>" required>
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
                    <label for="barcode" class="form-label">Barcode</label>
                    <input type="text" class="form-control" id="barcode" name="barcode" value="<?= htmlspecialchars($barcode); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary" name="addInv">Add Inventory</button>
            </form>

            <h3 class="mt-5">View Inventory List</h3>
            <a href="history.php" class="btn btn-secondary">View Inventory History</a>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>
