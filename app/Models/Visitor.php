<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'full_name',
        'department_id',
        'birth_date',
        'position',
        'phone',
        'entry_time',
        'exit_time',
        'notes'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'entry_time' => 'datetime',
        'exit_time' => 'datetime'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function document()
    {
        return $this->hasOne(Document::class);
    }
}
