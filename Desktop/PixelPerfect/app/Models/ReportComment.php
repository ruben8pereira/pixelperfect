<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportComment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'report_id',
        'user_id',
        'content',
        'include_in_pdf',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'include_in_pdf' => 'boolean',
    ];

    /**
     * Get the report that owns the comment.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Get the user that made the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
