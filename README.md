# Grain Management System (CogsFlow)

A comprehensive grain management system built with CodeIgniter 4 and modern UI components. This system provides complete grain processing workflow management from batch creation to dispatch and financial reporting.

## Features

### Phase 1: Modern UI Foundation (COMPLETED)
- **Sneat Admin Template Integration**: Professional, responsive admin interface
- **Modern Dashboard**: Statistics cards, quick actions, and system overview
- **Responsive Navigation**: Mobile-first sidebar and top navbar
- **Bootstrap 5.3.2**: Latest Bootstrap components and utilities
- **Boxicons Integration**: Comprehensive icon library
- **Vanilla JavaScript**: Modern JS replacing legacy jQuery
- **Custom CSS Framework**: Consistent design system with CSS variables
- **Professional Color Scheme**: Modern, accessible color palette
- **Smooth Animations**: Enhanced user experience with transitions

### Phase 2: Core Page & Route Structure (COMPLETED)
- **Complete MVC Architecture**: Proper separation of concerns
- **RESTful Routing**: Clean, organized URL structure
- **Module Controllers**: Dedicated controllers for each business module
- **Comprehensive Views**: All pages with consistent layout and styling
- **Working Navigation**: Functional sidebar with active state management
- **Error Handling**: Custom 404 pages and proper error management
- **Reports Module**: Complete analytics and reporting system

### Phase 5: Settings Panel & Admin Utilities (COMPLETED)
- **Comprehensive Settings Management**: Database-driven configuration system
- **Admin Utilities Panel**: Cache management, database optimization, backup tools
- **System Health Monitoring**: Real-time health status and diagnostics
- **Settings Categories**: Company, System, Business, Security configurations
- **Import/Export Functionality**: JSON-based settings backup and restore
- **System Logs Viewer**: Paginated log viewing with filtering capabilities
- **Role-Based Access Control**: Admin-only access protection
- **Modern Tabbed Interface**: Clean, responsive settings dashboard

## 📋 System Modules

### Core Business Modules
- **Dashboard**: System overview with key metrics and quick actions
- **Batch Management**: Create, track, and manage grain batches
- **Inventory Management**: Stock tracking, adjustments, and valuation
- **Dispatch Management**: Shipping coordination and delivery tracking
- **Purchase Orders**: Supplier management and procurement workflow
- **Expense Tracking**: Cost management and financial monitoring
- **Reports & Analytics**: Comprehensive reporting with export capabilities
- **Settings**: System configuration and preferences

### 🔄 Planned Features (Phase 3+)
- **Database Integration**: MySQL backend with full CRUD operations
- **User Authentication**: Role-based access control and permissions
- **Advanced Analytics**: Interactive charts and data visualization
- **Export Functionality**: PDF, Excel, and CSV export capabilities
- **Email Notifications**: Automated alerts and notifications
- **API Endpoints**: RESTful API for third-party integrations
- **Mobile Optimization**: Enhanced mobile experience
- **Audit Trail**: Complete activity logging and tracking

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- Web server (Apache/Nginx)

### Setup Instructions

1. **Clone or download the project**
   ```bash
   cd /path/to/your/webroot
   # Project is already set up in cogsflow directory
   ```

2. **Install dependencies**
   ```bash
   cd cogsflow
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   ```
   Edit the `.env` file with your database credentials:
   ```
   database.default.hostname = localhost
   database.default.database = grain_management
   database.default.username = your_username
   database.default.password = your_password
   ```

4. **Set up the database**
   - Create a MySQL database named `grain_management`
   - Database schema will be implemented in Phase 2

5. **Configure web server**
   - Point your web server to the `public` folder
   - For XAMPP: Access via `http://localhost/cogsflow/public/`

## Project Structure

