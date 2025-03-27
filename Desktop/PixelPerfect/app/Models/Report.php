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
        'client',
        'operator',
        'intervention_reason',
        'weather',
        'location'
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
     * Get the pipe sections for the report.
     */
    public function reportSections()
    {
        return $this->hasMany(\App\Models\ReportSection::class, 'report_id');
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

    /**
     * Get the invitations for the report.
     */
    public function invitations()
    {
        return $this->hasMany(ReportInvitation::class);
    }

    /**
     * Generate a unique share token for the report.
     *
     * @param string $email
     * @param int $expiresInDays
     * @return \App\Models\ReportInvitation
     */
    public function shareWith(string $email, int $expiresInDays = 7)
    {
        return $this->invitations()->create([
            'email' => $email,
            'invited_by' => \Illuminate\Support\Facades\Auth::user()->id,
            'token' => \Illuminate\Support\Str::random(64),
            'expires_at' => now()->addDays($expiresInDays),
        ]);
    }
}
