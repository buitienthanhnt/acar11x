@extends('adminhtml.layouts.base')

@section('mainBody')
    <div class="container p-4">
        <h2 class="text-success">Trân trọng cảm ơn quý khách hàng đã đặt phòng!</h2>
        <h4 class="font-weight-bold">Thông tin hóa đơn: {{ $order->increment_id }}</h4>
        <div class="row gap-2 p-2">
            <div class="col order-1 border border-dark rounded p-2">
                <h4 class="font-weight-bold">Thông tin khách hàng:</h4>
                <h5 class="font-weight-normal " style="margin-left: 5px">Họ và tên: {{ $order->detail->name }}</h5>
                <h5 class="font-weight-normal " style="margin-left: 5px">Email: {{ $order->detail->email }}</h5>
                <h5 class="font-weight-normal " style="margin-left: 5px">Số điện thoại: {{ $order->detail->phone }}</h5>
            </div>
            <div class="col order-1 border border-dark rounded p-2">
                <h4 class="font-weight-bold">Khách sạn: {{ $order->home->name }}</h4>
                <h5 class="font-weight-normal " style="margin-left: 5px">Địa chỉ: {{ $order->home->district }}</h5>
                <h5 class="font-weight-normal " style="margin-left: 5px">Liên hệ: 0702032201</h5>
            </div>
            <div class="col order-12 border border-dark rounded p-2">
                <h5 class="font-weight-bold">Thông tin phòng: {{ $order->room->title }}</h5>
                <h5 class="font-weight-bold">Thời gian lưu trú:</h5>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($order->selected_time as $time)
                        <h5 style="font-weight: 600; margin-left: 5px">
                            {{ $time }}</h5>
                    @endforeach
                </div>
            </div>

        </div>
        <div class="mt-4">
            <h4 class="font-weight-bold">Quý khách có thắc mắc có thể liên hệ với khách sạn qua sdt: 0702032201</h4>
        </div>
        {{-- <p>Cảm ơn bạn đã đăng ký tài khoản.</p>
        <p>Trân trọng,</p>
        <p>Đội ngũ phát triển.</p> --}}
        {{-- <h3>{{ $order->{Thanhnt\Ahomeglobal\Models\Types\OrderInterface::ID} }}</h3> --}}
    </div>
@endsection
