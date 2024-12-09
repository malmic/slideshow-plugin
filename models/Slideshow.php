<?php

namespace Flosch\Slideshow\Models;

use Model;
use Seimaldigital\Landingpage\Models\Landingpages;

class Slideshow extends Model
{
    use \October\Rain\Database\Traits\Multisite;
    
    /**
     * @var string The database table used by the model.
     */
    public $table = 'flosch_slideshow_slideshows';

    /**
     * @var array Fillable fields
     */
    public $fillable = [
        'name'
    ];

    /**
     * Softly implement the TranslatableModel behavior.
     */
    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var array Attributes that support translation, if available.
     */
    public $translatable = [
        'name'
    ];
    
    public $hasOne = [
        'landingpage' => [Landingpages::class, 'key' => 'slideshow_id']
    ];

    public $hasMany = [
        'slides' => [
            'Flosch\Slideshow\Models\Slide'
        ],
        'publishedSlides' => [
            'Flosch\Slideshow\Models\Slide',
            'scope' => 'published'
        ],
        'slides_count' => [
            'Flosch\Slideshow\Models\Slide',
            'count' => true
        ]
    ];
    
    public function scopeDisableGlobalScopes($query) {
        return $query->withoutGlobalScopes();
    }
    
    protected $propagatable = [];
}
