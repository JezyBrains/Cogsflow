#!/bin/bash

echo "=========================================="
echo "Database Connection Test"
echo "=========================================="
echo ""

# Test 1: Check if database host is reachable
echo "Test 1: Checking if database host is reachable..."
if ping -c 1 grainflow-database-7brbih > /dev/null 2>&1; then
    echo "✓ Host grainflow-database-7brbih is reachable"
else
    echo "✗ Host grainflow-database-7brbih is NOT reachable"
    echo "  Trying to resolve hostname..."
    nslookup grainflow-database-7brbih 2>&1 || echo "  DNS lookup failed"
fi

echo ""

# Test 2: Check if MySQL port is open
echo "Test 2: Checking if MySQL port 3306 is open..."
if command -v nc > /dev/null; then
    if nc -zv grainflow-database-7brbih 3306 2>&1 | grep -q succeeded; then
        echo "✓ Port 3306 is open"
    else
        echo "✗ Port 3306 is NOT accessible"
    fi
else
    echo "  nc command not available, skipping port check"
fi

echo ""

# Test 3: Try direct MySQL connection
echo "Test 3: Testing MySQL connection..."
if command -v mysql > /dev/null; then
    mysql -h grainflow-database-7brbih -P 3306 -u cogsflow_user -pCogsFlow2026SecurePass -D cogsflow_db -e "SELECT 1;" 2>&1
    if [ $? -eq 0 ]; then
        echo "✓ MySQL connection successful!"
    else
        echo "✗ MySQL connection failed"
    fi
else
    echo "  mysql command not available"
fi

echo ""
echo "=========================================="
