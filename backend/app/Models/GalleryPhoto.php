<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    protected $fillable = [
        'title',
        'category',
        'src',
        'aspect_class',
        'width_scale',
        'object_position',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'width_scale' => 'float',
            'sort_order' => 'integer',
        ];
    }

    public function toApiArray(): array
    {
        $item = [
            'id' => $this->id,
            'title' => $this->title,
            'category' => $this->category,
            'src' => $this->src,
        ];

        if ($this->aspect_class && $this->aspect_class !== 'aspect-[4/5]') {
            $item['aspectClass'] = $this->aspect_class;
        }

        if ($this->width_scale && abs($this->width_scale - 1.0) > 0.01) {
            $item['widthScale'] = $this->width_scale;
        }

        if ($this->object_position && $this->object_position !== '50% 50%') {
            $item['objectPosition'] = $this->object_position;
        }

        return $item;
    }
}
