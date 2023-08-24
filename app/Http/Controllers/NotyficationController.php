<?php

namespace App\Http\Controllers;

use App\Models\Notyfication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotyficationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notyfications=Auth::user()->notyfications()->get();
        return response(['notyfications'=>$notyfications]);
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
    public function show(Notyfication $notyfication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notyfication $notyfication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notyfication $notyfication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notyfication $notyfication)
    {
        //
    }
}
