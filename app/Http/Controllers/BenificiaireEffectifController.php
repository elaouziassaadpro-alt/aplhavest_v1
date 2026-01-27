<?php

namespace App\Http\Controllers;

use App\Models\BenificiaireEffectif;
use App\Models\Pays;
use Illuminate\Http\Request;
use App\Models\Ppe;


class BenificiaireEffectifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
         $info_generales_id = $request->info_generales_id;
         $pays = Pays::all();
         $ppes = Ppe::all();
        return view('etablissements.infoetablissement.BenificiaireEffectif.create', compact('info_generales_id', 'pays', 'ppes'));
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
    public function show(BenificiaireEffectif $benificiaireEffectif)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BenificiaireEffectif $benificiaireEffectif)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BenificiaireEffectif $benificiaireEffectif)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BenificiaireEffectif $benificiaireEffectif)
    {
        //
    }
}
