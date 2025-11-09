@extends('adminhtml.layouts.left-bar')

@section('title')
    setup page design
@endsection

@section('afCss')
@endsection

@section('mainBody')
    <x-dashboard-chart />
    <div class="px-2 py-4">
        <div data-bind='component: "pageSetupId"'></div>

        <script type="text/javascript">
            // https://developer.mozilla.org/en-US/docs/Web/API/Document/DOMContentLoaded_event == $(document).ready()
            // khoi tao knockoutJs component view model.
            // jquery: $ co the dung trong: requirejs binh thuong vi no la global var.
            // vị trí tương đối của thư mục bắt đầu từ vị trí file: requireMain.js trong data-main.
            requirejs(['knockElement/components/designPage', ],
                function(designPage,) {}
            );
        </script>
    </div>
@endsection
