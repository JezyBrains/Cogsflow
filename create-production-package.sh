#!/bin/bash

# CogsFlow Production Package Creator
echo "🚀 Creating CogsFlow Production Package..."

# Get current date for filename
DATE=$(date +"%Y%m%d-%H%M%S")
PACKAGE_NAME="cogsflow-production-complete-$DATE"

# Create temporary directory
mkdir -p "/tmp/$PACKAGE_NAME"

echo "📁 Copying application files..."

# Copy all necessary files
cp -r app "/tmp/$PACKAGE_NAME/"
cp -r public "/tmp/$PACKAGE_NAME/"
cp -r vendor "/tmp/$PACKAGE_NAME/"
cp -r writable "/tmp/$PACKAGE_NAME/"

# Copy configuration files
cp .env.example "/tmp/$PACKAGE_NAME/"
cp .env.production "/tmp/$PACKAGE_NAME/"
cp composer.json "/tmp/$PACKAGE_NAME/"
cp composer.lock "/tmp/$PACKAGE_NAME/"

# Copy documentation
cp *.md "/tmp/$PACKAGE_NAME/" 2>/dev/null || true

# Copy database files
cp -r app/Database "/tmp/$PACKAGE_NAME/app/" 2>/dev/null || true
cp database_schema.sql "/tmp/$PACKAGE_NAME/" 2>/dev/null || true

echo "🔧 Setting up production environment..."

# Create production .env file
cat > "/tmp/$PACKAGE_NAME/.env" << 'EOF'
#--------------------------------------------------------------------
# PRODUCTION ENVIRONMENT VARIABLES
#--------------------------------------------------------------------

CI_ENVIRONMENT = production

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------

app.baseURL = 'http://localhost:8000/'
app.forceGlobalSecureRequests = true
app.CSPEnabled = false

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------

database.default.hostname = localhost
database.default.database = johsport_cogsflow
database.default.username = johsport_cogsflow
database.default.password = your_database_password_here
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306

#--------------------------------------------------------------------
# SECURITY
#--------------------------------------------------------------------

security.csrfProtection  = 'cookie'
security.tokenRandomize  = false
security.tokenName       = 'csrf_token_name'
security.headerName      = 'X-CSRF-TOKEN'
security.cookieName      = 'csrf_cookie_name'
security.expires         = 7200
security.regenerate      = true
security.redirect        = true
security.samesite        = 'Lax'

#--------------------------------------------------------------------
# SESSION
#--------------------------------------------------------------------

session.driver = 'CodeIgniter\Session\Handlers\FileHandler'
session.cookieName = 'ci_session'
session.expiration = 7200
session.savePath = null
session.matchIP = false
session.timeToUpdate = 300
session.regenerateDestroy = false

#--------------------------------------------------------------------
# LOGGER
#--------------------------------------------------------------------

logger.threshold = 4
EOF

echo "📦 Creating deployment package..."

# Create the zip file
cd /tmp
zip -r "$PACKAGE_NAME.zip" "$PACKAGE_NAME" -x "*/node_modules/*" "*/tests/*" "*/.git/*" "*/.*"

# Move to original directory
mv "$PACKAGE_NAME.zip" "/Applications/XAMPP/xamppfiles/htdocs/cogsflow/"

# Clean up
rm -rf "/tmp/$PACKAGE_NAME"

echo "✅ Production package created: $PACKAGE_NAME.zip"
echo ""
echo "📋 DEPLOYMENT INSTRUCTIONS:"
echo "1. Upload $PACKAGE_NAME.zip to your server"
echo "2. Extract to your web root directory"
echo "3. Update .env file with your database credentials"
echo "4. Import database_schema.sql to your MySQL database"
echo "5. Set file permissions: chmod 755 writable/ -R"
echo "6. Test your application"
echo ""
echo "🔐 Default Admin Login:"
echo "Username: admin"
echo "Password: NipoAgro2025!"
echo ""
echo "🚀 Your application should now work perfectly!"
