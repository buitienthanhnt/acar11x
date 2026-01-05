@extends('adminhtml.layouts.left-bar')

@section('title')
    book category
@endsection

@section('mainBody')
    <x-dashboard-chart />
    <div>
        <a class="btn btn-sm btn-info" href="{{ url('adminhtml/abook/bookcate-create') }}">create new category</a>
    </div>
    <div class='p-2'>
        {{-- $__data : dung de lay tat ca cac bien duoc truyen vao 1 blade template --}}
        {!! view('components.adminhtml.pages.blocks.tableListItem', $__data) !!}
    </div>
@endsection
