<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductions extends Model
{
    use HasFactory;

    protected $table = 'order_productions';

    protected $fillable = [
        'sale_id', 'user_id', 'status'
    ];
}
