@extends('adminhtml.layouts.left-bar')

@section('title')
    create book
@endsection


@isset($optionAttribute)
    @section('formBaseContentRight')
        {!! view('components.adminhtml.formfields.formFieldRender', ['listAttributes' => $optionAttribute]) !!}
    @endsection
@endisset


@section('mainBody')
    <x-dashboard-chart></x-dashboard-chart>
    <div class="px-4">
        <div class="row">
            {!! view('components.adminhtml.formfields.formBase', [
                'method' => 'POST',
                'action' => $action ?? url('' . 'adminhtml/abook/register'),
                'listAttributes' => $listAttributes,
            ]) !!}

        </div>
    </div>
@endsection