```
cogsflow/
├── app/
│   ├── Controllers/           # Application controllers
│   │   ├── DashboardController.php
│   │   ├── BatchController.php
│   │   ├── DispatchController.php
│   │   ├── PurchaseOrderController.php
│   │   ├── InventoryController.php
│   │   ├── ExpenseController.php
│   │   └── SettingsController.php
│   ├── Config/
│   │   └── Routes.php         # Route definitions
│   └── Views/                 # View templates
│       ├── layouts/
│       │   └── main.php       # Main layout template
│       ├── dashboard/
│       ├── batches/
│       ├── dispatches/
│       ├── purchase_orders/
│       ├── inventory/
│       ├── expenses/
│       ├── settings/
│       └── errors/
├── public/                    # Web accessible files
│   └── index.php             # Application entry point
└── .env.example              # Environment configuration template
```

## Route Structure

| Route | Controller | Method | Description |
|-------|------------|--------|--------------|
| `/` | DashboardController | index | Dashboard homepage |
| `/dashboard` | DashboardController | index | Dashboard |
| `/batches` | BatchController | index | List all batches |
| `/batches/new` | BatchController | new | Create batch form |
| `/batches/create` | BatchController | create | Process batch creation |
| `/dispatches` | DispatchController | index | List all dispatches |
| `/dispatches/new` | DispatchController | new | Create dispatch form |
| `/dispatches/create` | DispatchController | create | Process dispatch creation |
| `/dispatches/receive/{id}` | DispatchController | showReceiveForm | Receive dispatch form |
| `/dispatches/receive` | DispatchController | receive | Process dispatch receiving |
| `/purchase-orders` | PurchaseOrderController | index | List purchase orders |
| `/purchase-orders/new` | PurchaseOrderController | new | Create PO form |
| `/purchase-orders/create` | PurchaseOrderController | create | Process PO creation |
| `/inventory` | InventoryController | index | Inventory overview |
| `/inventory/adjust` | InventoryController | showAdjustForm | Inventory adjustment form |
| `/inventory/adjust` | InventoryController | adjust | Process inventory adjustment |
| `/expenses` | ExpenseController | index | List expenses |
| `/expenses/new` | ExpenseController | new | Create expense form |
| `/expenses/log` | ExpenseController | log | Process expense logging |
| `/settings` | SettingsController | index | System settings |
| `/settings/update` | SettingsController | update | Update settings |
| `/settings/adminUtility` | SettingsController | adminUtility | Admin utilities (AJAX) |
| `/settings/systemInfo` | SettingsController | systemInfo | System information (AJAX) |
| `/settings/logs` | SettingsController | logs | System logs viewer |
| `/settings/exportSettings` | SettingsController | exportSettings | Export settings (JSON) |
| `/settings/importSettings` | SettingsController | importSettings | Import settings (JSON) |

## Workflow Overview

### Batch Management
1. **Create Batch**: Log supplier batch information including weight and moisture content
2. **Track Batches**: Monitor batch status and details

### Dispatch Management
1. **Create Dispatch**: Register transporter and batch dispatch details
2. **Track Dispatches**: Monitor dispatch status and location
3. **Receive Cargo**: Verify and confirm receipt of dispatched goods

### Purchase Orders
1. **Create PO**: Raise new purchase orders with suppliers
2. **Track Orders**: Monitor order status and delivery schedules

### Inventory Management
1. **Track Stock**: Monitor current inventory levels by grain type
2. **Adjust Inventory**: Make stock movements and balance updates
3. **Low Stock Alerts**: Get notified when stock falls below threshold

### Expense Tracking
1. **Log Expenses**: Record operational costs by category
2. **Track Spending**: Monitor expenses by category and time period

### Settings & Configuration
1. **System Settings**: Configure system-wide preferences and behavior
2. **Admin Utilities**: Manage system maintenance and optimization
3. **Import/Export**: Backup and restore system configurations
4. **System Monitoring**: View health status and system logs

## System Configuration

### Configurable Settings Categories

#### Company Settings
- **Company Name**: Business name displayed throughout the system
- **Company Email**: Primary contact email for notifications and communications
- **Company Phone**: Business phone number for contact purposes
- **Company Address**: Physical business address for documentation

