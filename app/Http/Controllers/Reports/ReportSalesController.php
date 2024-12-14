<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salesget = history_sales::where('userid',auth()->user()->rentersid)
                                        ->latest()
                                        ->get();
                $sales = history_sales::where('userid',auth()->user()->rentersid)
                                        ->latest()
                                        ->paginate(5);

                $totalqty = collect($salesget)->sum('qty'); 
                $totalsales = collect($salesget)->sum('total');

                return view('reports.sales.index')->with(['sales' => $sales])
                ->with(['totalsales' => $totalsales])
                ->with(['totalqty' => $totalqty]);
                

        
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
