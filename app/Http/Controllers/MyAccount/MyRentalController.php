<?php

namespace App\Http\Controllers\MyAccount;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RentalPayments;
use App\Models\cabinet;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\history_rental_payments;
use App\Models\user_login_log;
use \Carbon\Carbon;

class MyRentalController extends Controller
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
                    ->where(function(Builder $builder){
                        $builder->where('branchid',auth()->user()->branchid);
                    })
                    ->orderBy('cabid',$request->orderrow)
                    ->paginate($request->pagerow);

            return view('myaccount.myrental.index',['cabinets' => $cabinets])
                        ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }
    public function loaddata(){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $cabinets = cabinet::where('userid',auth()->user()->rentersid)
                    ->orderBy('status','asc')
                    ->orderBy('cabid','asc')
                    ->orderBy('branchname','asc')
                    ->paginate(5);

        return view('myaccount.myrental.index',['cabinets' => $cabinets])
                    ->with('i', (request()->input('page', 1) - 1) * 5);
        
        $RentalPayments = RentalPayments::where('userid',auth()->user()->rentersid)
                                        ->paginate(5);
        
        $RentalPaymentsHistory = history_rental_payments::where('userid',auth()->user()->rentersid)
                    ->where(function(Builder $builder){
                        $builder->where('branchid',auth()->user()->branchid);
                    })->paginate(5);

        $notes = 'My Rental';
        $status = 'Success';
        $this->userlog($notes,$status);
       

        if(!empty($RentalPayments))
        {
            return view('myaccount.myrental.index',['RentalPayments' => $RentalPayments])
                ->with('i', (request()->input('page', 1) - 1) * 5);
        }
        elseif(!empty($RentalPaymentsHistory))
        {
            return view('myaccount.myrental.index',['RentalPayments' => $RentalPaymentsHistory])
                ->with('i', (request()->input('page', 1) - 1) * 5);
        }
        else
        {
            $notes = 'My Rental. No Record Found.';
            $status = 'Failed';
            $this->userlog($notes,$status);
            return redirect()->back()
                                ->with('failed','No Record Found.');
        }
            
    }
    
    public function storedata(){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
    }
    
    public function updatedata(){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
    }
    
    public function destroydata(){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
    }

    public function cabinetrental(){

        if(auth()->user()->accesstype =='Renters'){
            $RentalPaymentsHistory = history_rental_payments::where('userid',auth()->user()->rentersid)
                    ->where(function(Builder $builder){
                        $builder->where('branchid',auth()->user()->branchid);
                    })->paginate(5);

            if(!empty($RentalPaymentsHistory))
            {
                return view('myaccount.myrental.index',['RentalPayments' => $RentalPaymentsHistory])
                    ->with('i', (request()->input('page', 1) - 1) * 5);
            }
            else
            {
                $notes = 'My Rental. No Record Found.';
                $status = 'Failed.';
                $this->userlog($notes,$status);

                return redirect()->back()
                                    ->with('failed','No Record Found.');
            }
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
    public function create()
    {
        if(auth()->user()->accesstype =='Renters'){
            return view('myaccount.myrental.create');
        }else{
            return redirect()->route('dashboard.index');
        }

        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->user()->accesstype =='Renters'){
            return redirect()->route('dashboard.index');
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show_search(Request $request, $cabinet){
        $cabinet = cabinet::where('cabid',$cabinet)->first();
        $RentalPayments = RentalPayments::where('cabid',$cabinet->cabid)
                                        ->latest()->paginate($request->pagerow);
        $RentalPayments1 = RentalPayments::where('cabid',$cabinet->cabid)
                                        ->latest()->get();
        $recordcount = collect($RentalPayments1)->count('rpid');

        return view('myaccount.myrental.show',['rentalpayments' => $RentalPayments])
                ->with(['cabid'=>$cabinet->cabid])
                ->with(['branchname'=>$cabinet->branchname])
                ->with(['cabinetname'=>$cabinet->cabinetname])
                ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }

    public function show($cabinet)
    {
        if(auth()->user()->accesstype =='Renters'){
            
            $cabinet = cabinet::where('cabid',$cabinet)->first();

            $RentalPayments = RentalPayments::where('cabid',$cabinet->cabid)->latest()->paginate(5);

            $RentalPayments1 = RentalPayments::where('cabid',$cabinet->cabid)->latest()->get();

            $recordcount = collect($RentalPayments1)->count('rpid');

            if($recordcount == 0)
            {
                $notes = 'My Rental. History. No Record Found. Current. ' . $cabinet->branchname . ', ' .$cabinet->cabinetname;
                $status = 'Failed';
                $this->userlog($notes,$status);
                    
                return redirect()->back()
                                    ->with('failed','No Record Found.');
            }
            else
            {
                $notes = 'My Rental. Current Payment.';
                $status = 'Success';
                $this->userlog($notes,$status);

            return view('myaccount.myrental.show',['rentalpayments' => $RentalPayments])
                ->with(['cabid'=>$cabinet->cabid])
                ->with(['branchname'=>$cabinet->branchname])
                ->with(['cabinetname'=>$cabinet->cabinetname])
                ->with('i', (request()->input('page', 1) - 1) * 5);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }

    public function show_history_search(Request $request, $cabinet){
        $cabinet = cabinet::where('cabid',$cabinet)->first();
        $RentalPaymentsHistory = history_rental_payments::where('cabid',$cabinet->cabid)
                                        ->latest()->paginate($request->pagerow);
        $RentalPaymentsHistory1 = history_rental_payments::where('cabid',$cabinet->cabid)
                                        ->latest()->get();
        $recordcount = collect($RentalPaymentsHistory1)->count('rpid');

        return view('myaccount.myrental.show-history',['rentalpayments' => $RentalPaymentsHistory])
                    ->with(['cabinetname'=>$cabinet->cabinetname])
                    ->with(['branchname'=>$cabinet->branchname])
                    ->with(['cabid'=>$cabinet->cabid])
                    ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function show_history($cabinet){
        if(auth()->user()->accesstype =='Renters'){
            $cabinet = cabinet::where('cabid',$cabinet)->first();
            
            $RentalPaymentsHistory = history_rental_payments::where('cabid',$cabinet->cabid)->latest()->paginate(5);
            
            $RentalPaymentsHistory1 = history_rental_payments::where('cabid',$cabinet->cabid)->latest()->get();

            $recordcount = collect($RentalPaymentsHistory1)->count('rpid');

            if($recordcount == 0)
            {
                $notes = 'My Rental. History. No Record Found. Previous. ' . $cabinet->branchname . ', ' .$cabinet->cabinetname;
                $status = 'Failed';
                $this->userlog($notes,$status);
                
            return redirect()->back()
                                ->with('failed','No Record Found.');
            }
            else
            {
                $notes = 'My Rental. Previous Payments.';
                $status = 'Success';
                $this->userlog($notes,$status);

                return view('myaccount.myrental.show-history',['rentalpayments' => $RentalPaymentsHistory])
                    ->with(['cabinetname'=>$cabinet->cabinetname])
                    ->with(['branchname'=>$cabinet->branchname])
                    ->with(['cabid'=>$cabinet->cabid])
                    ->with('i', (request()->input('page', 1) - 1) * 5);
                    
            }
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
            return redirect()->route('dashboard.index');
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
