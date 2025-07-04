<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.myaccount.navigation')
        </div>
    </div>
	<div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                <form action="{{ route('renters.update',$renter->userid) }}" method="POST">
                    @csrf
                    @method('PUT') 
                    <!-- Error & Success Notification -->
                    @include('layouts.notifications') 
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg dark:bg-gray-800">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Renters Profile Information
                            </h3>
                        </div>
                        <!-- Modal body -->
                        <img width="100" height="100" class="rounded-full mt-4" src="{{ asset("/storage/$renter->avatar") }}" alt="user avatar" />
                            <div class="grid gap-4 mb-4 grid-cols-2">
                                <div class="col-span-2 sm:col-span-1 ">
                                    <!-- username -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="username" :value="__('Username')" />
                                        <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username', $renter->username)" required autofocus />
                                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- Email Address -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="email" :value="__('Email')" />
                                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $renter->email)" required />
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- Password -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="password" :value="__('Password')" />

                                        <x-text-input id="password" class="block mt-1 w-full"
                                                        type="password"
                                                        name="password"
                                                            />

                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- Confirm Password -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                                        <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                                        type="password"
                                                        name="password_confirmation"  />

                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                    </div>                    
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- firstname -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="firstname" :value="__('First Name')" />
                                        <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname', $renter->firstname)" required autofocus/>
                                        <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- middlename -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="middlename" :value="__('Middle Name')" />
                                        <x-text-input id="middlename" class="block mt-1 w-full" type="text" name="middlename" :value="old('middlename', $renter->middlename)" required autofocus />
                                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                        <!-- lastname -->
                                        <div class="form-group mt-4">
                                        <x-input-label for="lastname" :value="__('Last Name')" />
                                        <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname', $renter->lastname)" required />
                                        <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- birthdate -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="birthdate" :value="__('Birth Date')" />
                                        <x-text-input id="birthdate" class="block mt-1 w-full" type="date" name="birthdate" :value="date('Y-m-d',strtotime(old('birthdate', $renter->birthdate)))" required autofocus autocomplete="bday" />
                                        <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
                                    </div>
                                </div>
                            
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- branchname -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="branchname" :value="__('Branch Name')" />
                                        <select id="branchname" name="branchname" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('branchname', $renter->branchname)">
                                            @foreach($branch as $branches)
                                            @php
                                                $sel = '';
                                                if($branches->branchname == $renter->branchname):
                                                    $sel = 'selected="selected"';
                                                endif;
                                                
                                            @endphp 
                                                <option value = "{{ $branches->branchname}}"  
                                                    {{ $sel; }}
                                                >{{ $branches->branchname}}</option>
                                            @endforeach
                                        </select>
                                        
                                        <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                    </div>
                                    @php
                                    @endphp
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- cabinetnumber -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="cabinetname" :value="__('Cabinet No.')" />
                                            <select id="cabinetname" name="cabinetname" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('cabinetname', $renter->cabinetname)">
                                            @foreach($cabinet as $cabinets)
                                            @php
                                                $sel = '';
                                                if($cabinets->cabinetname == $renter->cabinetname):
                                                    $sel = 'selected="selected"';
                                                endif;
                                                
                                            @endphp 
                                                <option value = "{{ $cabinets->cabinetname}}"  
                                                    {{ $sel; }}
                                                >{{ $cabinets->cabinetname}}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('cabinetname')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- accesstype -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="accesstype" :value="__('Access Type')" />
                                        <!-- <x-text-input id="accesstype" class="block mt-1 w-full" type="text" name="accesstype" :value="old('accesstype')" required autofocus autocomplete="off" /> -->
                                        <x-text-input id="accesstype" class="block mt-1 w-full" type="text" name="accesstype" :value="old('accesstype','Renters')" required autofocus readonly/>
                                        <x-input-error :messages="$errors->get('accesstype')" class="mt-2" />
                                        
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- status -->
                                    @php
                                        $op1 = '';
                                        $op2 = '';
                                        if ($renter->status == 'Active'):
                                            $op1 = 'selected = "selected"';
                                        elseif ($renter->status == 'Inactive'):
                                            $op2 = 'selected = "selected"';
                                        endif;
                                    @endphp
                                    <div class="form-group mt-4">
                                        <x-input-label for="status" :value="__('Status')" />
                                        <!-- <x-text-input id="status" class="block mt-1 w-full" type="text" name="status" :value="old('status')" required autofocus autocomplete="off" /> -->
                                        <select id="status" name="status" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('status', $renter->status)">
                                            <option value ="Active"  {{ $op1; }}>Active</option>
                                            <option value ="Inactive"  {{ $op2; }}">Inactive</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="flex items-center justify-between col-span-2 sm:col-span-2">
                                    
                                    <x-primary-button class="ms-4">
                                        <a class="btn btn-primary" > Update</a>
                                    </x-primary-button>
                                    </div>
                                </div>
                                
                            </div>
                        
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</x-app-layout>