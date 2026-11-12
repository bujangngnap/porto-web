<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $fillable = [
        'display_name',
        'sub_text',
        'url',
        'type',
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
            'id' => $this->id,
            'name' => $this->display_name,
            'displayName' => $this->display_name,
            'subText' => $this->sub_text ?? '',
            'url' => $this->url,
            'type' => $this->type,
        ];
    }
}
