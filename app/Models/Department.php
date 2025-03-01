<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
    ];
    public function Visitor()
    {
        return $this->hasMany(Visitor::class);
    }
}
