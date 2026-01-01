# Dokploy Network Configuration Fix

## Problem
The application container cannot reach the database container. Error: "Host grainflow-database-7brbih is NOT reachable"

## Root Cause
The application and database containers are not in the same Docker network, so they cannot communicate.

## Solution Options

### Option 1: Ensure Same Project/Network (Recommended)

1. In Dokploy dashboard, go to your **application settings**
2. Check which **project** or **network** it's in
3. Go to your **database settings**
4. Ensure it's in the **same project/network** as the application
5. Redeploy both services

### Option 2: Use Database Internal IP

If you can't put them in the same network, use the database's internal IP address:

1. In Dokploy, go to your database service
2. Find the **Internal IP** (usually something like `172.x.x.x`)
3. Update the environment variable in your application:
   ```
   database.default.hostname=<INTERNAL_IP_ADDRESS>
   ```
4. Redeploy the application

### Option 3: Use Docker Network Name

If Dokploy creates a specific network, use it:

1. SSH into your Dokploy server
2. Run: `docker network ls`
3. Find the network both containers should be on
4. Ensure both containers are connected to that network

### Option 4: Link Services in Dokploy

Some deployment platforms have a "service linking" feature:

1. In your application settings, look for "Links" or "Dependencies"
2. Add the database service as a dependency
3. This should automatically configure networking

## Testing After Fix

After applying any of the above solutions, test the connection:

```bash
# In your application container
bash /app/test-db-connection.sh
```

You should see:
- ✓ Host is reachable
- ✓ Port 3306 is open
- ✓ MySQL connection successful

## Current Configuration

Your database service details:
- **Service Name**: grainflow-database-7brbih
- **Database**: cogsflow_db
- **Username**: cogsflow_user
- **Password**: CogsFlow2026SecurePass
- **Port**: 3306

## If Nothing Works

As a last resort, you can use an external database:
1. Create a MySQL database on a cloud provider (AWS RDS, DigitalOcean, etc.)
2. Update the hostname to the external database URL
3. Ensure the database allows connections from your Dokploy server IP
