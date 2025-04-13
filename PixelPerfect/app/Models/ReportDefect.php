<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportDefect extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'report_id',
        'defect_type_id',
        'description',
        'severity',
        'coordinates',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'coordinates' => 'array',
    ];

    /**
     * Get the report that owns the defect.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Get the defect type that categorizes the defect.
     */
    public function defectType()
    {
        return $this->belongsTo(DefectType::class);
    }

    /**
     * Get the images for the defect.
     */
    public function images()
    {
        return $this->hasMany(ReportImage::class, 'defect_id');
    }
}
