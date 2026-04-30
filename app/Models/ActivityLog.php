<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'properties',
        'ip_address',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    // ── Relationships ─────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    // ── Static Helper ─────────────────────────────────────────

    public static function record(string $action, Model $subject, string $description, array $properties = []): void
    {
        static::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'subject_type' => get_class($subject),
            'subject_id'   => $subject->getKey(),
            'description'  => $description,
            'properties'   => $properties,
            'ip_address'   => request()->ip(),
        ]);
    }

    // ── Action Badge Color Helper ─────────────────────────────

    public function actionBadgeClass(): string
    {
        return match ($this->action) {
            'approved'  => 'bg-success',
            'rejected'  => 'bg-danger',
            'deleted'   => 'bg-dark',
            'cancelled' => 'bg-secondary',
            'created'   => 'bg-primary',
            'updated'   => 'bg-info text-dark',
            default     => 'bg-warning text-dark',
        };
    }
}
