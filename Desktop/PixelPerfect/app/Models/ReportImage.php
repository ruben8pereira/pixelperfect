<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'report_id',
        'defect_id',
        'file_path',
        'caption',
        'drawing_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'drawing_data' => 'json',
    ];

    /**
     * Get the report that owns the image.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Get the defect that owns the image.
     */
    public function defect()
    {
        return $this->belongsTo(ReportDefect::class, 'defect_id');
    }
}
