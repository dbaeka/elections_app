<?php

namespace App\Models;

use App\Models\Base\AbstractAPIModel;
use Database\Factories\StationsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Station extends AbstractAPIModel
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return StationsFactory::new();
    }

    protected $fillable = ['name', 'code', 'num_voters'];

    public function type()
    {
        return 'stations';
    }

    public function constituency()
    {
        return $this->belongsTo(Constituency::class);
    }

    public function constituencies()
    {
        return $this->constituency();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
