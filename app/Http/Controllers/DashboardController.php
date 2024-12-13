<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\RentalPayments; 
use App\Models\RenterRequests;
use App\Models\user_login_log;
use Illuminate\Http\Request;
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

        return view('dashboard.index');

    }
    public function login(){
        return view('auth.login');
    }
  
    public function displayall(Request $request)
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
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->back()->with('failed','Account Inactive');
        }
        
    }
}
