<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RenterRequests;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;

class RequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $RenterRequests = RenterRequests::where('userid',auth()->user()->rentersid)
                    ->where(function(Builder $builder){
                        $builder
                                ->orderBy('status','desc');
                    })->latest()->paginate(5);
        
        return view('dashboard.Requests.index')
                ->with(['RenterRequests' => $RenterRequests])
                ->with('i', (request()->input('page', 1) - 1) * 5);

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
