<?php
// functions.php

// Fetch all vendors
function getAllVendors() {
    global $conn;
    $query = "SELECT * FROM vendors";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch vendor by ID
function getVendorById($id) {
    global $conn;   
    // Mempersiapkan statement
    $stmt = $conn->prepare("SELECT * FROM vendors WHERE id = ?");   
    // Mengikat parameter (id) dengan tipe "i" (integer)
    $stmt->bind_param("i", $id);
    // Menjalankan query
    $stmt->execute();
    // Mendapatkan hasil
    $result = $stmt->get_result();  
    // Mengambil hasil sebagai array asosiatif
    return $result->fetch_assoc();
}


// Add a new vendor
function addVendor($name, $contact, $item, $nomor_invoice, $quantity) {
    global $conn;
    // Mempersiapkan statement
    $stmt = $conn->prepare("INSERT INTO vendors (name, contact, item, nomor_invoice, quantity) 
                            VALUES (?, ?, ?, ?, ?)"); 
    // Mengikat parameter dengan tipe data yang sesuai
    $stmt->bind_param("ssssi", $name, $contact, $item, $nomor_invoice, $quantity);
    // Menjalankan query
    return $stmt->execute();
}

// Update vendor
function updateVendor($id, $name, $contact, $item, $nomor_invoice, $quantity) {
    global $conn;
    // Mempersiapkan statement
    $stmt = $conn->prepare("UPDATE vendors 
                            SET name = ?, contact = ?, item = ?, nomor_invoice = ?, quantity = ?
                            WHERE id = ?");
    
    // Mengikat parameter dengan tipe data yang sesuai
    $stmt->bind_param("ssssii", $name, $contact, $item, $nomor_invoice, $quantity, $id);
    // Menjalankan query
    return $stmt->execute();
}

// Delete vendor
function deleteVendor($id) {
    global $conn;
    // Nonaktifkan Foreign Key Constraint
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");
    // Hapus vendor
    $query = "DELETE FROM vendors WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    // Aktifkan kembali Foreign Key Constraint
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");
    return $stmt->execute();
}


function getAllStorageUnits() {
    global $conn;
    $query = "SELECT * FROM storage_units";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Add a new inventory item
function addInventory($item_name, $category, $quantity, $price,$storage_unit_id, $barcode, $vendor_id) {
    global $conn;
    $sql = "INSERT INTO inventory (name, category, quantity, price, storage_unit_id, barcode, vendor_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssiisis', $item_name, $category, $quantity, $price, $storage_unit_id, $barcode, $vendor_id);
    return $stmt->execute();
}

function getInvById($id) {
    global $conn;
    $query = "SELECT * FROM inventory WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_assoc();  // Mengambil satu baris hasil sebagai array asosiatif
    } else {
        return false;  // Kembalikan false jika query gagal
    }
}

function updateInv($id, $name, $category, $quantity, $price,$storage_unit_id, $barcode, $vendor_id) {
    global $conn;
    $query = "UPDATE inventory SET 
             name = ?, category = ?, quantity = ?, price = ?,storage_unit_id = ?, barcode = ?, vendor_id = ? 
             WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssiissii', $name, $category, $quantity, $price, $storage_unit_id, $barcode, $vendor_id, $id);
    return $stmt->execute();
}


function deleteInv($id) {
    global $conn;
    $query = "DELETE FROM inventory WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}


// Fetch all inventory items
function getAllInventory($search_keyword = '') {
    global $conn;

    // Buat query dasar
    $query = "SELECT inventory.*, storage_units.name AS storage_unit_name, vendors.name AS vendor_name
              FROM inventory
              JOIN storage_units ON inventory.storage_unit_id = storage_units.id
              JOIN vendors ON inventory.vendor_id = vendors.id";
    
    // Jika ada kata kunci pencarian, tambahkan filter ke query
    if (!empty($search_keyword)) {
        $search_keyword = '%' . mysqli_real_escape_string($conn, $search_keyword) . '%';
        $query .= " WHERE inventory.name LIKE ? 
                    OR inventory.category LIKE ? 
                    OR vendors.name LIKE ?
                    OR inventory.barcode LIKE ?
                    OR storage_units.name LIKE ?";
    }

    $stmt = $conn->prepare($query);
    
    // Bind parameter jika ada kata kunci pencarian
    if (!empty($search_keyword)) {
        $stmt->bind_param('sssss', $search_keyword, $search_keyword, $search_keyword, $search_keyword, $search_keyword);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

function loginAdmin($email, $password) {
    global $conn;
    // Hash password yang diinput dengan md5
    $password_hashed = md5($password);
    // Persiapkan query untuk mengambil data admin berdasarkan email
    $query = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($query);   
    // Bind parameter untuk email
    $stmt->bind_param('s', $email); 
    // Eksekusi statement
    $stmt->execute();    
    // Ambil hasilnya
    $result = $stmt->get_result();
    // Periksa apakah data admin ditemukan
    if ($result && $result->num_rows == 1) {
        $admin = $result->fetch_assoc();  
        // Verifikasi password yang diinput dengan password yang ada di database
        if ($admin['password'] === $password_hashed) {
            // Jika password cocok, set session admin
            $_SESSION['admin'] = $admin;
            $_SESSION['admin_name'] = $admin['username']; // Set session variable untuk nama admin
            return true;
        }
    }

    return false; // Return false jika email/password salah
}

function AdminLogin() {
    if (!isset($_SESSION['admin'])) {
        header('Location: login.php');
        exit();
    }
}


