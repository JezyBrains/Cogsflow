# Comprehensive Reporting & Data Export Module Documentation

## Overview

The Comprehensive Reporting & Data Export Module (Phase 8) provides dynamic, interactive reports with advanced filtering, charting, and export capabilities. The system supports role-based access control and offers multiple export formats including PDF and Excel.

## Architecture

### Database Structure

#### Reports Table (`reports`)
- **Purpose**: Stores report configurations and metadata
- **Key Fields**:
  - `slug`: Unique identifier for the report
  - `name`: Display name of the report
  - `description`: Detailed description
  - `category`: Report category (inventory, financial, operations, suppliers)
  - `query_config`: JSON configuration for data queries
  - `chart_config`: JSON configuration for chart display
  - `filters`: JSON configuration for available filters
  - `roles`: JSON array of roles with access to the report

### Core Components

#### 1. ReportModel (`app/Models/ReportModel.php`)
**Purpose**: Handles all database operations and data aggregation for reports

**Key Methods**:
- `getReportsByRole($userRoles)`: Retrieves reports accessible by user roles
- `getStockSummaryData($filters)`: Generates stock summary report data
- `getExpenseAnalysisData($filters)`: Generates expense analysis data
- `getDispatchPerformanceData($filters)`: Generates dispatch performance metrics
- `getSupplierPerformanceData($filters)`: Generates supplier performance data
- `getBatchAnalyticsData($filters)`: Generates batch analytics data
- `getFilterOptions($field)`: Provides filter dropdown options

**Data Aggregation Logic**:
```php
// Example: Stock Summary Data Generation
public function getStockSummaryData($filters = [])
{
    // Combines incoming stock (batches) with outgoing stock (dispatches)
    // Calculates current stock levels by grain type
    // Applies date, supplier, and grain type filters
    // Returns aggregated data with totals and averages
}
```

#### 2. ReportController (`app/Controllers/ReportController.php`)
**Purpose**: Handles HTTP requests, authentication, and report generation

**Key Methods**:
- `index()`: Displays reports dashboard with role-based filtering
- `view($slug)`: Shows individual report with filters and charts
- `generate($slug)`: AJAX endpoint for report data generation
- `exportPdf($slug)`: Exports report to PDF format
- `exportExcel($slug)`: Exports report to Excel format
- `quickStats()`: Provides dashboard statistics
- `exportAll($format)`: Exports all accessible reports

**Authentication & Authorization**:
```php
// Role-based access control
private function hasReportAccess($report, $userRoles)
{
    if (empty($report['roles'])) {
        return true; // No role restriction
    }
    
    $reportRoles = json_decode($report['roles'], true);
    return !empty(array_intersect($userRoles, $reportRoles));
}
```

### Report Types

#### 1. Stock Summary Report
- **Purpose**: Overview of incoming and outgoing stock with current inventory levels
- **Data Sources**: `batches`, `dispatches` tables
- **Key Metrics**: Total incoming, total outgoing, current stock, batch count
- **Filters**: Date range, grain type, supplier
- **Chart Type**: Bar chart
- **Access**: Admin, Warehouse Staff

#### 2. Expense Analysis Report
- **Purpose**: Detailed breakdown of expenses by category and time period
- **Data Sources**: `expenses` table
- **Key Metrics**: Total amount, average amount, expense count, min/max amounts
- **Filters**: Date range, category, amount range
- **Chart Type**: Pie chart
- **Access**: Admin only

#### 3. Dispatch Performance Report
- **Purpose**: Analysis of dispatch efficiency and delivery performance
- **Data Sources**: `dispatches` table
- **Key Metrics**: Total dispatches, completed dispatches, average delivery time
- **Filters**: Date range, status, vehicle
- **Chart Type**: Line chart
- **Access**: Admin, Warehouse Staff

#### 4. Supplier Performance Report
- **Purpose**: Evaluation of supplier reliability and delivery metrics
- **Data Sources**: `batches` table
- **Key Metrics**: Total batches, total quantity, average quality score
- **Filters**: Date range, supplier, grain type
- **Chart Type**: Radar chart
- **Access**: Admin, Warehouse Staff

