# Document Management Workflow Integration

## Overview

This document outlines the comprehensive integration of document management into the CogsFlow grain management system's batch, dispatch, and receiving workflows. The system now enforces document validation at critical workflow stages to ensure compliance and maintain audit trails.

## Workflow Integration Points

### 1. Batch Approval Stage
**Workflow Stage**: `batch_approval`
**Reference Type**: `batch`
**Trigger**: Before batch can be approved

#### Required Documents:
- **Quality Certificate**: Official quality inspection certificate from supplier
- **Supplier Delivery Note**: Official delivery note from supplier  
- **Weight Certificate**: Official weighbridge certificate

#### Implementation:
- Document upload widget appears on batch detail page for pending batches
- Validation occurs in `BatchController::approve()` method
- Users cannot approve batches without all required documents

### 2. Dispatch Transit Stage
**Workflow Stage**: `dispatch_transit`
**Reference Type**: `dispatch`
**Trigger**: Before dispatch can be marked as "in_transit"

#### Required Documents:
- **Transport Permit**: Official transport permit for grain movement
- **Vehicle Inspection Certificate**: Vehicle roadworthiness certificate
- **Driver License**: Valid driver license copy

#### Implementation:
- Document upload widget appears on dispatch detail page for pending dispatches
- Validation occurs in `DispatchController::updateStatus()` method
- Users cannot mark dispatches as in_transit without all required documents

### 3. Receiving Inspection Stage
**Workflow Stage**: `receiving_inspection`
**Reference Type**: `inspection`
**Trigger**: Before receiving inspection can be completed

#### Required Documents:
- **Delivery Receipt**: Signed delivery receipt (required)
- **Inspection Photos**: Photos of received goods and discrepancies (optional)
- **Discrepancy Report**: Detailed report of any discrepancies found (optional)

#### Implementation:
- Document upload widget appears on inspection form
- Validation occurs in `BatchReceivingController::processInspection()` method
- Users cannot complete inspections without all required documents

## Technical Implementation

### Database Schema

#### Tables Created:
1. **document_types**: Defines document types for each workflow stage
2. **documents**: Stores uploaded document records
3. **workflow_document_requirements**: Maps required documents to workflow stages

#### Key Fields:
- `workflow_stage`: Enum of batch_approval, dispatch_transit, receiving_inspection
- `reference_type`: Enum of batch, dispatch, inspection
- `reference_id`: Links to specific batch/dispatch/inspection record
- `is_required`: Boolean flag for mandatory documents

### Models

#### DocumentModel
- Handles document upload, validation, and retrieval
- Provides methods for checking required document compliance
- Manages file storage and security

#### DocumentTypeModel
- Manages document type definitions
- Provides workflow stage filtering
- Handles allowed file extensions and size limits

### Controllers

#### DocumentController
- AJAX endpoints for document upload/download
- Document status management
- Security validation and access control

#### Integration Points:
- **BatchController**: Document validation in approval process
- **DispatchController**: Document validation in status updates
- **BatchReceivingController**: Document validation in inspection completion

### Views

#### Document Upload Widget
- Reusable component for all workflow stages
- Real-time document status tracking
- Progress indicators and validation feedback
- File upload with drag-and-drop support

## Security Features

### Access Control
- Role-based document access
- Users can only delete their own documents (except admins)
- Secure file storage outside web root

### File Validation
- Extension whitelist per document type
- File size limits (configurable per document type)
- MIME type validation
- Virus scanning ready (extensible)

### Audit Trail
- Complete upload/download logging
- User tracking for all document actions
- IP address and timestamp recording

## Workflow Enforcement

### Validation Rules
1. **Batch Approval**: All required batch_approval documents must be uploaded
2. **Dispatch Transit**: All required dispatch_transit documents must be uploaded
3. **Receiving Inspection**: All required receiving_inspection documents must be uploaded

### Error Handling
- Clear error messages when documents are missing
- User-friendly validation feedback
- Graceful degradation for missing document types

### Process Flow
```
1. User attempts workflow action (approve/transit/complete)
2. System checks for required documents
3. If documents missing: Show error, prevent action
4. If documents complete: Allow action to proceed
5. Log action and document compliance
```

## Configuration

### Document Types
Document types are pre-configured but can be modified:
- Add new document types via database
- Modify requirements per workflow stage
- Adjust file size limits and allowed extensions

### File Storage
- Documents stored in `writable/uploads/documents/`
- Organized by reference type and ID
- Configurable storage location

## API Endpoints

### Document Management
- `POST /documents/upload` - Upload document
- `GET /documents/{type}/{id}` - Get documents for reference
- `DELETE /documents/delete/{id}` - Delete document
- `GET /documents/download/{id}` - Download document
- `GET /documents/check/{stage}/{type}/{id}` - Check compliance

### Widget Rendering
- `GET /documents/widget/{stage}/{type}/{id}` - Render upload widget

## Error Scenarios & Solutions

### Common Issues:
1. **Missing Document Types**: System gracefully handles missing document type definitions
2. **File Upload Failures**: Comprehensive error reporting and retry mechanisms
3. **Storage Issues**: Fallback storage options and error recovery
4. **Permission Errors**: Clear access denied messages with resolution steps

### Troubleshooting:
- Check file permissions on upload directory
- Verify database migrations have run
- Confirm document types are properly seeded
- Review error logs for detailed failure information

## Benefits Achieved

### Process Control
- **Compliance**: Ensures all required documents are collected
- **Audit Trail**: Complete documentation of workflow progression
- **Quality Assurance**: Prevents incomplete processes from advancing

### User Experience
- **Intuitive Interface**: Clear document requirements and status
- **Real-time Feedback**: Immediate validation and progress updates
- **Streamlined Process**: Integrated workflow without context switching

### Business Value
- **Risk Mitigation**: Reduces compliance and audit risks
- **Process Standardization**: Enforces consistent documentation practices
- **Operational Efficiency**: Prevents delays from missing documentation

## Future Enhancements

### Planned Features:
1. **Email Notifications**: Alert users when documents are required
2. **Document Templates**: Provide standard document templates
3. **Digital Signatures**: Support for electronic document signing
4. **OCR Integration**: Automatic data extraction from documents
5. **Document Versioning**: Track document revisions and updates

### Extensibility:
- Additional workflow stages can be easily added
- New document types can be configured without code changes
- Custom validation rules can be implemented per document type
- Integration with external document management systems

## Deployment Instructions

### Database Migration
```bash
php spark migrate
```

### File Permissions
```bash
chmod 755 writable/uploads/
mkdir -p writable/uploads/documents/
chmod 755 writable/uploads/documents/
```

### Configuration Verification
1. Verify document types are seeded
2. Test file upload functionality
3. Confirm workflow validation is working
4. Check error handling and user feedback

## Conclusion

The document management integration provides a robust, secure, and user-friendly system for enforcing document requirements throughout the grain management workflow. This implementation strengthens process control, ensures compliance, and provides a solid foundation for future enhancements while maintaining the system's usability and performance.
