<?php

namespace App\Http\Controllers;

use App\Models\LoggedinDevices;
use Illuminate\Http\Request;

class LoggedinDevicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function getMyDevices($id){
        $response = LoggedinDevices::getMyDevices($id);
        return response()->json(['error'=>false, 'data'=>$response],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(LoggedinDevices $loggedinDevices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoggedinDevices $loggedinDevices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoggedinDevices $loggedinDevices)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoggedinDevices $loggedinDevices)
    {
        //
    }
}
