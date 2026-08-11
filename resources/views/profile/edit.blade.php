@extends('layouts.tailadmin')

@section('content')
    <div class="">
        <div class="space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
@endsection
