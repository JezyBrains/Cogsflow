<?php
// Quick fix for dispatches table - add missing columns
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "johsport_nipo";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Fixing dispatches table...</h2>";
    
    // Check current structure
    echo "<h3>Current table structure:</h3>";
    $stmt = $pdo->query("DESCRIBE dispatches");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    foreach($columns as $col) {
        echo $col['Field'] . " - " . $col['Type'] . "\n";
    }
    echo "</pre>";
    
    // Add missing columns
    $alterQueries = [
        "ALTER TABLE dispatches ADD COLUMN IF NOT EXISTS trailer_number VARCHAR(20) NULL AFTER vehicle_number",
        "ALTER TABLE dispatches ADD COLUMN IF NOT EXISTS dispatcher_name VARCHAR(255) NULL AFTER driver_phone", 
        "ALTER TABLE dispatches ADD COLUMN IF NOT EXISTS estimated_arrival DATETIME NULL AFTER dispatcher_name",
        "ALTER TABLE dispatches ADD COLUMN IF NOT EXISTS actual_departure DATETIME NULL AFTER estimated_arrival",
        "ALTER TABLE dispatches ADD COLUMN IF NOT EXISTS actual_arrival DATETIME NULL AFTER actual_departure"
    ];
    
    foreach($alterQueries as $query) {
        try {
            $pdo->exec($query);
            echo "✓ Executed: " . $query . "<br>";
        } catch(PDOException $e) {
            echo "⚠ Warning: " . $e->getMessage() . " for query: " . $query . "<br>";
        }
    }
    
    echo "<h3>Updated table structure:</h3>";
    $stmt = $pdo->query("DESCRIBE dispatches");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    foreach($columns as $col) {
        echo $col['Field'] . " - " . $col['Type'] . "\n";
    }
    echo "</pre>";
    
    echo "<h3>✅ Table fix completed!</h3>";
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
