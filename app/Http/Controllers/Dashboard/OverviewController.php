<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\RentalPayments;
use App\Models\Sales;
use App\Models\history_sales;
use App\Models\renter_monthly_sales;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;


class OverviewController extends Controller
{
    public function userlog($notes,$status){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        
        $userlog = user_login_log::query()->create([
            'userid' => auth()->user()->rentersid,
            'username' => auth()->user()->username,
            'firstname' => auth()->user()->firstname,
            'middlename' => auth()->user()->middlename,
            'lastname' => auth()->user()->lastname,
            'email' => auth()->user()->email,
            'branchid' => auth()->user()->branchid,
            'branchname' => auth()->user()->branchname,
            'accesstype' => auth()->user()->accesstype,
            'timerecorded'  => $timenow,
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'mod'  => 0,
            'notes' => $notes,
            'status'  => $status,
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::now();
        $tmonth = $today->month;
        $tyear = $today->year;

        $sales = Sales::where('userid',auth()->user()->rentersid)
                    ->where(function(Builder $builder){
                        $builder->where('collected_status','Pending')
                                ->where('total','!=',0);
                    })->get();

        

        $totalsales = collect($sales)->sum('total');

        $rentalpayments = RentalPayments::where('userid',auth()->user()->rentersid)
        ->latest()
        ->paginate(5);

        $renter_monthly_sales = renter_monthly_sales::where('rentersid',auth()->user()->rentersid)
                                                    ->where(function(Builder $builder){
                                                        $builder->where('status','Active');
                                                    })->get();
        
        foreach($renter_monthly_sales as $rms){
            $rpmonth = $rms->rpmonth;
            $rpyear = $rms->rpyear;
        }
        $jan =  history_sales::where('userid',auth()->user()->rentersid)
        ->where(function(Builder $builder) {  
            $builder->whereYear('created_at', 2025)
                    ->whereMonth('created_at', 1);
        })->get();
        $jansales = collect($jan)->sum('total');
        dd($jansales);
        $history_sales = history_sales::where('userid',auth()->user()->rentersid)
                                            ->where(function(Builder $builder) {            
                                                $builder->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
                                                        ->where('collected_status','Pending');
                                                })->get();

        $test = history_sales::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->get();
           
           $testtotal = collect($history_sales)->sum('total');
           dd($testtotal);
        if(empty($renter_monthly_sales)){

        }elseif($today->month == $rpmonth && $today->year == $rpyear){

            
        }else{
           
        }
                                
       // dd($renter_monthly_sales);

        return view('dashboard.Overview.index')
                            ->with(['rentalpayments' => $rentalpayments])
                            ->with(['renter_monthly_sale' => $renter_monthly_sales])
                            ->with(['totalsales' => $totalsales])
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
