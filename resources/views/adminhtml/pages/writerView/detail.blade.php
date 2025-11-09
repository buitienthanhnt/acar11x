@extends('adminhtml.layouts.left-bar')

@section('title')
    detail writer
@endsection

@section('mainBody')
    <x-dashboard-chart />
    <div class="p-4">
        <h3>Thông tin tác giả:</h3>

        <div class="col-md-6"
            style="background-color: rgb(187, 158, 219); padding: 16px; border-radius: 16px; box-shadow: 15px 15px 10px rgba(0, 0, 0, 0.5);">
            <a href='{{ url("adminhtml/writer/edit/$writer->id") }}' class="position-absolute" style="top: 10px; right: 10px;">
                <i class="material-icons"
                    style="font-size: 36px; color: #da1cba">edit</i>
            </a>
            <div class="d-flex gap-8">
                <div>
                    <p class="text-primary text-2xl font-bold">
                        {{ __('attr.name') }}: {{ $writer->{\App\Models\Types\WriterInterface::NAME} }}
                    </p>
                    <p class="text-dark font-bold text-xl underline">{{ 'email' }}:
                        {{ $writer->{\App\Models\Types\WriterInterface::EMAIL} }}</p>
                    <p></p>
                    <p class="text-dark font-bold text-xl underline">{{ 'bút danh' }}:
                        {{ $writer->{\App\Models\Types\WriterInterface::ALIAS} }}</p>
                    <p></p>
                    <p class="text-dark font-bold text-xl">{{ 'trạng thái' }}:
                        {{ $writer->{\App\Models\Types\WriterInterface::ACTIVE} }}</p>
                    <p></p>
                </div>
                @if ($writer->{\App\Models\Types\WriterInterface::IMAGE_PATH})
                    <img src="{{ $writer->{\App\Models\Types\WriterInterface::IMAGE_PATH} }}" alt=""
                        width="200px" height="200px" class="rounded-circle">
                @endif
            </div>

            <div class="d-flex justify-content-between">
                <p class="text-dark font-bold text-2xl">{{ 'địa chỉ' }}:
                    {{ $writer->{\App\Models\Types\WriterInterface::ADDRESS} }}</p>

                <p class="text-dark font-bold text-2xl" style="text-decoration: underline">{{ 'sdt' }}:
                    {{ $writer->{\App\Models\Types\WriterInterface::PHONE} }}</p>
            </div>

            <div class="">
                <p class="text-info font-bold text-lg" style="text-decoration: underline">{{ 'ngày sinh' }}:
                    {{ $writer->{\App\Models\Types\WriterInterface::DATE_OF_BIRTH} }}</p>

                <p class="font-bold text-lg text-success">{{ 'mô tả' }}:
                    {{ $writer->{\App\Models\Types\WriterInterface::DESCRIPTION} }}</p>
            </div>
        </div>
    </div>
@endsection
