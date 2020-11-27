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

    public function allowedAttributes()
    {
        $image = parent::allowedAttributes();
        $path = $image->get('file_path');
        return $image->replace(['file_path' => asset($path)]);
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
