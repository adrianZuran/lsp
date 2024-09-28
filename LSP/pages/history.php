<?php
session_start();

include '../include/header.php';
include '../database/functions.php';
include '../database/koneksi.php';
AdminLogin();
// Ambil kata kunci pencarian (jika ada)
$search_keyword = isset($_GET['search']) ? $_GET['search'] : '';

// Ambil data inventaris, filter berdasarkan kata kunci pencarian (jika ada)
$inventory_data = getAllInventory($search_keyword);

if(isset($_GET['delete'])) {
    $InvId = $_GET['delete'];
    deleteInv($InvId);
    header('location: history.php');
    exit();
}



?>

<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <?php include '../include/sidebar.php'; ?>
        </div>
        <div class="col-9">
            <h3 class="mt-5">Inventory List</h3>
            
            <!-- Form pencarian -->
            <form method="GET" action="" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by item name, category, or vendor" value="<?= htmlspecialchars($search_keyword); ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Barcode</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Storage Unit</th>
                        <th>Vendor</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($inventory_data)): ?>
                        <?php foreach ($inventory_data as $Inv): ?>
                            <tr class="<?= ($Inv['quantity'] == 0) ? 'bg-danger' : ''; ?>">
                                <td><?= htmlspecialchars($Inv['name']); ?></td>
                                <td><?= htmlspecialchars($Inv['category']); ?></td>
                                <td><?= htmlspecialchars($Inv['quantity']); ?></td>
                                <td>Rp. <?= htmlspecialchars($Inv['price']); ?></td>
                                <td><?= htmlspecialchars($Inv['barcode']); ?></td>
                                <td><?= htmlspecialchars($Inv['storage_unit_name']); ?></td>
                                <td><?= htmlspecialchars($Inv['vendor_name']); ?></td>
                                <td>
                                <a href="editInv.php?edit=<?= $Inv['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="history.php?delete=<?= $Inv['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No data available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>
