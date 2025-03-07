<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'report_number',
        'description',
        'organization_id',
        'created_by',
        'pdf_export_count',
        'language',
        'inspection_date',
        'operator',
        'client',
        'location',
        'intervention_reason',
        'weather'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'inspection_date' => 'date',
        'pdf_export_count' => 'integer',
    ];

    /**
     * Get the organization that owns the report.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the user that created the report.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the defects for the report.
     */
    public function reportDefects()
    {
        return $this->hasMany(ReportDefect::class);
    }

    /**
     * Get the images for the report.
     */
    public function reportImages()
    {
        return $this->hasMany(ReportImage::class);
    }

    /**
     * Get the comments for the report.
     */
    public function reportComments()
    {
        return $this->hasMany(ReportComment::class);
    }

    /**
     * Get the sections for the report.
     */
    public function reportSections()
    {
        return $this->hasMany(ReportSection::class);
    }

    /**
     * Generate a unique share token for the report.
     *
     * @return string
     */
    public function generateShareToken()
    {
        $token = bin2hex(random_bytes(16)); // 32 character random string
        $this->share_token = $token;
        $this->save();

        return $token;
    }
}
