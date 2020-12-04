<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Controllers\Controller;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Models\ImageFile;
use App\Models\PMResult;
use App\Models\Result;
use Dotenv\Exception\InvalidFileException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\Exception\CannotWriteFileException;

class ImageFileController extends APIController
{
    //

    /**
     * Display a listing of the resource.
     *
     * @return JSONAPICollection
     */
    public function index()
    {
        //
        return $this->service->fetchResources(ImageFile::class, 'images');
    }

    /**
     * Display the specified resource.
     * @param $result
     * @return JSONAPIResource
     */
    public function show($image)
    {
        //
        return $this->service->fetchResource(ImageFile::class, $image, 'images');
    }


    public function fileUpload(Request $request)
    {
        $request->validate([
            'data' => 'required|mimes:png,jpg,jpeg|max:10240',
            'result_id' => 'sometimes|required'
        ]);

        if (!$request->hasFile('data')) {
            throw new FileNotFoundException('File missing from request.');
        }

        $file = $request->file('data');

        if (!$file->isValid()) {
            throw new InvalidFileException('File is not valid to be used.');
        }

        //save file
        $user = $request->user();
        $result_id = $request->result_id;
        $station = $user->stations()->first();
        $result = ($result_id) ? Result::find($result_id)->first() : $user->results()->latest()->first();
        $name = ($station) ? $station->name : $file->getClientOriginalName();
        $fileName = $this->generateFileName($name) . '.' . $file->extension();

        $filePath = $file->storeAs('uploads', $fileName);

        $image = new ImageFile;
        $image->name = $fileName;
        $image->file_path = $filePath;
        $this->service->updateToOneRelationship($image, 'results', $result->id);
        $image->save();

        return new JSONAPIResource($image);

    }

    public function fileBase64Upload(Request $request)
    {
        $request->validate([
            'data.attributes.content' => 'required|string',
            'data.attributes.result_id' => 'sometimes|required',
            'data.attributes.filename' => 'required|string'
        ]);

        $attributes = collect($request->input('data.attributes'));
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $attributes['content']));
        $file = Image::make($image);
        if (!$file->stream()->isReadable()) {
            throw new InvalidFileException('File is not valid to be used.');
        }

        //save file
        $user = $request->user();
        $result_id = $attributes->get('result_id');
        $station = $user->stations()->first();
        $result = ($result_id) ? Result::find($result_id) : $user->results()->latest()->first();
        $clientName = $attributes['filename'];
        $name = ($station) ? $station->name : File::name($clientName);
        $fileName = $this->generateFileName($name) . '.' . File::extension($clientName);

        $filePath = "uploads/" . $fileName;
        if (!Storage::put($filePath, $file->stream()))
            throw new CannotWriteFileException('Failed to save image');
        $image = new ImageFile;
        $image->name = $fileName;
        $image->file_path = $filePath;
        if ($result)
            $this->service->updateToOneRelationship($image, 'results', $result->id);
        $image->save();

        return new JSONAPIResource($image);

    }

    public function filePMUpload(Request $request)
    {
        $request->validate([
            'data.attributes.content' => 'required|string',
            'data.attributes.result_id' => 'sometimes|required',
            'data.attributes.filename' => 'required|string'
        ]);

        $attributes = collect($request->input('data.attributes'));
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $attributes['content']));
        $file = Image::make($image);
        if (!$file->stream()->isReadable()) {
            throw new InvalidFileException('File is not valid to be used.');
        }

        //save file
        $user = $request->user();
        $result_id = $attributes->get('result_id');
        $station = $user->stations()->first();
        $result = ($result_id) ? PMResult::find($result_id) : $user->pm_results()->latest()->first();
        $clientName = $attributes['filename'];
        $name = ($station) ? 'pm_'.$station->name : File::name($clientName);
        $fileName = $this->generateFileName($name) . '.' . File::extension($clientName);

        $filePath = "uploads/" . $fileName;
        if (!Storage::put($filePath, $file->stream()))
            throw new CannotWriteFileException('Failed to save image');
        $image = new ImageFile;
        $image->name = $fileName;
        $image->file_path = $filePath;
        if ($result)
            $image->pm_result_id = $result->id;
        $image->save();

        return new JSONAPIResource($image);

    }

    public function download($filename)
    {
        $path = storage_path('app/uploads/' . $filename);
        return Image::make($path)->response();
    }


    private function generateFileName($name)
    {
        return Str::slug($name, '_') .
            '_' .
            now()->format('d_M_Y_H_i') .
            '_' .
            Str::limit(Str::slug(Str::uuid(), '_'), 8, "");
    }
}
