@extends('adminhtml.layouts.left-bar')

@section('title')
    edit writer
@endsection

@section('mainBody')
    <x-dashboard-chart />
    <div class="px-4">
        <div class="row">
            {!! view('components.adminhtml.formfields.formBase', [
                'method' => 'POST',
                'action' => url("adminhtml/writer/update/$writer->id"),
                'listAttributes' => $listAttributes,
            ]) !!}
        </div>
    </div>
@endsection
