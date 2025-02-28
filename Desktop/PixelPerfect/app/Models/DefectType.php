<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefectType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'translations',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'translations' => 'array',
    ];

    /**
     * Get the defects for the defect type.
     */
    public function defects()
    {
        return $this->hasMany(ReportDefect::class);
    }

    /**
     * Get the name in the current language.
     *
     * @return string
     */
    public function getLocalizedNameAttribute()
    {
        $locale = app()->getLocale();

        if ($locale === 'en' || !$this->translations || !isset($this->translations['name'][$locale])) {
            return $this->name;
        }

        return $this->translations['name'][$locale];
    }

    /**
     * Get the description in the current language.
     *
     * @return string|null
     */
    public function getLocalizedDescriptionAttribute()
    {
        $locale = app()->getLocale();

        if ($locale === 'en' || !$this->translations || !isset($this->translations['description'][$locale])) {
            return $this->description;
        }

        return $this->translations['description'][$locale];
    }
}
