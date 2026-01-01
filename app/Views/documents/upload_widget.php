<div class="document-upload-widget" data-workflow-stage="<?= $workflow_stage ?>" data-reference-type="<?= $reference_type ?>" data-reference-id="<?= $reference_id ?>">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bx bx-file-blank me-2"></i>
                Document Management
                <span class="badge bg-info ms-2"><?= ucwords(str_replace('_', ' ', $workflow_stage)) ?></span>
            </h5>
        </div>
        <div class="card-body">
            <!-- Required Documents Status -->
            <div class="required-documents-status mb-3">
                <h6 class="text-muted mb-2">Required Documents Status:</h6>
                <div class="row">
                    <?php foreach ($required_documents as $reqDoc): ?>
                        <div class="col-md-4 mb-2">
                            <div class="d-flex align-items-center">
                                <?php if ($reqDoc['is_satisfied']): ?>
                                    <i class="bx bx-check-circle text-success me-2"></i>
                                    <span class="text-success"><?= esc($reqDoc['name']) ?></span>
                                <?php else: ?>
                                    <i class="bx bx-x-circle text-danger me-2"></i>
                                    <span class="text-danger"><?= esc($reqDoc['name']) ?></span>
                                <?php endif; ?>
                                <small class="text-muted ms-2">(<?= $reqDoc['uploaded_count'] ?? 0 ?>/<?= $reqDoc['minimum_count'] ?>)</small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Upload Form -->
            <div class="upload-section">
                <form id="documentUploadForm" enctype="multipart/form-data">
                    <input type="hidden" name="reference_type" value="<?= $reference_type ?>">
                    <input type="hidden" name="reference_id" value="<?= $reference_id ?>">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <label for="document_type_id" class="form-label">Document Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="document_type_id" name="document_type_id" required>
                                <option value="">Select Document Type</option>
                                <?php foreach ($document_types as $docType): ?>
                                    <option value="<?= $docType['id'] ?>" 
                                            data-extensions="<?= esc($docType['allowed_extensions']) ?>"
                                            data-max-size="<?= $docType['max_file_size_mb'] ?>">
                                        <?= esc($docType['name']) ?>
                                        <?php if ($docType['is_required']): ?>
                                            <span class="text-danger">*</span>
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted" id="documentTypeHelp"></small>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="document_file" class="form-label">Select File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="document_file" name="document_file" required>
                            <small class="form-text text-muted" id="fileHelp">Max size: 10MB</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <input type="text" class="form-control" id="notes" name="notes" placeholder="Additional notes">
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" id="uploadBtn">
                                <i class="bx bx-upload me-2"></i>Upload Document
                            </button>
                            <div class="upload-progress mt-2" style="display: none;">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Existing Documents -->
            <div class="existing-documents mt-4">
                <h6 class="text-muted mb-3">Uploaded Documents:</h6>
                <div class="documents-list">
                    <?php if (empty($existing_documents)): ?>
                        <div class="text-center text-muted py-3">
                            <i class="bx bx-file-blank display-4"></i>
                            <p class="mb-0">No documents uploaded yet</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Document Type</th>
                                        <th>File Name</th>
                                        <th>Upload Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($existing_documents as $doc): ?>
                                        <tr data-document-id="<?= $doc['id'] ?>">
                                            <td><?= esc($doc['document_type_name']) ?></td>
                                            <td>
                                                <i class="bx bx-file me-1"></i>
                                                <?= esc($doc['original_filename']) ?>
                                                <small class="text-muted d-block"><?= number_format($doc['file_size'] / 1024, 1) ?> KB</small>
                                            </td>
                                            <td><?= date('M j, Y g:i A', strtotime($doc['upload_date'])) ?></td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'pending' => 'warning',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger'
                                                ];
                                                ?>
                                                <span class="badge bg-<?= $statusClass[$doc['status']] ?? 'secondary' ?>">
                                                    <?= ucfirst($doc['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('documents/download/' . $doc['id']) ?>" 
                                                       class="btn btn-outline-primary btn-sm" 
                                                       title="Download">
                                                        <i class="bx bx-download"></i>
                                                    </a>
                                                    <?php if ($doc['uploaded_by'] == session()->get('user_id') || session()->get('role') === 'admin'): ?>
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-sm delete-document" 
                                                                data-document-id="<?= $doc['id'] ?>"
                                                                title="Delete">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function safeToast(type, message) {
        if (typeof window.showToast === 'function') {
            window.showToast(type, message);
        }
    }

    $('.document-upload-widget').each(function() {
        const widget = $(this);
        const workflowStage = widget.data('workflow-stage');
        const referenceType = widget.data('reference-type');
        const referenceId = widget.data('reference-id');
        const form = widget.find('#documentUploadForm');

        widget.find('#document_type_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const extensions = selectedOption.data('extensions');
            const maxSize = selectedOption.data('max-size');
            
            if (extensions) {
                let extArray = [];
                try {
                    extArray = JSON.parse(extensions);
                } catch (e) {
                    extArray = [];
                }

                const acceptAttr = extArray.map(ext => '.' + ext).join(',');
                widget.find('#document_file').attr('accept', acceptAttr);
                widget.find('#documentTypeHelp').text(`Allowed: ${extArray.join(', ')} | Max: ${maxSize}MB`);
                widget.find('#fileHelp').text(`Allowed: ${extArray.join(', ')} | Max: ${maxSize}MB`);
            }
        });

        form.on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const uploadBtn = widget.find('#uploadBtn');
            const progressDiv = widget.find('.upload-progress');
            const progressBar = widget.find('.progress-bar');

            uploadBtn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Uploading...');
            progressDiv.show();

            $.ajax({
                url: '<?= base_url('documents/upload') ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            const percentComplete = (evt.loaded / evt.total) * 100;
                            progressBar.css('width', percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    if (response && response.success) {
                        safeToast('success', response.message || 'Document uploaded successfully');

                        form[0].reset();
                        widget.find('#document_type_id').trigger('change');

                        setTimeout(function() {
                            location.reload();
                        }, 300);

                        $(document).trigger('documentsUpdated', {
                            workflowStage: workflowStage,
                            referenceType: referenceType,
                            referenceId: referenceId,
                            allDocumentsUploaded: response.all_documents_uploaded
                        });
                    } else {
                        safeToast('error', (response && response.message) ? response.message : 'Upload failed');
                        if (response && response.errors) {
                            console.error('Validation errors:', response.errors);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    safeToast('error', 'Upload failed: ' + error);
                },
                complete: function() {
                    uploadBtn.prop('disabled', false).html('<i class="bx bx-upload me-2"></i>Upload Document');
                    progressDiv.hide();
                    progressBar.css('width', '0%');
                }
            });
        });
    });

    // Handle document deletion
    $(document).on('click', '.delete-document', function() {
        const documentId = $(this).data('document-id');
        const row = $(this).closest('tr');
        
        if (confirm('Are you sure you want to delete this document?')) {
            $.ajax({
                url: `<?= base_url('documents/delete') ?>/${documentId}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        row.fadeOut(function() {
                            $(this).remove();
                            
                            // Check if no documents left
                            if ($('.documents-list tbody tr').length === 0) {
                                $('.documents-list').html(`
                                    <div class="text-center text-muted py-3">
                                        <i class="bx bx-file-blank display-4"></i>
                                        <p class="mb-0">No documents uploaded yet</p>
                                    </div>
                                `);
                            }
                        });
                        
                        // Trigger custom event for workflow validation
                        $(document).trigger('documentsUpdated', {
                            workflowStage: workflowStage,
                            referenceType: referenceType,
                            referenceId: referenceId,
                            allDocumentsUploaded: false
                        });
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function() {
                    showToast('error', 'Failed to delete document');
                }
            });
        }
    });

    // Initialize tooltips
    $('[title]').tooltip();
});

// Toast notification function
function showToast(type, message) {
    const toastClass = type === 'success' ? 'bg-success' : 'bg-danger';
    const toast = $(`
        <div class="toast align-items-center text-white ${toastClass} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `);
    
    $('.toast-container').append(toast);
    const bsToast = new bootstrap.Toast(toast[0]);
    bsToast.show();
    
    // Remove toast element after it's hidden
    toast.on('hidden.bs.toast', function() {
        $(this).remove();
    });
}
</script>

<!-- Toast container -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>
