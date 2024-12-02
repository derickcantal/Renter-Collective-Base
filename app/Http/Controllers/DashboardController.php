<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\RentalPayments;
use App\Models\RenterRequests;
use App\Models\user_login_log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;

class DashboardController extends Controller
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
     public function renters(){

        $sales = Sales::where('userid',auth()->user()->renterid)
                    ->where(function(Builder $builder){
                        $builder->where('collected_status','Pending')
                                ->where('total','!=',0);
                    })->latest()->paginate(5);

        $RenterRequests = RenterRequests::where('userid',auth()->user()->renterid)
                    ->where(function(Builder $builder){
                        $builder
                                ->orderBy('status','desc');
                    })->latest()->paginate(5);

        $rentalpayments = RentalPayments::where('userid',auth()->user()->renterid)
                    ->latest()
                    ->paginate(5);


        return view('dashboard.index')->with(['sales' => $sales])
                                        ->with(['RenterRequests' => $RenterRequests])
                                        ->with(['rentalpayments' => $rentalpayments])
                                        ->with('i', (request()->input('page', 1) - 1) * 5);

        return redirect()->route('mydashboard.index');

    }

  
    public function displayall()
    {
       
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return $this->cashier();
            }elseif(auth()->user()->accesstype =='Renters'){
                return $this->renters();
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->administrator();
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->administrator();
            }
        }else{
            return view('login')->with('failed','Account Inactive');
        }
        
    }
}
