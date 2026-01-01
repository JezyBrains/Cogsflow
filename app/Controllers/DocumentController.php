<?php

namespace App\Controllers;

use App\Models\DocumentModel;
use App\Models\DocumentTypeModel;
use CodeIgniter\HTTP\ResponseInterface;

class DocumentController extends BaseController
{
    protected $documentModel;
    protected $documentTypeModel;

    public function __construct()
    {
        $this->documentModel = new DocumentModel();
        $this->documentTypeModel = new DocumentTypeModel();
    }

    /**
     * Upload document via AJAX
     */
    public function upload()
    {
        try {
            $validation = \Config\Services::validation();
            
            $rules = [
                'document_type_id' => 'required|integer',
                'reference_type' => 'required|in_list[batch,dispatch,inspection]',
                'reference_id' => 'required|integer',
                'document_file' => 'uploaded[document_file]|max_size[document_file,10240]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $file = $this->request->getFile('document_file');
            $documentTypeId = $this->request->getPost('document_type_id');
            $referenceType = $this->request->getPost('reference_type');
            $referenceId = $this->request->getPost('reference_id');
            $notes = $this->request->getPost('notes');
            $uploadedBy = session()->get('user_id');

            $documentId = $this->documentModel->uploadDocument(
                $file,
                $documentTypeId,
                $referenceType,
                $referenceId,
                $uploadedBy,
                $notes
            );

            // Check if all required documents are now uploaded
            $workflowStage = $this->getWorkflowStageFromReference($referenceType);
            $documentCheck = $this->documentModel->areRequiredDocumentsUploaded(
                $workflowStage,
                $referenceType,
                $referenceId
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'document_id' => $documentId,
                'all_documents_uploaded' => $documentCheck['satisfied'],
                'missing_documents' => $documentCheck['missing_documents'] ?? []
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Document upload error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get documents for a reference
     */
    public function getDocuments($referenceType, $referenceId)
    {
        try {
            $documents = $this->documentModel->getDocumentsByReference($referenceType, $referenceId);
            
            return $this->response->setJSON([
                'success' => true,
                'documents' => $documents
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get required documents for a workflow stage
     */
    public function getRequiredDocuments($workflowStage, $referenceType = null, $referenceId = null)
    {
        try {
            $requiredDocs = $this->documentModel->getRequiredDocumentsForStage(
                $workflowStage,
                $referenceId,
                $referenceType
            );
            
            return $this->response->setJSON([
                'success' => true,
                'required_documents' => $requiredDocs
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check if required documents are uploaded
     */
    public function checkRequiredDocuments($workflowStage, $referenceType, $referenceId)
    {
        try {
            $documentCheck = $this->documentModel->areRequiredDocumentsUploaded(
                $workflowStage,
                $referenceType,
                $referenceId
            );
            
            return $this->response->setJSON([
                'success' => true,
                'satisfied' => $documentCheck['satisfied'],
                'missing_documents' => $documentCheck['missing_documents'],
                'message' => $documentCheck['message']
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete document
     */
    public function delete($documentId)
    {
        try {
            $document = $this->documentModel->find($documentId);
            
            if (!$document) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Document not found'
                ]);
            }

            // Check if user can delete (only uploader or admin)
            $currentUserId = session()->get('user_id');
            $userRole = session()->get('role');
            
            if ($document['uploaded_by'] != $currentUserId && $userRole !== 'admin') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'You can only delete documents you uploaded'
                ]);
            }

            $this->documentModel->deleteDocument($documentId);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Download document
     */
    public function download($documentId)
    {
        try {
            $document = $this->documentModel->find($documentId);
            
            if (!$document) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Document not found');
            }

            if (!file_exists($document['file_path'])) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
            }

            return $this->response->download($document['file_path'], null)
                                 ->setFileName($document['original_filename']);

        } catch (\Exception $e) {
            log_message('error', 'Document download error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to download document');
        }
    }

    /**
     * Update document status
     */
    public function updateStatus($documentId)
    {
        try {
            $status = $this->request->getPost('status');
            $notes = $this->request->getPost('notes');

            $this->documentModel->updateDocumentStatus($documentId, $status, $notes);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Document status updated successfully'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get document types for a workflow stage
     */
    public function getDocumentTypes($workflowStage)
    {
        try {
            $documentTypes = $this->documentTypeModel->getDocumentTypesForStage($workflowStage);
            
            return $this->response->setJSON([
                'success' => true,
                'document_types' => $documentTypes
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Render document upload widget
     */
    public function renderUploadWidget($workflowStage, $referenceType, $referenceId)
    {
        try {
            $documentTypes = $this->documentTypeModel->getDocumentTypesForStage($workflowStage);
            $existingDocuments = $this->documentModel->getDocumentsByReference($referenceType, $referenceId);
            $requiredDocuments = $this->documentModel->getRequiredDocumentsForStage(
                $workflowStage,
                $referenceId,
                $referenceType
            );

            $data = [
                'workflow_stage' => $workflowStage,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'document_types' => $documentTypes,
                'existing_documents' => $existingDocuments,
                'required_documents' => $requiredDocuments
            ];

            return view('documents/upload_widget', $data);

        } catch (\Exception $e) {
            log_message('error', 'Document widget render error: ' . $e->getMessage());
            return '<div class="alert alert-danger">Error loading document widget</div>';
        }
    }

    /**
     * Get workflow stage from reference type
     */
    private function getWorkflowStageFromReference($referenceType)
    {
        $stageMap = [
            'batch' => 'batch_approval',
            'dispatch' => 'dispatch_transit',
            'inspection' => 'receiving_inspection'
        ];

        return $stageMap[$referenceType] ?? null;
    }
}
