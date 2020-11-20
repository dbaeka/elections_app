<?php

namespace App\Models;

use App\Models\Base\AbstractAPIModel;
use Database\Factories\ConstituenciesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Constituency extends AbstractAPIModel
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return ConstituenciesFactory::new();
    }

    protected $fillable = ['name'];

    public function type()
    {
        return 'constituencies';
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function districts()
    {
        return $this->district();
    }
}
