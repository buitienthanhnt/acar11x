@extends('adminhtml.layouts.left-bar')

@section('title')
    create category
@endsection

@section('mainBody')
    <x-dashboard-chart></x-dashboard-chart>
    <div class="px-4">
        <div class="row">

            {!! view('components.adminhtml.formfields.formBase', [
                'method' => 'POST',
                'action' => url(App\Models\Types\CategoryInterface::ROUTE_PREFIX . '/register'),
                'listAttributes' => $listAttributes,
            ]) !!}

        </div>
    </div>
@endsection
