<?php

namespace App\Models;

use App\Models\Base\AbstractAPIModel;
use Database\Factories\CandidatesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Candidate extends AbstractAPIModel
{
    use HasFactory;


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return CandidatesFactory::new();
    }

    protected $fillable = ['pres', 'vice'];

    public function type()
    {
        return 'candidates';
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function parties()
    {
        return $this->party();
    }
}
