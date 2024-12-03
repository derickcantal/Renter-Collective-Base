<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RenterRequests;
use App\Models\Sales;
use App\Models\Renter;
use App\Models\branch;
use App\Models\cabinet;
use App\Models\history_sales;
use App\Models\user_login_log;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;

class MyRequestController extends Controller
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
    public function search(Request $request)
    {
        if(auth()->user()->accesstype =='Renters'){
            $cabinets = cabinet::where('userid',auth()->user()->rentersid)
                    ->orderBy('status','asc')
                    ->orderBy('cabid','asc')
                    ->orderBy('branchname','asc')
                    ->paginate($request->pagerow);

            return view('myrequest.index',['cabinets' => $cabinets])
                    ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);   
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }

    public function loaddata(){

        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        if(auth()->user()->accesstype =='Renters'){
            $cabinets = cabinet::where('userid',auth()->user()->rentersid)
                    ->orderBy('status','asc')
                    ->orderBy('cabid','asc')
                    ->orderBy('branchname','asc')
                    ->paginate(5);

            return view('myrequest.index',['cabinets' => $cabinets])
                        ->with('i', (request()->input('page', 1) - 1) * 5);

            $RenterRequests = RenterRequests::where('cabinetname',auth()->user()->cabinetname)
                        ->where(function(Builder $builder){
                            $builder->where('branchname',auth()->user()->branchname);
                                    
                        })->paginate(5);
            
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
                'notes' => 'My Request',
                'status'  => 'Success',
            ]);  
                        
            return view('myrequest.index',compact('RenterRequests'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }

    public function sales($cabid){
        
        if(auth()->user()->accesstype =='Renters'){
            $history_sales = history_sales::where('cabid',$cabid)
                    ->where(function(Builder $builder){
                        $builder->where('collected_status', "For Approval");
                    })->paginate(5);

            $history_sales1 = history_sales::where('cabid',$cabid)
                        ->where(function(Builder $builder){
                            $builder->where('collected_status', "For Approval");
                        })->get();
                    
            $totalsales = collect($history_sales1)->sum('total');

            if($totalsales == 0)
            {
                return redirect()->route('myrequest.index')
                                    ->with('failed','No Records Found.');
            }

            return view('myrequest.sales',compact('history_sales'))
                    ->with('cabid',$cabid)
                    ->with('i', (request()->input('page', 1) - 1) * 5);
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }
    
    public function storedata($request,$cabid){
        $startdate = Carbon::parse($request->startdate)->format('Y-m-d');
        $enddadte = Carbon::parse($request->enddate)->format('Y-m-d');
        if(auth()->user()->accesstype =='Renters'){
            $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

            $cabinet = cabinet::where('cabid',$cabid)
            ->where(function(Builder $builder){
                $builder->where('userid', auth()->user()->rentersid);
            })->first();

            $renter = Renter::where('rentersid',$cabinet->userid)->first();

            $history_sales = history_sales::where('cabid',$cabid)
                    ->where(function(Builder $builder) use($startdate,$enddadte){
                        $builder->whereBetween('timerecorded', [$startdate .' 00:00:00', $enddadte .' 23:59:59'])
                                ->where('collected_status', "Pending");
                    })->get();

            $totalsales = collect($history_sales)->sum('total');

            if($totalsales == 0)
            {
                return redirect()->route('myrequest.index')
                            ->with('failed','Sales Request creation failed');
            }
            
            if(empty($request->rnotes))
            {
                $rnotes = 'Null';
            }
            else
            {
                $rnotes =  $request->rnotes;
            }

            $RenterRequests = RenterRequests::create([
                'branchid' => $cabinet->branchid,
                'branchname' => $cabinet->branchname,
                'cabid' => $cabinet->cabid,
                'cabinetname' => $cabinet->cabinetname,
                'totalsales' => $totalsales,
                'totalcollected' => 0,
                'avatarproof' => 'avatars/cash-default.jpg',
                'rnotes' => $rnotes,
                'userid' => $renter->rentersid,
                'firstname' => $renter->firstname,
                'lastname' => $renter->lastname,
                'rstartdate' => $request->startdate,
                'renddate' => $request->enddate,
                'created_by' => Auth()->user()->email,
                'updated_by' => 'Null',
                'timerecorded' => $timenow,
                'mod' => 0,
                'posted' => 'N',
                'status' => 'For Approval',
            ]);

            history_sales::where('cabid',$cabid)
                        ->where(function(Builder $builder) use($startdate,$enddadte){
                            $builder->whereBetween('timerecorded', [$startdate .' 00:00:00', $enddadte .' 23:59:59'])
                                    ->where('collected_status', "Pending")
                                    ->where('total','!=', 0)
                                    ->where('returned', 'N');
                        })->update([
                            'collected_status' => 'For Approval',
                            'updated_by' => auth()->user()->email,
                        ]);
        
            if ($RenterRequests) {
                //query successful
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
                    'notes' => 'My Request. Create',
                    'status'  => 'Success',
                ]);  
                return redirect()->route('myrequest.index')
                            ->with('success','Sales Request created successfully.');
            }else{
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
                    'notes' => 'My Request. Create',
                    'status'  => 'Failed',
                ]);  
                return redirect()->route('myrequest.index')
                            ->with('failed','Sales Request creation failed');
            }

        }else{
            return redirect()->route('dashboard.index');
        }
        
    }
    
    public function updatedata(){
        if(auth()->user()->accesstype =='Renters'){
            return redirect()->route('dashboard.index');
        }else{
            return redirect()->route('dashboard.index');
        }
    }
    
    public function destroydata(){
        if(auth()->user()->accesstype =='Renters'){
            return redirect()->route('dashboard.index');
        }else{
            return redirect()->route('dashboard.index');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return $this->loaddata();
            }elseif(auth()->user()->accesstype =='Supervisor' or auth()->user()->accesstype =='Administrator'){
                return redirect()->route('dashboard.index');
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_select_range($cabid)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $cabinet = cabinet::where('cabid', $cabid)
                            ->latest()
                            ->first();

        if($cabinet->userid != auth()->user()->rentersid)
        {
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
                'notes' => 'My Request. Create. CID Error',
                'status'  => 'Failed',
            ]);  
            return redirect()->route('myrequest.index')
                                ->with('failed','Unknown Command.');
        }

        if(auth()->user()->accesstype =='Renters'){
            $cabinet = cabinet::where('cabid',$cabid)
                    ->where(function(Builder $builder){
                        $builder->where('userid', auth()->user()->rentersid);
                    })->first();
        
            $renter = Renter::where('rentersid',$cabinet->userid)->first();

            

            $history_sales = history_sales::where('cabid',$cabid)
                        ->where(function(Builder $builder){
                            $builder->where('collected_status', "Pending");
                        })->get();


                    
            $totalsales = collect($history_sales)->sum('total');

            if($totalsales == 0)
            {
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
                    'notes' => 'My Request. Create. Zero Sales',
                    'status'  => 'Failed',
                ]); 

                return redirect()->route('myrequest.index')
                            ->with('failed','Sales Request creation failed');
            }
            
            return view('myrequest.create_select_range')
                        ->with(['cabinet'=>$cabinet])
                        ->with(['renter'=>$renter])
                        ->with(['history_sales'=>$history_sales])
                        ->with('totalsales',$totalsales)
                        ->with('cabid',$cabid);
        }else{
            return redirect()->route('dashboard.index');
        }
    }
    public function create(Request $request, $cabid)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $cabinet = cabinet::where('cabid', $cabid)
                            ->latest()
                            ->first();

        $startdate = Carbon::parse($request->startdate)->format('Y-m-d');
        $enddadte = Carbon::parse($request->enddate)->format('Y-m-d');

        if($cabinet->userid != auth()->user()->rentersid)
        {
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
                'notes' => 'My Request. Create. CID Error',
                'status'  => 'Failed',
            ]);  
            return redirect()->route('myrequest.index')
                                ->with('failed','Unknown Command.');
        }

        if(auth()->user()->accesstype =='Renters'){
            $cabinet = cabinet::where('cabid',$cabid)
                    ->where(function(Builder $builder){
                        $builder->where('userid', auth()->user()->rentersid);
                    })->first();
        
            $renter = Renter::where('rentersid',$cabinet->userid)->first();

            

            $history_sales = history_sales::where('cabid',$cabid)
                        ->where(function(Builder $builder) use($startdate,$enddadte){
                            $builder->whereBetween('timerecorded', [$startdate .' 00:00:00', $enddadte .' 23:59:59'])
                                    ->where('collected_status', "Pending");
                        })->get();


                    
            $totalsales = collect($history_sales)->sum('total');

            if($totalsales == 0)
            {
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
                    'notes' => 'My Request. Create. Zero Sales',
                    'status'  => 'Failed',
                ]); 

                return redirect()->route('myrequest.index')
                            ->with('failed','No Sales Found.');
            }
            
            return view('myrequest.create')
                        ->with(['cabinet'=>$cabinet])
                        ->with(['renter'=>$renter])
                        ->with(['history_sales'=>$history_sales])
                        ->with('totalsales',$totalsales)
                        ->with('cabid',$cabid)
                        ->with('startdate',$startdate)
                        ->with('enddate',$enddadte);
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $cabid)
    {

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return $this->storedata($request, $cabid);
            }elseif(auth()->user()->accesstype =='Supervisor' or auth()->user()->accesstype =='Administrator'){
                return redirect()->route('dashboard.index');
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $cabid)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $cabinet = cabinet::where('cabid', $cabid)
                            ->latest()
                            ->first();
        if($cabinet->userid != auth()->user()->rentersid)
        {
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
                'notes' => 'My Request. Cabinet. CID Error',
                'status'  => 'Failed',
            ]); 

            return redirect()->route('myrequest.index')
                                ->with('failed','Unknown Command.');
        }
        if(auth()->user()->accesstype =='Renters'){
            $history_sales = history_sales::where('cabid',$cabid)
            ->where(function(Builder $builder){
                $builder->where('collected_status', "Pending")
                        ->where('total','!=', 0);
            })->paginate(5);

            $history_sales1 = history_sales::where('cabid',$cabid)
                        ->where(function(Builder $builder){
                            $builder->where('collected_status', "Pending")
                            ->where('total','!=', 0);
                        })->get();
                    
            $totalsales = collect($history_sales1)->sum('total');

            if($totalsales == 0)
            {
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
                    'notes' => 'My Request. Cabinet. CID Error',
                    'status'  => 'Failed',
                ]);  
                return redirect()->route('myrequest.index')
                                    ->with('failed','No Sales to collect.');
            }

            return view('myrequest.show')
                        ->with('history_sales',$history_sales)
                        ->with('totalsales',$totalsales)
                        ->with('cabid',$cabid)
                        ->with('i', (request()->input('page', 1) - 1) * 5);
        }else{
            return redirect()->route('dashboard.index');
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if(auth()->user()->accesstype =='Renters'){
            $RenterRequests = RenterRequests::findOrFail($id);
            return view('myrequest.edit',['RenterRequests' => $RenterRequests]);
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(auth()->user()->accesstype =='Renters'){
            return redirect()->route('dashboard.index');
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(auth()->user()->accesstype =='Renters'){
            return redirect()->route('dashboard.index');
        }else{
            return redirect()->route('dashboard.index');
        }
    }
}
