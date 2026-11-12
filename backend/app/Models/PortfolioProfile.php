<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioProfile extends Model
{
    protected $fillable = [
        'hero_top_text',
        'hero_bottom_text',
        'greeting',
        'first_name',
        'last_name',
        'full_name',
        'bio',
        'resume_url',
        'email',
        'location',
        'avatar',
        'avatar_reveal',
        'contact_photo',
    ];

    public function toApiArray(): array
    {
        return [
            'heroTopText' => $this->hero_top_text,
            'heroBottomText' => $this->hero_bottom_text,
            'greeting' => $this->greeting,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'name' => $this->full_name,
            'bio' => $this->bio,
            'resumeUrl' => $this->resume_url ?: '#',
            'email' => $this->email,
            'location' => $this->location,
            'avatar' => $this->avatar ?: '/profile.png',
            'avatarReveal' => $this->avatar_reveal ?: '/PhotoBlackPanther.png',
            'contactPhoto' => $this->contact_photo ?: '/profile.jpg',
        ];
    }
}
