<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\history_sales;
use App\Models\history_sales_requests;
use App\Models\history_rental_payments;
use App\Models\user_login_log;
use \Carbon\Carbon;
use App\Models\branch;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ReportRentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //dd('Default Page');
        $salesget = history_sales::where('userid',auth()->user()->rentersid)
                                        ->latest()
                                        ->get();
        $sales = history_sales::where('userid',auth()->user()->rentersid)
                                ->latest()
                                ->paginate(5);

        $totalqty = collect($salesget)->sum('qty'); 
        $totalsales = collect($salesget)->sum('total');

        $branch = branch::orderBy('branchname', 'asc')->get();

        $sales_requests = history_sales_requests::latest()->paginate(5);
        
        $rentalpayments = history_rental_payments::latest()->paginate(5);

        return view('reports.rentalpayments')->with(['sales' => $sales])
                ->with(['sales_requests' => $sales_requests])
                ->with(['branch' => $branch])
                ->with(['rentalpayments' => $rentalpayments])
                ->with(['totalsales' => $totalsales])
                ->with(['totalqty' => $totalqty])
                ->with('i', (request()->input('page', 1) - 1) * 10);
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
