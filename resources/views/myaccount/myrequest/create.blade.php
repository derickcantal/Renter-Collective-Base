<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.myaccount.navigation')
        </div>
    </div>
<div class="py-8">
	<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
			<div class="py-8">
				<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
					<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <form action="{{ route('myrequest.stores',$cabid) }}" method="POST" class="p-4 md:p-5">
                            @csrf 
                            @method('GET')

                            <div class="relative p-4 w-full max-w-full max-h-full">
                                <!-- Breadcrumb -->
                                <nav class="flex px-5 py-3 text-gray-700  bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                                        <li class="inline-flex items-center">
                                        <a href="{{ route('myrequest.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                            </svg>
                                            Request
                                        </a>
                                        </li>
                                        <li aria-current="page">
                                        <div class="flex items-center">
                                            <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                            </svg>
                                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Cabinet Sales </span>
                                        </div>
                                        </li>
                                        <li aria-current="page">
                                        <div class="flex items-center">
                                            <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                            </svg>
                                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Pending Collection</span>
                                        </div>
                                        </li>
                                        <li aria-current="page">
                                        <div class="flex items-center">
                                            <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                            </svg>
                                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Create Request</span>
                                        </div>
                                        </li>
                                    </ol>
                                </nav>
                                <!-- Error & Success Notification -->
                                @include('layouts.notifications') 
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Request Payment Information
                                        </h3>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <div class="col-span-2 sm:col-span-1">
                                            <!-- branchname -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="startdate" :value="__('Start Date')" />
                                                <x-text-input id="startdate" class="block mt-1 w-full" type="text" name="startdate" value="{{ $startdate }}" required autofocus autocomplete="off" readonly/>
                                                <x-input-error :messages="$errors->get('startdate')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <!-- cabinetnumber -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="enddate" :value="__('End Date')" />
                                                <x-text-input id="enddate" class="block mt-1 w-full" type="text" name="enddate" value="{{ $enddate }}" required autofocus autocomplete="off" readonly/> 
                                                
                                                <x-input-error :messages="$errors->get('enddate')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <!-- branchname -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="branchname" :value="__('Branch Name')" />
                                                <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" value="{{ auth()->user()->branchname }}" required autofocus autocomplete="off" readonly/>
                                                <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <!-- cabinetnumber -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="cabinetname" :value="__('Cabinet No.')" />
                                                <x-text-input id="cabinetname" class="block mt-1 w-full" type="text" name="cabinetname" value="{{ $cabinet->cabinetname }}" required autofocus autocomplete="off" readonly/> 
                                                
                                                <x-input-error :messages="$errors->get('cabinetname')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <!-- firstname -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="fullname" :value="__('Full Name')" />
                                                <x-text-input id="fullname" class="block mt-1 w-full" type="text" name="fullname" value="{{ auth()->user()->lastname }}, {{ auth()->user()->firstname }} {{ auth()->user()->middlename }}" required autofocus autocomplete="given-name" readonly/>
                                                <x-input-error :messages="$errors->get('fullname')" class="mt-2" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-span-2 sm:col-span-1">
                                                <!-- total sales -->
                                                <div class="form-group mt-4">
                                                <x-input-label for="totalsales" :value="__('Total Sales')" />
                                                <x-text-input id="totalsales" class="block mt-1 w-full" type="text" name="totalsales" value="{{ number_format($totalsales, 2) }}" required autofocus autocomplete="off" readonly />
                                                <x-input-error :messages="$errors->get('totalsales')" class="mt-2" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-span-2 sm:col-span-1 ">
                                            <!-- Notes -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="rnotes" :value="__('Notes')" />
                                                <x-text-input id="rnotes" class="block mt-1 w-full" type="text" name="rnotes" :value="old('rnotes')"  autofocus autocomplete="off" />
                                                <x-input-error :messages="$errors->get('rnotes')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between col-span-2 sm:col-span-2">
                                            
                                            <x-primary-button class="ms-4">
                                                <a class="btn btn-primary">Create Request</a>
                                            </x-primary-button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>