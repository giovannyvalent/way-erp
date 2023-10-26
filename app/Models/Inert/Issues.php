<?php

namespace App\Models\Inert;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issues extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'release_id',
        'gravity',
        'urgency',
        'trend',
        'user_id',
        'type',
        'expiry_date',
        'instances_released',
        'status_all',
        'description'
    ];

    public function release(){
        return $this->belongsTo(Release::class);
    }
}
