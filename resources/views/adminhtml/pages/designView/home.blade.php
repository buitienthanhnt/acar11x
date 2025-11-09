@extends('adminhtml.layouts.left-bar')

@section('title')
    create new page
@endsection

@section('afCss')
@endsection

@section('mainBody')
    <x-dashboard-chart />
    <div class="px-4">
        <a class="btn btn-info" href="{{ url('/adminhtml/design/page-setup') }}">design home page construct</a>
    </div>
@endsection