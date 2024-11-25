<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Renter;
use App\Models\branch;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use \Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
      
        $branch = branch::orderBy('branchname', 'asc')->get();
        return view('auth.register',['branch' => $branch]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $branch = branch::where('branchid', $request->branchid)->first();

        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:'.Renter::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Renter::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'mobile_primary' => ['required', 'max:255', 'unique:'.Renter::class],
        ]);

        $renter = Renter::create([
            'avatar' => 'avatars/avatar-default.jpg',
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'mobile_primary' => $request->mobile_primary,
            'birthdate' => $request->birthdate,
            'branchid' => $branch->branchid,
            'branchname' => $branch->branchname,
            'cabid' => 0,
            'cabinetname' => 'Null',
            'accesstype' => 'Renters',
            'created_by' =>$request->email,
            'updated_by' => 'Null', 
            'timerecorded' => $timenow,
            'mod' => 0,
            'status' => 'Inactive',
        ]);

        event(new Registered($renter));

        Auth::login($renter);

        return redirect(route('dashboard', absolute: false));
    }
}
