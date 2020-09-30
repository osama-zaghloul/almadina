<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    public $timestamps = false;
    protected $table = 'orders';
    protected $fillable = [
        'order_number', 'status', 'created_at', 'lat', 'lng', 'total', 'name', 'phone', 'date', 'time',
        'notes'
    ];
}
