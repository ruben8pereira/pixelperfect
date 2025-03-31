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

    /**
     * Get the images associated with this section.
     */
    public function images()
    {
        return $this->hasMany(ReportImage::class, 'section_id');
    }

    /**
     * Get the primary image for this section.
     */
    public function image()
    {
        return $this->hasOne(ReportImage::class, 'section_id');
    }
}
