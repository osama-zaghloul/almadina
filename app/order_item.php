<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order_item extends Model
{
    protected $table = 'order_items';
    public $timestamps = false;
    protected $fillable = ['order_id', 'item_id', 'qty', 'price', 'cutting_id', 'weight_id',  'headType'];
}
