# Grain Management System

A comprehensive grain management system built with CodeIgniter 4 framework for tracking batches, dispatches, inventory, purchase orders, and expenses.

## Features

### Phase 1 - Base Setup (Completed)
- Clean routing structure with meaningful URLs
- Modular layout system with sidebar navigation
- Dashboard with overview statistics
- Batch management (create, list)
- Dispatch management (create, receive, list)
- Purchase order management
- Inventory tracking and adjustment
- Expense logging and categorization
- System settings configuration
- Custom 404 error handling
- Responsive Bootstrap UI

### Planned Features (Phase 2+)
- Database integration with MySQL
- User authentication and authorization
- Reporting and analytics
- Data export functionality
- Email notifications
- API endpoints

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

**Current Version**: Phase 1 - Base Setup Complete
**CodeIgniter Version**: 4.6.2
**PHP Version**: 8.1+ 

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
