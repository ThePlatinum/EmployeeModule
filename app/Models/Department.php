<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Platinum\LaravelExtras\Traits\Sluggable;

class Department extends Model
{
    use HasFactory, Sluggable;

    private $slugSourceColumn = 'name';

    protected $fillable = [
        'name',
        'slug'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}
