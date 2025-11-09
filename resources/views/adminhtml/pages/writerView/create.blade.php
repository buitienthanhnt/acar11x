@extends('adminhtml.layouts.left-bar')

@section('title')
    register writer
@endsection

@section('mainBody')
    <x-dashboard-chart />
    <div class="px-4">
        <div class="row">
            {!! view('components.adminhtml.formfields.formBase', [
                'method' => 'POST',
                'action' => route('admin_writer_create'),
                'listAttributes' => $listAttributes,
            ]) !!}
        </div>
    </div>
@endsection
