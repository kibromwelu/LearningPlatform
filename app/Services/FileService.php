<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileService
{


    public static function storeFile($path, $file)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/' . $path, $filename);
        return $filename;
    }

    public static function getFile($path, $filename)
    {
        $filePath = $path . $filename;
        if (Storage::disk('public')->exists($filePath)) {

            $fileContent = Storage::disk('public')->get($filePath);
            $mimeType = Storage::disk('public')->mimeType($filePath);
            return response($fileContent, 200)->header('Content-Type', $mimeType);

            // return Storage::disk('public')->download($filePath);//uncomment to download the image
        } else {
            return response()->json(['error' => true, 'message' => 'File not found'], 404);
        }
    }
    public static function downloadFile($path, $filename)
    {
        $filePath = $path . $filename;
        if (Storage::disk('public')->exists($filePath)) {

            $fileContent = Storage::disk('public')->get($filePath);
            $mimeType = Storage::disk('public')->mimeType($filePath);
            // return response($fileContent, 200)->header('Content-Type', $mimeType);

            return Storage::disk('public')->download($filePath); //uncomment to download the image
        } else {
            return response()->json(['error' => true, 'message' => 'File not found'], 404);
        }
    }
    public static function deleteFile($filePath, $filename)
    {
        $path = $filePath . $filename;
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
