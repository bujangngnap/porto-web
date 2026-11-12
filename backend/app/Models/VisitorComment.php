<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VisitorComment extends Model
{
    protected $fillable = [
        'name',
        'message',
        'avatar_path',
        'is_approved',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
        ];
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function formattedDate(): string
    {
        if (! $this->created_at) {
            return 'Baru saja';
        }

        if ($this->created_at->diffInSeconds(now()) < 60) {
            return 'Baru saja';
        }

        return $this->created_at->locale('id')->diffForHumans();
    }

    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'message' => $this->message,
            'avatar' => $this->avatar_path ?: '',
            'date' => $this->formattedDate(),
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
