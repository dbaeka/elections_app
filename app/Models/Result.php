<?php

namespace App\Models;

use App\Models\Base\AbstractAPIModel;
use Database\Factories\ResultsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Result extends AbstractAPIModel
{
    use HasFactory;
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $casts = [
        'records' => 'array',
//        'approved' => 'boolean',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return ResultsFactory::new();
    }

    protected $fillable = ['records', 'is_approved', 'station_code', 'remark'];


    public function type()
    {
        return 'results';
    }

    public function allowedAttributes($type=null)
    {
        $results = parent::allowedAttributes();
//        $records = json_decode($results->get('records'));
//        foreach ($records as $key => $value) {
//            $ca = Candidate::find($key)->pres;
//            $p = 10;
//        }
        $images = $this->load(['images' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->images;
        $paths = $images->pluck('name')->map(function ($value) {
            return url("/api/v1/get_image/" . $value);
        });
        $latest = $paths->first();
        $results->prepend($paths, 'all_images');
        $results->prepend($latest, 'recent_image');
        return $results->replace(['records' => json_decode($results->get('records'))]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->user();
    }

    public function images()
    {
        return $this->hasMany(ImageFile::class);
    }

    public function station(){
        return $this->belongsToThrough(Station::class, User::class);
    }

    public function stations() {
        $this->station();
    }
}
