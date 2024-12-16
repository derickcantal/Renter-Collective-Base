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
use Illuminate\Contracts\Database\Eloquent\Builder;

class ReportRentalController extends Controller
{
    public function search(Request $request)
    {
        if($request->orderrow == 'H-L'){
            $orderby = "total";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'L-H'){
            $orderby = "total";
            $orderrow = 'asc';
        }elseif($request->orderrow == 'A-Z'){
            $orderby = "productname";
            $orderrow = 'asc';
        }elseif($request->orderrow == 'Z-A'){
            $orderby = "productname";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'Latest'){
            $orderby = "salesid";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'Oldest'){
            $orderby = "salesid";
            $orderrow = 'asc';
        }

        if(empty($request->search)){
            if(empty($request->startdate) && empty($request->enddate)){
                $rentalpaymentsget = history_rental_payments::where('userid',auth()->user()->rentersid)
                                        ->orderBy($orderby,$orderrow)
                                        ->get();
                $rentalpayments = history_rental_payments::where('userid',auth()->user()->rentersid)
                                        ->orderBy($orderby,$orderrow)
                                        ->paginate($request->pagerow);

                $totalqty = collect($rentalpaymentsget)->sum('qty');
                $totalsales = collect($rentalpaymentsget)->sum('total');

    
                return view('reports.rental.index')
                    ->with(['rentalpayments' => $rentalpayments])
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

                $rentalpaymentsget = history_rental_payments::where('userid',auth()->user()->rentersid)
                                            ->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                            ->orderBy($orderby,$orderrow)
                                            ->get();
                $rentalpayments = history_rental_payments::where('userid',auth()->user()->rentersid)
                                        ->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                        ->orderBy($orderby,$orderrow)
                                        ->paginate($request->pagerow);
                
                $totalqty = collect($rentalpaymentsget)->sum('qty');
                $totalsales = collect($rentalpaymentsget)->sum('total');
    

                return view('reports.rental.index')
                    ->with(['rentalpayments' => $rentalpayments])
                    ->with(['totalsales' => $totalsales])
                    ->with(['totalqty' => $totalqty]);
                
            }
        }else{

            $rentalpayments = history_rental_payments::where('userid', auth()->user()->rentersid)
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

            $rentalpaymentsget = history_rental_payments::where('userid', auth()->user()->rentersid)
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

            $totalsales = collect($rentalpaymentsget)->sum('total');
            $totalqty = collect($rentalpaymentsget)->sum('qty');

            $sales_requests = history_sales_requests::where('userid',auth()->user()->rentersid)->orderBy('status','desc')->paginate(5);
        
            $rentalpayments = history_rental_payments::where('userid',auth()->user()->rentersid)->orderBy('status','desc')->paginate(5);


            return view('reports.rental.index')
                ->with(['rentalpayments' => $rentalpayments])
                ->with(['totalsales' => $totalsales])
                ->with(['totalqty' => $totalqty]);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rentalpaymentsget = history_rental_payments::where('userid',auth()->user()->rentersid)
                                        ->latest()
                                        ->get();
        $rentalpayments = history_rental_payments::where('userid',auth()->user()->rentersid)
                                ->latest()
                                ->paginate(5);

        $totalqty = collect($rentalpaymentsget)->sum('qty'); 
        $totalsales = collect($rentalpaymentsget)->sum('total');

        return view('reports.rental.index')
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
