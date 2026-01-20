<?php

namespace App\Services;

use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Log an event to the audit trail.
     */
    public function log(string $event, $model = null, ?array $oldValues = null, ?array $newValues = null)
    {
        return AuditTrail::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => $model ? get_class($model) : null,
            'auditable_id' => $model ? $model->id : null,
            'old_values' => $this->maskSensitiveData($oldValues),
            'new_values' => $this->maskSensitiveData($newValues),
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    protected function maskSensitiveData(?array $data)
    {
        if (!$data)
            return null;
        $hidden = ['password', 'token', 'secret', 'credit_card', 'pin'];
        foreach ($data as $key => $value) {
            if (in_array($key, $hidden)) {
                $data[$key] = '********';
            }
        }
        return $data;
    }
}
