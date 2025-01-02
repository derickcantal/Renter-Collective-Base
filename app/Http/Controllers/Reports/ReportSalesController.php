<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\history_sales;
use App\Models\user_login_log;
use \Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ReportSalesController extends Controller
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

    public function search(Request $request){
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
                $salesget = history_sales::where('userid',auth()->user()->rentersid)
                                        ->where('total', '!=', '0')
                                        ->orderBy($orderby,$orderrow)
                                        ->get();
                $sales = history_sales::where('userid',auth()->user()->rentersid)
                                        ->where('total', '!=', '0')
                                        ->orderBy($orderby,$orderrow)
                                        ->paginate($request->pagerow);

                $totalqty = collect($salesget)->sum('qty');
                $totalsales = collect($salesget)->sum('total');

                return view('reports.sales.index')->with(['sales' => $sales])
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

                $salesget = history_sales::where('userid',auth()->user()->rentersid)
                                            ->where('total', '!=', '0')
                                            ->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                            ->orderBy($orderby,$orderrow)
                                            ->get();
                $sales = history_sales::where('userid',auth()->user()->rentersid)
                                        ->where('total', '!=', '0')
                                        ->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                        ->orderBy($orderby,$orderrow)
                                        ->paginate($request->pagerow);
                
                $totalqty = collect($salesget)->sum('qty');
                $totalsales = collect($salesget)->sum('total');
    
                return view('reports.sales.index')
                    ->with(['sales' => $sales])
                    ->with(['totalsales' => $totalsales])
                    ->with(['totalqty' => $totalqty]);
                
            }
        }else{

            $sales = history_sales::where('userid', auth()->user()->rentersid)
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

            $salesget = history_sales::where('userid', auth()->user()->rentersid)
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

            $totalsales = collect($salesget)->sum('total');
            $totalqty = collect($salesget)->sum('qty');

            return view('reports.sales.index')
                ->with(['sales' => $sales])
                ->with(['totalsales' => $totalsales])
                ->with(['totalqty' => $totalqty]);
        }
    }
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
