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
        $response = Letter::get();
        $imgPath = 'data:image/jpg;base64,' . base64_encode(
            file_get_contents(
                storage_path('/app/public/AlNU3WTK_400x400.jpg')
            )
        );
        return Pdf::loadView('letter', ['name' => 'Kibrom', 'image_path' => $imgPath, 'data' => $response])->stream('preview.pdf', ['Attachment' => false]);
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
        //
    }


    public function destroy(Letter $letter)
    {
        //
    }
}
