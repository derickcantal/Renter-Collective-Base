<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\history_sales;
use App\Models\history_sales_requests;
use App\Models\history_rental_payments;
use App\Models\user_login_log;
use \Carbon\Carbon;
use App\Models\branch;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ReportRequestController extends Controller
{
    public function search(Request $request)
    {
        if($request->orderrow == 'Latest'){
            $orderby = "salesrid";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'Oldest'){
            $orderby = "salesrid";
            $orderrow = 'asc';
        }

        if(empty($request->search)){
            if(empty($request->startdate) && empty($request->enddate)){
                $sales_requests_get = history_sales_requests::where('userid',auth()->user()->rentersid)
                                        ->orderBy($orderby,$orderrow)
                                        ->get();
                $sales_requests = history_sales_requests::where('userid',auth()->user()->rentersid)
                                        ->orderBy($orderby,$orderrow)
                                        ->paginate($request->pagerow);

                $totalqty = collect($sales_requests_get)->sum('qty');
                $totalsales = collect($sales_requests_get)->sum('total');

                return view('reports.requests.index')
                    ->with(['sales_requests' => $sales_requests])
                    ->with(['totalsales' => $totalsales])
                    ->with(['totalqty' => $totalqty]);
            }
            elseif(empty($request->startdate) or empty($request->enddate)){
                
                return redirect()->back()
                    
                    ->with('failed','Start & End Dates Required');
            }
            else{
                $startDate = Carbon::parse($request->startdate)->format('Y-m-d');
                $endDate = Carbon::parse($request->enddate)->format('Y-m-d');

                $sales_requests_get = history_sales_requests::where('userid',auth()->user()->rentersid)
                                            ->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                            ->orderBy($orderby,$orderrow)
                                            ->get();
                $sales_requests = history_sales_requests::where('userid',auth()->user()->rentersid)
                                        ->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                        ->orderBy($orderby,$orderrow)
                                        ->paginate($request->pagerow);
                
                $totalqty = collect($sales_requests_get)->sum('qty');
                $totalsales = collect($sales_requests_get)->sum('total');
    
                return view('reports.requests.index')
                    ->with(['sales_requests' => $sales_requests])
                    ->with(['totalsales' => $totalsales])
                    ->with(['totalqty' => $totalqty]);
                
            }
        }else{

            $sales_requests = history_sales_requests::where('userid', auth()->user()->rentersid)
                    ->where(function(Builder $builder) use($request){
                        $builder
                                ->where('cabinetname','like',"%{$request->search}%")
                                ->orWhere('productname','like',"%{$request->search}%")
                                ->orWhere('qty','like',"%{$request->search}%")
                                ->orWhere('srp','like',"%{$request->search}%")
                                ->orWhere('total','like',"%{$request->search}%")
                                ->orWhere('username','like',"%{$request->search}%")
                                ->orWhere('branchname','like',"%{$request->search}%")
                                ->orWhere('snotes','like',"%{$request->search}%");
                    })
                    ->orderBy($orderby,$orderrow)
                    ->paginate($request->pagerow);

            $sales_requests_get = history_sales_requests::where('userid', auth()->user()->rentersid)
                    ->where(function(Builder $builder) use($request){
                        $builder
                                ->where('cabinetname','like',"%{$request->search}%")
                                ->orWhere('productname','like',"%{$request->search}%")
                                ->orWhere('qty','like',"%{$request->search}%")
                                ->orWhere('srp','like',"%{$request->search}%")
                                ->orWhere('total','like',"%{$request->search}%")
                                ->orWhere('username','like',"%{$request->search}%")
                                ->orWhere('branchname','like',"%{$request->search}%")
                                ->orWhere('snotes','like',"%{$request->search}%");
                    })
                    ->orderBy($orderby,$orderrow)
                    ->get();

            $totalsales = collect($sales_requests_get)->sum('total');
            $totalqty = collect($sales_requests_get)->sum('qty');

            return view('reports.requests.index')
                ->with(['sales_requests' => $sales_requests])
                ->with(['totalsales' => $totalsales])
                ->with(['totalqty' => $totalqty]);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //dd('Default Page');
        $sales_requests_get = history_sales_requests::where('userid',auth()->user()->rentersid)
                                        ->latest()
                                        ->get();
        $sales_requests = history_sales_requests::where('userid',auth()->user()->rentersid)
                                ->latest()
                                ->paginate(5);

        $totalqty = collect($sales_requests_get)->sum('qty'); 
        $totalsales = collect($sales_requests_get)->sum('total');

        return view('reports.requests.index')
                ->with(['sales_requests' => $sales_requests])
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
