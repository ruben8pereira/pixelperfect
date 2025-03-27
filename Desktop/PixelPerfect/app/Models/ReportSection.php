<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportSection extends Model
{
    protected $table = 'report_sections';

    protected $fillable = [
        'report_id',
        'name',
        'diameter',
        'material',
        'length',
        'start_manhole',
        'end_manhole',
        'location',
        'comments'
    ];

    /**
     * Get the report that owns the section.
     */
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
