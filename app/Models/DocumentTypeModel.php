<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentTypeModel extends Model
{
    protected $table = 'document_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'description', 'workflow_stage', 'is_required', 
        'allowed_extensions', 'max_file_size_mb'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|max_length[100]',
        'workflow_stage' => 'required|in_list[batch_approval,dispatch_transit,receiving_inspection]',
        'is_required' => 'in_list[0,1]',
        'max_file_size_mb' => 'integer|greater_than[0]'
    ];

    /**
     * Get document types for a specific workflow stage
     */
    public function getDocumentTypesForStage($workflowStage)
    {
        return $this->where('workflow_stage', $workflowStage)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get required document types for a workflow stage
     */
    public function getRequiredDocumentTypesForStage($workflowStage)
    {
        return $this->where('workflow_stage', $workflowStage)
                    ->where('is_required', true)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get all workflow stages
     */
    public function getWorkflowStages()
    {
        return [
            'batch_approval' => 'Batch Approval',
            'dispatch_transit' => 'Dispatch Transit',
            'receiving_inspection' => 'Receiving Inspection'
        ];
    }

    /**
     * Get document type with allowed extensions as array
     */
    public function getDocumentTypeWithExtensions($id)
    {
        $docType = $this->find($id);
        
        if ($docType && $docType['allowed_extensions']) {
            $docType['allowed_extensions_array'] = json_decode($docType['allowed_extensions'], true);
        }
        
        return $docType;
    }
}
