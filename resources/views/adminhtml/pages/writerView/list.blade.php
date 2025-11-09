@extends('adminhtml.layouts.left-bar')

@section('title')
    writer list
@endsection

@section('mainBody')
    <x-dashboard-chart />
    <div>
        <a href="{{ url('adminhtml/writer/create') }}" class="btn btn-info">create writer</a>
    </div>
    <div class='p-2'>
        <div class='p-2'>
            {!! view('components.adminhtml.pages.blocks.tableSearchField', ['title' => 'Danh sách tác giả:']) !!}

            {!! view('components.adminhtml.pages.blocks.tableListItem', $__data) !!}
        </div>
    </div>
@endsection
