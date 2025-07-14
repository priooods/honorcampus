<?php

namespace App\Http\Controllers;

use App\Models\THonorTab;
use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;

class HelperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $data = THonorTab::where('id', $id)->with(['mahasiswa', 'dosen', 'type_request', 'type_request_detail'])->first();
        $pdf = LaravelMpdf::loadView('slip', compact('data'));
        return $pdf->stream('slip.pdf');
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
