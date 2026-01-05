@extends('adminhtml.layouts.left-bar')

@section('title')
    create category
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
                'action' => $action ?? url('' . 'adminhtml/ahome/home-register'),
                'listAttributes' => $listAttributes,
            ]) !!}

        </div>
    </div>
@endsection
