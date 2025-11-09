@extends('adminhtml.layouts.left-bar')

@section('title')
    edit category
@endsection

@section('mainBody')
    <x-dashboard-chart />
    <div class="px-4">
        <div class="row">
            {!! view('components.adminhtml.formfields.formBase', $__data) !!}
        </div>
    </div>
@endsection
