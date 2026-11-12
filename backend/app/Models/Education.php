<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations';

    protected $fillable = [
        'school',
        'degree',
        'faculty',
        'year',
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
            'id' => 'edu-'.$this->id,
            'school' => $this->school,
            'degree' => $this->degree,
            'faculty' => $this->faculty ?? '',
            'year' => $this->year,
            'tag' => $this->tag ?? '',
            'logo' => $this->logo ?? '',
            'description' => $this->description ?? '',
        ];
    }
}
