#!/bin/bash

# Database Configuration Checker
echo "=========================================="
echo "Database Configuration Check"
echo "=========================================="
echo ""

echo "Checking .env file..."
if [ -f /app/.env ]; then
    echo "✓ .env file exists"
    echo ""
    echo "Database configuration in .env:"
    grep "database.default" /app/.env | head -6
else
    echo "✗ .env file not found!"
fi

echo ""
echo "=========================================="
echo "Testing database connection with PHP..."
echo ""

php -r "
\$config = new \Config\Database();
\$db = \Config\Database::connect();
echo 'Hostname: ' . \$db->hostname . PHP_EOL;
echo 'Database: ' . \$db->database . PHP_EOL;
echo 'Username: ' . \$db->username . PHP_EOL;
echo 'Port: ' . \$db->port . PHP_EOL;
echo '' . PHP_EOL;

try {
    \$db->query('SELECT 1');
    echo '✓ Connection successful!' . PHP_EOL;
} catch (Exception \$e) {
    echo '✗ Connection failed: ' . \$e->getMessage() . PHP_EOL;
}
"