#### System Settings
- **System Name**: Application title shown in browser and headers
- **Base URL**: Application base URL for proper link generation
- **Default Currency**: Currency code for financial calculations (KES, USD, EUR, GBP)
- **Default Timezone**: System timezone for date/time operations
- **Date Format**: Display format for dates (YYYY-MM-DD, DD/MM/YYYY, etc.)
- **DateTime Format**: Display format for timestamps

#### Business Settings
- **Low Stock Threshold**: Minimum stock level before triggering alerts
- **Enable Notifications**: Toggle system-wide notification system
- **Auto Backup**: Enable automatic daily database backups
- **Backup Retention Days**: Number of days to keep backup files (1-365)

#### Security Settings
- **Session Timeout**: User session duration in seconds (300-86400)
- **Password Min Length**: Minimum required password length (6-50)
- **Enable 2FA**: Toggle two-factor authentication (future feature)

### Admin Utilities

#### System Maintenance
- **Clear Cache**: Remove all cached data and temporary files
- **Reset Queue Jobs**: Reset background job queue (future feature)
- **Optimize Database**: Run database optimization on all tables
- **Clean Old Logs**: Remove system logs older than specified days

#### Backup & Recovery
- **Trigger Backup**: Create immediate database backup
- **View System Logs**: Browse and filter system activity logs
- **System Health**: Monitor application health and performance

#### Import/Export
- **Export Settings**: Download all settings as JSON file
- **Import Settings**: Upload and restore settings from JSON file

### Settings Management

#### Accessing Settings
1. Navigate to **Settings** from the main menu
2. Admin privileges required (role-based access control)
3. Settings organized in tabbed interface for easy navigation

#### Modifying Settings
1. Select appropriate category tab (Company, System, Business, Security)
2. Update desired values in the form
3. Click **Update [Category] Settings** to save changes
4. Changes take effect immediately without system restart

#### Backup & Restore
1. **Export**: Click "Export" button to download current settings as JSON
2. **Import**: Use "Import" button to upload and restore settings from JSON file
3. **Warning**: Import overwrites existing settings - export first as backup

#### System Monitoring
1. **Health Status**: Real-time system health indicators
2. **System Info**: View PHP, database, and server information
3. **Logs**: Browse system logs with filtering by level and date
4. **Maintenance**: Access admin utilities for system maintenance

### Fallback Behavior

If settings are not configured or database is unavailable:
- System uses default values from `.env` file
- Company name defaults to "Grain Management System"
- Currency defaults to "KES" (Kenyan Shilling)
- Timezone defaults to "Africa/Nairobi"
- Date format defaults to "Y-m-d" (YYYY-MM-DD)
- Low stock threshold defaults to 20 units
- Session timeout defaults to 7200 seconds (2 hours)

### Security Considerations

- **Sensitive Settings**: Marked as sensitive and excluded from frontend exposure
- **Role-Based Access**: Settings panel requires admin privileges
- **CSRF Protection**: All forms protected against cross-site request forgery
- **Input Validation**: Server-side validation for all setting updates
- **Audit Trail**: All setting changes logged with user and timestamp

## Development Guidelines

### Best Practices
- Use lowercase URIs for all routes
- Maintain clear naming conventions
- Avoid embedding PHP logic directly in views
- Add inline comments to explain controller methods
- Follow CodeIgniter 4 conventions

### Code Organization
- Controllers handle business logic
- Views handle presentation
- Models handle data operations (Phase 2)
- Routes are organized by module groups

## Support

For issues and questions:
1. Check the CodeIgniter 4 [User Guide](https://codeigniter.com/user_guide/)
2. Review the route structure above
3. Examine controller stub methods for workflow logic

## Version

**Current Version**: Phase 5 - Settings Panel & Admin Utilities Complete
**CodeIgniter Version**: 4.6.2
**PHP Version**: 8.1+ 

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