#### 5. Batch Analytics Report
- **Purpose**: Comprehensive analysis of batch arrivals and processing
- **Data Sources**: `batches` table
- **Key Metrics**: Batch count, total quantity, average quality
- **Filters**: Date range, status, grain type
- **Chart Type**: Area chart
- **Access**: Admin, Warehouse Staff

## User Interface

### Reports Dashboard (`app/Views/reports/index.php`)
- **Dynamic Report Cards**: Displays reports grouped by category
- **Role-based Visibility**: Shows only reports accessible to current user
- **Quick Export**: Bulk export functionality for all reports
- **Live Statistics**: Real-time dashboard statistics with refresh capability

### Individual Report View (`app/Views/reports/view.php`)
- **Interactive Filters**: Dynamic filter panel with date ranges and dropdowns
- **Chart Visualization**: Responsive charts using Chart.js
- **Data Table**: Sortable, searchable data table
- **Export Options**: PDF, Excel, CSV, and JSON export formats
- **Summary Statistics**: Calculated metrics and totals

### Key Features:
1. **Responsive Design**: Works on all screen sizes
2. **Real-time Updates**: AJAX-powered report generation
3. **Interactive Charts**: Hover effects, legends, and tooltips
4. **Advanced Filtering**: Multiple filter combinations
5. **Export Flexibility**: Multiple format options

## Export Capabilities

### PDF Export
- **Library**: TCPDF
- **Features**: 
  - Professional layouts with headers and footers
  - Formatted data tables
  - Applied filter information
  - Company branding
- **File Naming**: `report_name_YYYY-MM-DD.pdf`

### Excel Export
- **Library**: PhpSpreadsheet
- **Features**:
  - Multiple worksheets for bulk exports
  - Formatted headers and data
  - Auto-sized columns
  - Metadata inclusion
- **File Naming**: `report_name_YYYY-MM-DD.xlsx`

### Additional Formats
- **CSV**: Client-side generation for data tables
- **JSON**: Raw data export for API integration

## Role-Based Access Control

### Access Levels:
1. **Admin**: Access to all reports
2. **Warehouse Staff**: Inventory, batch, and dispatch reports
3. **Standard User**: Limited view reports (if configured)

### Implementation:
```php
// Report configuration with role restrictions
'roles' => json_encode(['admin', 'warehouse_staff'])

// Access check in controller
if (!$this->hasReportAccess($report, $userRoles)) {
    return redirect()->to('/reports')->with('error', 'Access denied');
}
```

## Adding New Reports

### Step 1: Database Configuration
Add new report to the `reports` table:

```sql
INSERT INTO reports (name, slug, description, category, icon, color, query_config, chart_config, filters, roles, sort_order) 
VALUES (
    'New Report Name',
    'new_report_slug',
    'Description of the new report',
    'category_name',
    'bx-icon-name',
    'primary',
    '{"tables": ["table_name"], "metrics": ["metric1", "metric2"]}',
    '{"type": "bar", "responsive": true}',
    '{"date_range": true, "custom_filter": true}',
    '["admin", "warehouse_staff"]',
    6
);
```

### Step 2: Add Data Method to ReportModel
```php
public function getNewReportData($filters = [])
{
    $db = \Config\Database::connect();
    $builder = $db->table('your_table');
    
    $query = $builder->select('
        column1,
        SUM(column2) as total_column2,
        COUNT(*) as record_count
    ')->groupBy('column1');
    
    // Apply filters
    if (!empty($filters['date_from'])) {
        $query->where('date_column >=', $filters['date_from']);
    }
    
    return $query->get()->getResultArray();
}
```

### Step 3: Update ReportController
Add case to `generateReportData()` method:
```php
case 'new_report_slug':
    return $this->reportModel->getNewReportData($filters);
```

### Step 4: Add Filter Options (if needed)
Update `getFilterOptions()` method in ReportModel:
```php
case 'new_filter_field':
    return $db->table('table_name')->select('field_name')->distinct()->get()->getResultArray();
```

