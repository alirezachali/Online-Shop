<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboxMessage extends Model
{
    protected $fillable = [
        'type',
        'idempotency_key',
        'payload',
        'status',
        'attempts',
        'last_error',
        'available_at',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'available_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function markSent(): void
    {
        $this->forceFill([
            'status' => 'sent',
            'sent_at' => now(),
            'last_error' => null,
        ])->save();
    }

    public function markFailed(string $error): void
    {
        $attempts = $this->attempts + 1;
        $delaySeconds = min(3600, 3 * (2 ** min($attempts, 10)));

        $this->forceFill([
            'status' => 'pending',
            'attempts' => $attempts,
            'last_error' => $error,
            'available_at' => now()->addSeconds($delaySeconds),
        ])->save();
    }
}
