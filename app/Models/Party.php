<?php

namespace App\Models;

use App\Models\Base\AbstractAPIModel;
use Database\Factories\PartiesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Party extends AbstractAPIModel
{
    use HasFactory;


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PartiesFactory::new();
    }

    protected $fillable = ['name', 'short_name'];

    public function type()
    {
        return 'parties';
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class);
    }

    public function candidates()
    {
        return $this->candidate();
    }
}
