<?php

namespace App\Models;

use App\Models\Base\AbstractAPIModel;
use Database\Factories\DistrictsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends AbstractAPIModel
{
    use HasFactory;


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return DistrictsFactory::new();
    }

    protected $fillable = ['name'];

    public function type()
    {
        return 'districts';
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function regions()
    {
        return $this->region();
    }

    public function constituencies()
    {
        return $this->hasMany(Constituency::class);
    }
}
