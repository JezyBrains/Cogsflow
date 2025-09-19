<?php
/**
 * Users Table Diagnostic
 * Check the structure and data of the users table
 */

echo "<h1>🔍 Users Table Diagnostic</h1>";

// Database connection
$hostname = 'localhost';
$database = 'johsport_nipo';
$username = 'johsport_Jezakh';
$password = 'SAMAKImkavu68@';

try {
    $mysqli = new mysqli($hostname, $username, $password, $database);
    
    if ($mysqli->connect_error) {
        echo "❌ Connection failed: " . $mysqli->connect_error;
        exit;
    }
    
    echo "✅ Connected to database<br><br>";
    
    // Check if users table exists
    $result = $mysqli->query("SHOW TABLES LIKE 'users'");
    if ($result->num_rows == 0) {
        echo "❌ <strong>users table does not exist!</strong><br>";
        echo "<p>You need to create the users table first.</p>";
        
        // Show all tables
        echo "<h2>📋 Available Tables:</h2>";
        $result = $mysqli->query("SHOW TABLES");
        while ($row = $result->fetch_array()) {
            echo "- " . $row[0] . "<br>";
        }
        
    } else {
        echo "✅ users table exists<br><br>";
        
        // Show table structure
        echo "<h2>📋 Users Table Structure:</h2>";
        $result = $mysqli->query("DESCRIBE users");
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
        
        // Check for password-related columns
        $result = $mysqli->query("DESCRIBE users");
        $passwordColumns = [];
        while ($row = $result->fetch_assoc()) {
            $field = strtolower($row['Field']);
            if (strpos($field, 'pass') !== false || strpos($field, 'pwd') !== false || strpos($field, 'hash') !== false) {
                $passwordColumns[] = $row['Field'];
            }
        }
        
        if (empty($passwordColumns)) {
            echo "❌ <strong>No password column found!</strong><br>";
            echo "<p>The users table needs a password column.</p>";
        } else {
            echo "✅ <strong>Password columns found:</strong> " . implode(', ', $passwordColumns) . "<br><br>";
        }
        
        // Show sample user data
        echo "<h2>📊 Sample User Data:</h2>";
        $result = $mysqli->query("SELECT * FROM users LIMIT 3");
        if ($result->num_rows > 0) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            
            // Header
            echo "<tr>";
            $fields = $result->fetch_fields();
            foreach ($fields as $field) {
                echo "<th>" . $field->name . "</th>";
            }
            echo "</tr>";
            
            // Data
            $result->data_seek(0);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $key => $value) {
                    if (strpos(strtolower($key), 'pass') !== false) {
                        echo "<td>" . str_repeat('*', min(strlen($value), 10)) . "</td>";
                    } else {
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "❌ <strong>No users found in table!</strong><br>";
            echo "<p>You need to create admin user.</p>";
        }
        
        // Count users
        $result = $mysqli->query("SELECT COUNT(*) as count FROM users");
        $count = $result->fetch_assoc()['count'];
        echo "<br><strong>Total users:</strong> $count<br>";
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}

echo "<hr>";
echo "<h2>🔧 Next Steps:</h2>";
echo "<ol>";
echo "<li>If users table doesn't exist: Import database_schema.sql</li>";
echo "<li>If password column is missing: Add password column</li>";
echo "<li>If no users exist: Create admin user</li>";
echo "<li>Update AuthController to use correct column name</li>";
echo "</ol>";

echo "<p><small>Generated: " . date('Y-m-d H:i:s') . "</small></p>";
?>
