<?php

namespace App\Models;

use App\Models\Base\AbstractAPIModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImageFile extends AbstractAPIModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_path'
    ];


    public function type()
    {
        return 'images';
    }

    public function allowedAttributes($type=null)
    {
        $image = parent::allowedAttributes();
        $path = $image->get('name');
        return $image->replace(['file_path' => url("/api/v1/get_image/" . $path)]);
    }

    public function result()
    {
        return $this->belongsTo(Result::class);
    }

    public function results()
    {
        return $this->result();
    }
}
