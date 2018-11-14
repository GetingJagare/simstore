<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.11.2018
 * Time: 17:00
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

class UploadController extends Controller
{
    public function index(Request $request)
    {
        $files = $request->allFiles();
        /** @var UploadedFile $file */
        $file = $files['file'];

        /** @var File $uploadedFile */
        $uploadedFile = $file->move(public_path('uploads'), $file->getClientOriginalName());
        return json_encode(['location' =>  '/uploads/' . $uploadedFile->getFilename()]);
    }
}