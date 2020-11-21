<?php

namespace App\Models;

use App\Models\Base\AbstractAPIModel;
use Database\Factories\RegionsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends AbstractAPIModel
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return RegionsFactory::new();
    }

    protected $fillable = ['name', 'capital'];

    public function type()
    {
        return 'regions';
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
