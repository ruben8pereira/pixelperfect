<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportSection extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
        return $this->belongsTo(Report::class);
    }
}
