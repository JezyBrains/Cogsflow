<?php

namespace App\Models;

use CodeIgniter\Model;

class InspectionSessionModel extends Model
{
    protected $table = 'inspection_sessions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'dispatch_id',
        'batch_id',
        'inspector_id',
        'started_at',
        'completed_at',
        'paused_at',
        'total_duration_seconds',
        'total_bags_expected',
        'total_bags_inspected',
        'total_bags_skipped',
        'total_discrepancies',
        'expected_total_weight_kg',
        'actual_total_weight_kg',
        'weight_variance_percent',
        'session_status',
        'device_type',
        'inspection_mode',
        'session_notes'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'dispatch_id' => 'required|integer',
        'batch_id' => 'required|integer',
        'inspector_id' => 'required|integer',
        'session_status' => 'permit_empty|in_list[in_progress,completed,paused,cancelled]'
    ];

    /**
     * Start a new inspection session
     */
    public function startSession($dispatchId, $batchId, $inspectorId, $deviceType = 'desktop')
    {
        // Check if there's an existing active session
        $existingSession = $this->where('dispatch_id', $dispatchId)
                                ->where('inspector_id', $inspectorId)
                                ->whereIn('session_status', ['in_progress', 'paused'])
                                ->first();

        if ($existingSession) {
            // Resume existing session
            $this->update($existingSession['id'], [
                'session_status' => 'in_progress',
                'paused_at' => null
            ]);
            return $existingSession['id'];
        }

        // Create new session
        $sessionData = [
            'dispatch_id' => $dispatchId,
            'batch_id' => $batchId,
            'inspector_id' => $inspectorId,
            'started_at' => date('Y-m-d H:i:s'),
            'session_status' => 'in_progress',
            'device_type' => $deviceType,
            'total_bags_expected' => 0,
            'total_bags_inspected' => 0,
            'total_bags_skipped' => 0,
            'total_discrepancies' => 0
        ];

        return $this->insert($sessionData);
    }

    /**
     * Update session progress
     */
    public function updateProgress($sessionId, $progressData)
    {
        return $this->update($sessionId, $progressData);
    }

    /**
     * Complete inspection session
     */
    public function completeSession($sessionId, $finalNotes = null)
    {
        $session = $this->find($sessionId);
        if (!$session) {
            return false;
        }

        $completedAt = date('Y-m-d H:i:s');
        $startedAt = strtotime($session['started_at']);
        $duration = time() - $startedAt;

        $updateData = [
            'completed_at' => $completedAt,
            'session_status' => 'completed',
            'total_duration_seconds' => $duration
        ];

        if ($finalNotes) {
            $updateData['session_notes'] = $finalNotes;
        }

        return $this->update($sessionId, $updateData);
    }

    /**
     * Pause inspection session
     */
    public function pauseSession($sessionId)
    {
        return $this->update($sessionId, [
            'session_status' => 'paused',
            'paused_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Resume inspection session
     */
    public function resumeSession($sessionId)
    {
        return $this->update($sessionId, [
            'session_status' => 'in_progress',
            'paused_at' => null
        ]);
    }

    /**
     * Cancel inspection session
     */
    public function cancelSession($sessionId, $reason = null)
    {
        $updateData = [
            'session_status' => 'cancelled'
        ];

        if ($reason) {
            $updateData['session_notes'] = $reason;
        }

        return $this->update($sessionId, $updateData);
    }

    /**
     * Get active session for dispatch
     */
    public function getActiveSession($dispatchId, $inspectorId = null)
    {
        $builder = $this->where('dispatch_id', $dispatchId)
                        ->whereIn('session_status', ['in_progress', 'paused']);

        if ($inspectorId) {
            $builder->where('inspector_id', $inspectorId);
        }

        return $builder->first();
    }

    /**
     * Get session statistics
     */
    public function getSessionStats($sessionId)
    {
        $session = $this->find($sessionId);
        if (!$session) {
            return null;
        }

        $stats = [
            'session_id' => $sessionId,
            'status' => $session['session_status'],
            'progress_percent' => 0,
            'bags_remaining' => 0,
            'estimated_time_remaining' => 0,
            'average_time_per_bag' => 0
        ];

        if ($session['total_bags_expected'] > 0) {
            $stats['progress_percent'] = round(
                ($session['total_bags_inspected'] / $session['total_bags_expected']) * 100,
                1
            );
            $stats['bags_remaining'] = $session['total_bags_expected'] - $session['total_bags_inspected'];
        }

        // Calculate average time per bag
        if ($session['total_bags_inspected'] > 0 && $session['started_at']) {
            $elapsedSeconds = time() - strtotime($session['started_at']);
            $stats['average_time_per_bag'] = round($elapsedSeconds / $session['total_bags_inspected']);
            $stats['estimated_time_remaining'] = $stats['average_time_per_bag'] * $stats['bags_remaining'];
        }

        return $stats;
    }

    /**
     * Get inspector's session history
     */
    public function getInspectorSessions($inspectorId, $limit = 10)
    {
        return $this->where('inspector_id', $inspectorId)
                    ->orderBy('started_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get session duration in human-readable format
     */
    public function getSessionDuration($sessionId)
    {
        $session = $this->find($sessionId);
        if (!$session || !$session['started_at']) {
            return '0 minutes';
        }

        $startTime = strtotime($session['started_at']);
        $endTime = $session['completed_at'] ? strtotime($session['completed_at']) : time();
        $duration = $endTime - $startTime;

        $hours = floor($duration / 3600);
        $minutes = floor(($duration % 3600) / 60);
        $seconds = $duration % 60;

        if ($hours > 0) {
            return sprintf('%d hours, %d minutes', $hours, $minutes);
        } elseif ($minutes > 0) {
            return sprintf('%d minutes, %d seconds', $minutes, $seconds);
        } else {
            return sprintf('%d seconds', $seconds);
        }
    }
}
