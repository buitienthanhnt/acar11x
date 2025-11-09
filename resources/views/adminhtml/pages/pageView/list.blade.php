@extends('adminhtml.layouts.left-bar')

@section('title')
    page list
@endsection

@section('mainBody')
    <x-dashboard-chart />
    <div>
        <a class="btn btn-sm btn-info" href="{{ url('adminhtml/page/create') }}">create new page</a>
    </div>
    <div class='p-2'>
        {!! view('components.adminhtml.pages.blocks.tableSearchField', ['title' => 'Danh sách bài viết:']) !!}
        {{-- $__data : dung de lay tat ca cac bien duoc truyen vao 1 blade template --}}
        {!! view('components.adminhtml.pages.blocks.tableListItem', $__data) !!}
    </div>
@endsection
