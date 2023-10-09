<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prdt extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        '_token',
        'price',
        'product_code',
        'description'

    ];
}
