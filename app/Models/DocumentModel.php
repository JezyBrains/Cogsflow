<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentModel extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'document_type_id', 'reference_type', 'reference_id', 'original_filename',
        'stored_filename', 'file_path', 'file_size', 'mime_type', 'uploaded_by',
        'upload_date', 'status', 'notes'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'document_type_id' => 'required|integer',
        'reference_type' => 'required|in_list[batch,dispatch,inspection]',
        'reference_id' => 'required|integer',
        'original_filename' => 'required|max_length[255]',
        'stored_filename' => 'required|max_length[255]',
        'file_path' => 'required|max_length[500]',
        'file_size' => 'required|integer',
        'mime_type' => 'required|max_length[100]',
        'uploaded_by' => 'required|integer',
        'upload_date' => 'required|valid_date',
        'status' => 'in_list[pending,approved,rejected]'
    ];

    protected $validationMessages = [
        'document_type_id' => [
            'required' => 'Document type is required',
            'integer' => 'Document type must be a valid ID'
        ],
        'reference_type' => [
            'required' => 'Reference type is required',
            'in_list' => 'Reference type must be batch, dispatch, or inspection'
        ],
        'reference_id' => [
            'required' => 'Reference ID is required',
            'integer' => 'Reference ID must be a valid ID'
        ]
    ];

    /**
     * Get documents for a specific reference (batch, dispatch, inspection)
     */
    public function getDocumentsByReference($referenceType, $referenceId)
    {
        return $this->select('documents.*, document_types.name as document_type_name, document_types.description as document_type_description')
                    ->join('document_types', 'document_types.id = documents.document_type_id')
                    ->where('documents.reference_type', $referenceType)
                    ->where('documents.reference_id', $referenceId)
                    ->orderBy('documents.upload_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get required documents for a workflow stage
     */
    public function getRequiredDocumentsForStage($workflowStage, $referenceId = null, $referenceType = null)
    {
        $builder = $this->db->table('workflow_document_requirements wdr');
        $builder->select('dt.*, wdr.is_mandatory, wdr.minimum_count');
        $builder->join('document_types dt', 'dt.id = wdr.document_type_id');
        $builder->where('wdr.workflow_stage', $workflowStage);
        $builder->where('wdr.is_mandatory', true);

        $requiredDocs = $builder->get()->getResultArray();

        // If reference provided, check which documents are already uploaded
        if ($referenceId && $referenceType) {
            foreach ($requiredDocs as &$doc) {
                $uploadedCount = $this->where('document_type_id', $doc['id'])
                                    ->where('reference_type', $referenceType)
                                    ->where('reference_id', $referenceId)
                                    ->where('status !=', 'rejected')
                                    ->countAllResults();
                
                $doc['uploaded_count'] = $uploadedCount;
                $doc['is_satisfied'] = $uploadedCount >= $doc['minimum_count'];
            }
        }

        return $requiredDocs;
    }

    /**
     * Check if all required documents are uploaded for a workflow stage
     */
    public function areRequiredDocumentsUploaded($workflowStage, $referenceType, $referenceId)
    {
        $requiredDocs = $this->getRequiredDocumentsForStage($workflowStage, $referenceId, $referenceType);
        
        foreach ($requiredDocs as $doc) {
            if (!$doc['is_satisfied']) {
                return [
                    'satisfied' => false,
                    'missing_documents' => array_filter($requiredDocs, function($d) { return !$d['is_satisfied']; }),
                    'message' => 'Missing required documents: ' . implode(', ', array_column(
                        array_filter($requiredDocs, function($d) { return !$d['is_satisfied']; }), 
                        'name'
                    ))
                ];
            }
        }

        return [
            'satisfied' => true,
            'missing_documents' => [],
            'message' => 'All required documents are uploaded'
        ];
    }

    /**
     * Upload and store document
     */
    public function uploadDocument($file, $documentTypeId, $referenceType, $referenceId, $uploadedBy, $notes = null)
    {
        // Validate file
        if (!$file->isValid()) {
            throw new \Exception('Invalid file uploaded');
        }

        // Get document type info
        $documentTypeModel = new DocumentTypeModel();
        $docType = $documentTypeModel->find($documentTypeId);
        
        if (!$docType) {
            throw new \Exception('Invalid document type');
        }

        // Validate file extension
        $allowedExtensions = json_decode($docType['allowed_extensions'], true);
        $fileExtension = strtolower($file->getClientExtension());
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            throw new \Exception('File type not allowed. Allowed types: ' . implode(', ', $allowedExtensions));
        }

        // Validate file size
        $fileSizeBytes = $file->getSize();
        $maxSizeBytes = $docType['max_file_size_mb'] * 1024 * 1024;
        
        if ($fileSizeBytes > $maxSizeBytes) {
            throw new \Exception('File size exceeds maximum allowed size of ' . $docType['max_file_size_mb'] . 'MB');
        }

        // Generate unique filename
        $originalName = $file->getClientName();
        $storedName = $referenceType . '_' . $referenceId . '_' . $documentTypeId . '_' . time() . '.' . $fileExtension;
        
        // Create upload directory if it doesn't exist
        $uploadPath = WRITEPATH . 'uploads/documents/' . $referenceType . '/' . $referenceId . '/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Move file
        if (!$file->move($uploadPath, $storedName)) {
            throw new \Exception('Failed to upload file');
        }

        // Save to database
        $documentData = [
            'document_type_id' => $documentTypeId,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'original_filename' => $originalName,
            'stored_filename' => $storedName,
            'file_path' => $uploadPath . $storedName,
            'file_size' => $fileSizeBytes,
            'mime_type' => $file->getClientMimeType(),
            'uploaded_by' => $uploadedBy,
            'upload_date' => date('Y-m-d H:i:s'),
            'status' => 'pending',
            'notes' => $notes
        ];

        $documentId = $this->insert($documentData);
        
        if (!$documentId) {
            // Clean up uploaded file if database insert failed
            unlink($uploadPath . $storedName);
            throw new \Exception('Failed to save document record');
        }

        return $documentId;
    }

    /**
     * Delete document and file
     */
    public function deleteDocument($documentId)
    {
        $document = $this->find($documentId);
        
        if (!$document) {
            throw new \Exception('Document not found');
        }

        // Delete physical file
        if (file_exists($document['file_path'])) {
            unlink($document['file_path']);
        }

        // Delete database record
        return $this->delete($documentId);
    }

    /**
     * Update document status
     */
    public function updateDocumentStatus($documentId, $status, $notes = null)
    {
        $validStatuses = ['pending', 'approved', 'rejected'];
        
        if (!in_array($status, $validStatuses)) {
            throw new \Exception('Invalid status');
        }

        $updateData = ['status' => $status];
        
        if ($notes !== null) {
            $updateData['notes'] = $notes;
        }

        return $this->update($documentId, $updateData);
    }

    /**
     * Get document statistics
     */
    public function getDocumentStats()
    {
        $stats = [];
        
        $stats['total_documents'] = $this->countAll();
        $stats['pending_documents'] = $this->where('status', 'pending')->countAllResults();
        $stats['approved_documents'] = $this->where('status', 'approved')->countAllResults();
        $stats['rejected_documents'] = $this->where('status', 'rejected')->countAllResults();
        
        // Documents by type
        $stats['by_reference_type'] = $this->select('reference_type, COUNT(*) as count')
                                          ->groupBy('reference_type')
                                          ->findAll();

        return $stats;
    }
}