## API Endpoints

### Report Generation
- **URL**: `/reports/generate/{slug}`
- **Method**: GET
- **Parameters**: Filter parameters as query string
- **Response**: JSON with report data and chart configuration

### Quick Statistics
- **URL**: `/reports/quick-stats`
- **Method**: GET
- **Response**: JSON with dashboard statistics

### Export Endpoints
- **PDF**: `/reports/export-pdf/{slug}`
- **Excel**: `/reports/export-excel/{slug}`
- **All Reports**: `/reports/export-all/{format}`

## Performance Considerations

### Database Optimization
1. **Indexes**: Ensure proper indexing on filtered columns
2. **Query Optimization**: Use appropriate JOINs and WHERE clauses
3. **Data Limits**: Implement pagination for large datasets

### Caching Strategy
```php
// Example caching implementation
$cacheKey = "report_{$slug}_" . md5(serialize($filters));
$cachedData = cache()->get($cacheKey);

if (!$cachedData) {
    $cachedData = $this->generateReportData($slug, $filters);
    cache()->save($cacheKey, $cachedData, 300); // 5 minutes
}
```

### Frontend Optimization
1. **Lazy Loading**: Load charts only when needed
2. **Data Pagination**: Implement client-side pagination for large tables
3. **Chart Optimization**: Use appropriate chart types for data size

## Security Measures

### Input Validation
- All filter inputs are validated and sanitized
- SQL injection prevention through query builder
- CSRF protection on all forms

### Access Control
- Authentication required for all report endpoints
- Role-based access control enforced
- Audit logging for report access and exports

### Data Protection
- Sensitive data masking in exports
- Secure file handling for downloads
- Temporary file cleanup

## Testing

### Unit Tests
```php
// Example test for report data generation
public function testStockSummaryDataGeneration()
{
    $reportModel = new ReportModel();
    $filters = ['date_from' => '2025-01-01', 'date_to' => '2025-12-31'];
    
    $data = $reportModel->getStockSummaryData($filters);
    
    $this->assertIsArray($data);
    $this->assertArrayHasKey('grain_type', $data[0]);
    $this->assertArrayHasKey('total_incoming', $data[0]);
}
```

### Integration Tests
- Test report generation with various filter combinations
- Verify export functionality for all formats
- Test role-based access control

## Deployment Notes

### Dependencies
- TCPDF: `composer require tecnickcom/tcpdf`
- PhpSpreadsheet: `composer require phpoffice/phpspreadsheet`
- Chart.js: Loaded via CDN

### Configuration
- Ensure proper file permissions for export directories
- Configure memory limits for large report exports
- Set up proper error logging

### Migration
Run the reports table migration:
```bash
php spark migrate
```

## Troubleshooting

### Common Issues

1. **Memory Limit Exceeded**
   - Increase PHP memory limit
   - Implement data pagination
   - Use streaming for large exports

2. **Chart Not Displaying**
   - Check Chart.js CDN availability
   - Verify data format compatibility
   - Check browser console for errors

3. **Export Failures**
   - Verify library installations
   - Check file permissions
   - Monitor server logs

4. **Access Denied Errors**
   - Verify user roles and permissions
   - Check report role configurations
   - Ensure authentication is working

## Future Enhancements

### Planned Features
1. **Scheduled Reports**: Automated report generation and email delivery
2. **Custom Report Builder**: User-friendly report creation interface
3. **Advanced Analytics**: Machine learning insights and predictions
4. **Real-time Dashboards**: WebSocket-powered live updates
5. **Mobile App Integration**: API endpoints for mobile applications

### Performance Improvements
1. **Database Sharding**: For large-scale deployments
2. **Redis Caching**: Advanced caching strategies
3. **Background Processing**: Queue-based report generation
4. **CDN Integration**: Static asset optimization

This comprehensive reporting system provides a solid foundation for business intelligence and data analysis within the GrainFlow application, with extensibility for future enhancements and customizations.
