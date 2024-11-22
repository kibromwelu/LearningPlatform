<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Http\Requests\StoreLetterRequest;
use App\Http\Requests\UpdateLetterRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


class LetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Letter::with('writer')->get();
        return response()->json(['error' => false, 'message' => "success", 'data' => $response], 200);
        // $imgPath = 'data:image/jpg;base64,' . base64_encode(
        //     file_get_contents(
        //         storage_path('/app/public/image.png')
        //     )
        // );
        // return Pdf::loadView('letter', ['name' => 'Kibrom', 'image_path' => $imgPath, 'data' => $response])->stream('preview.pdf', ['Attachment' => false]);
    }


    public function store(StoreLetterRequest $request)
    {

        $response = Letter::store($request->validated());
        return response()->json(['error' => false, 'message' => 'created successfuly', 'data' => $response], 201);
    }
    public function show($letter)
    {

        return Pdf::loadView('letter')->download('letter.pdf');
    }

    public function update(UpdateLetterRequest $request, Letter $letter)
    {
        $response = Letter::updateLetter($request->validated(), $letter);
        return response()->json(['error' => false, 'message' => 'updated successfuly', 'data' => $response], 202);
    }
    public function destroy(Letter $letter)
    {
        $letter->delete();
        return response()->json(['error' => false, 'message' => 'deleted successfuly'], 200);
    }
}
