<?php
/**
 * Debug Script for Batch Approval Issue
 * Run this to check your session and role information
 */

// Load CodeIgniter
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap CodeIgniter
$app = require_once FCPATH . '../app/Config/Paths.php';
$app = new \CodeIgniter\CodeIgniter($app);
$app->initialize();

// Get session
$session = \Config\Services::session();

echo "=== BATCH APPROVAL DEBUG INFO ===\n\n";

// Check session data
echo "1. SESSION DATA:\n";
echo "   User ID: " . ($session->get('user_id') ?? 'NOT SET') . "\n";
echo "   Username: " . ($session->get('username') ?? 'NOT SET') . "\n";
echo "   Role: " . ($session->get('role') ?? 'NOT SET') . "\n";
echo "   Is Logged In: " . ($session->get('isLoggedIn') ? 'YES' : 'NO') . "\n\n";

// Check if role is admin
$role = $session->get('role');
echo "2. ROLE CHECK:\n";
echo "   Role value: '" . $role . "'\n";
echo "   Is admin? " . ($role === 'admin' ? 'YES' : 'NO') . "\n";
echo "   Role type: " . gettype($role) . "\n\n";

// Check database for user info
if ($userId = $session->get('user_id')) {
    $db = \Config\Database::connect();
    $builder = $db->table('users');
    $user = $builder->where('id', $userId)->get()->getRowArray();
    
    echo "3. DATABASE USER INFO:\n";
    if ($user) {
        echo "   ID: " . $user['id'] . "\n";
        echo "   Username: " . $user['username'] . "\n";
        echo "   Role: " . $user['role'] . "\n";
        echo "   Status: " . $user['status'] . "\n\n";
        
        // Compare session role with DB role
        if ($session->get('role') !== $user['role']) {
            echo "   ⚠️  WARNING: Session role doesn't match database role!\n";
            echo "   Session role: " . $session->get('role') . "\n";
            echo "   Database role: " . $user['role'] . "\n\n";
        }
    } else {
        echo "   ❌ User not found in database!\n\n";
    }
}

// Check pending batches
echo "4. PENDING BATCHES:\n";
$db = \Config\Database::connect();
$builder = $db->table('batches');
$builder->select('batches.*, purchase_orders.po_number, purchase_orders.approved_by as po_approved_by');
$builder->join('purchase_orders', 'purchase_orders.id = batches.purchase_order_id', 'left');
$builder->where('batches.status', 'pending');
$batches = $builder->get()->getResultArray();

if (empty($batches)) {
    echo "   No pending batches found.\n\n";
} else {
    foreach ($batches as $batch) {
        echo "   Batch #" . $batch['id'] . " - " . $batch['batch_number'] . "\n";
        echo "   PO: " . ($batch['po_number'] ?? 'N/A') . "\n";
        echo "   PO Approved By: " . ($batch['po_approved_by'] ?? 'N/A') . "\n";
        echo "   Can approve? ";
        
        // Simulate the check
        if ($role === 'admin') {
            echo "YES (Admin override)\n";
        } elseif ($batch['po_approved_by'] == $session->get('user_id')) {
            echo "YES (Same approver)\n";
        } else {
            echo "NO (Different approver)\n";
        }
        echo "\n";
    }
}

// Recommendations
echo "5. RECOMMENDATIONS:\n";
if ($role !== 'admin') {
    echo "   ⚠️  Your role is '" . $role . "', not 'admin'\n";
    echo "   → Check if your user account has the correct role in the database\n";
    echo "   → Try logging out and logging back in to refresh session\n\n";
}

if ($session->get('role') && $user && $session->get('role') !== $user['role']) {
    echo "   ⚠️  Session role mismatch detected\n";
    echo "   → Log out and log back in to sync session with database\n\n";
}

echo "=== END DEBUG INFO ===\n";
