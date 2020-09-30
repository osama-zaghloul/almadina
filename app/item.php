<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class item extends Model
{
  public $timestamps = false;
  protected $fillable = ['artitle', 'price', 'discountprice', 'image', 'qty', 'suspensed', 'details', 'code'];
}