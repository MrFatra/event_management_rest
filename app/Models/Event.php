<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'event_type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'is_online',
        'location',
        'meeting_link',
        'price',
        'poster',
    ];

    protected $casts = [
        'is_online' => 'bool'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class, 'event_speakers');
    }

    public function ratings() {
        return $this->hasMany(EventRating::class);
    }
}
