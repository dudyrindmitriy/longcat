<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'visitor_id',
        'type',
        'document_name',
        'series',
        'number',
        'issue_date',
        'issued_by',
        'department_code',
        'region'
    ];

    protected $casts = [
        'issue_date' => 'date'
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
