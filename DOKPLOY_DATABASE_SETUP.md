# Dokploy Database Configuration Guide

## Problem
The application is trying to connect to hostname `db` which doesn't exist in Dokploy's environment. You need to configure the database connection properly.

## Solution Options

### Option 1: Use Dokploy's Built-in MySQL/MariaDB Service (Recommended)

1. **Create a MySQL Database in Dokploy:**
   - Go to your Dokploy dashboard
   - Navigate to "Databases" or "Services"
   - Create a new MySQL/MariaDB database
   - Note down the connection details provided by Dokploy

2. **Get Database Connection Details:**
   Dokploy will provide you with:
   - Hostname (usually something like `mysql-xxxxx` or an internal IP)
   - Port (usually `3306`)
   - Database name
   - Username
   - Password

3. **Configure Environment Variables in Dokploy:**
   In your application's environment variables section, add:
   ```
   database.default.hostname=<DOKPLOY_MYSQL_HOSTNAME>
   database.default.database=cogsflow_db
   database.default.username=cogsflow_user
   database.default.password=CogsFlow2026SecurePass
   database.default.DBDriver=MySQLi
   database.default.port=3306
   ```

### Option 2: Use External Database

If you have an external MySQL database:

1. **Add these environment variables in Dokploy:**
   ```
   database.default.hostname=<YOUR_DB_HOST>
   database.default.database=cogsflow_db
   database.default.username=cogsflow_user
   database.default.password=CogsFlow2026SecurePass
   database.default.DBDriver=MySQLi
   database.default.port=3306
   ```

## How to Add Environment Variables in Dokploy

1. Go to your application in Dokploy
2. Navigate to "Settings" or "Environment Variables"
3. Add each variable as a key-value pair:
   - Key: `database.default.hostname`
   - Value: `<your-database-hostname>`
4. Repeat for all database configuration variables
5. Save and redeploy

## Important Notes

- **DO NOT use `db` as hostname** - this only works in docker-compose
- **Get the actual hostname** from Dokploy's database service details
- The hostname might be:
  - An internal service name (e.g., `mysql-service-xxxxx`)
  - An IP address (e.g., `10.0.1.5`)
  - A container name from Dokploy's network
- Make sure the database service is running before deploying the application

## Testing Database Connection

After configuring, you can check the logs to see if the connection is successful. Look for:
- ✓ Database connection established
- Database initialization messages

If you still see connection errors, verify:
1. Database service is running
2. Hostname is correct
3. Port is correct (default: 3306)
4. Username and password are correct
5. Database name exists

## Current Environment Variables You Have

```
DB_DATABASE=cogsflow_db
DB_USERNAME=cogsflow_user
DB_PASSWORD=CogsFlow2026SecurePass
DB_ROOT_PASSWORD=RootPass2026VerySecure
APP_URL=https://nipoagro.com
ENCRYPTION_KEY=a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6
```

You need to add the **database hostname** that Dokploy provides for its MySQL service.
