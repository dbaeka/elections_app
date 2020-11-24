<?php

namespace App\Models;

use App\Models\Base\AbstractAPIModel;
use Database\Factories\ResultsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends AbstractAPIModel
{
    use HasFactory;

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

    protected $fillable = ['records', 'is_approved'];


    public function type()
    {
        return 'results';
    }

    public function allowedAttributes()
    {
        $results = parent::allowedAttributes();
//        $records = json_decode($results->get('records'));
//        foreach ($records as $key => $value) {
//            $ca = Candidate::find($key)->pres;
//            $p = 10;
//        }
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
}
