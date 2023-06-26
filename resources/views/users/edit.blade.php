@extends('layouts.app')

@section('submenu')
@include('layouts.submenu.profile')
@endsection

@section('content')
<section class="my-data-section">
    <div class="container">
        <div class="data-inner">
            <div class="row">
                <!-- Edit Project -->
                @if(request()->route('data') == 'project')
                @include('users.partials.project-edit')
                @else
                @include('users.partials.project-show')
                @endif

                <!-- Edit Fundus -->
                @if(request()->route('data') == 'fundus')
                @include('users.partials.fundus-edit')
                @else
                @include('users.partials.fundus-show')
                @endif
            </div>
        </div>
        @include('users.partials.footer')
    </div>
</section>
@endsection

@section('modal-windows')
@include('users.partials.fundus-pause')
@include('users.partials.project-pause')

@if(empty(Auth::user()->projectDetail))
@include('partials.upgrade-project')
@endif

@if(empty(Auth::user()->fundusDetail))
@include('partials.upgrade-store')
@endif

@endsection