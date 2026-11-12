<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'company',
        'role',
        'year',
        'subtitle',
        'tag',
        'logo',
        'description',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function toApiArray(): array
    {
        return [
            'id' => 'exp-'.$this->id,
            'company' => $this->company,
            'role' => $this->role,
            'year' => $this->year,
            'subtitle' => $this->subtitle ?? '',
            'tag' => $this->tag ?? '',
            'logo' => $this->logo ?? '',
            'description' => $this->description ?? '',
        ];
    }
}
