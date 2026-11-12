<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'slug',
        'tab',
        'title',
        'category',
        'description',
        'long_description',
        'tags',
        'link',
        'img',
        'img_object_position',
        'featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function toApiArray(): array
    {
        return [
            'id' => $this->slug,
            'dbId' => $this->id,
            'tab' => $this->tab,
            'title' => $this->title,
            'category' => $this->category,
            'description' => $this->description,
            'longDescription' => $this->long_description ?: $this->description,
            'tags' => $this->tags ?? [],
            'link' => $this->link ?? '',
            'img' => $this->img,
            'imgObjectPosition' => $this->img_object_position ?: '50% 50%',
            'featured' => (bool) $this->featured,
        ];
    }
}
