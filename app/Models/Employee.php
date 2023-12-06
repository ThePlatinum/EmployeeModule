<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'first_name',
        'last_name',
        'email'
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function getFullNameAttribute(): string
    {
        $lastName = $this->last_name ? " " . $this->last_name : '';

        return "$this->first_name$lastName";
    }

    public $appends = [
        'full_name'
    ];

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }
}
