@extends('adminhtml.layouts.left-bar')

@section('title')
    edit the page
@endsection

@section('formBaseContentRight')
    <div data-bind='component: "pageContentId"'></div>
    <script type="text/javascript">
        var defaultSupportFields = JSON.parse(`{!! $defaultSupportFields !!}`);
        var customFields = JSON.parse({{ Js::from($customFields) }});

        // lưu ý render json để tránh gặp phải lỗi cú pháp: Js::from (https://laravel.com/docs/12.x/blade#rendering-json)
        // render json string object(input json string)
        var pageContentFields = JSON.parse({{ Js::from($contentFields) }}); // should pass param for Component PageContent 

        // https://developer.mozilla.org/en-US/docs/Web/API/Document/DOMContentLoaded_event == $(document).ready()
        // khoi tao knockoutJs component view model.
        // jquery: $ co the dung trong: requirejs binh thuong vi no la global var.
        // vị trí tương đối của thư mục bắt đầu từ vị trí file: requireMain.js trong data-main.
        // requirejs([
        //     'knockout',
        // ], function(ko, ) {
        //     ko.components.register('message-editor', {
        //         viewModel: {
        //             require: 'knockElement/viewModel/product'
        //         },
        //         template: {
        //             require: 'text!knockElement/viewTemplate/product.html'
        //         }
        //     });
        //     // ko.applyBindings();
        // });
        requirejs(['knockElement/components/pageContent', ],
            function(PageContent, TextInput) {}
        );
    </script>
@endsection

@section('mainBody')
    <x-dashboard-chart />
    <div class="px-4">
        <div class="row">
            {!! view('components.adminhtml.formfields.formBase', $__data) !!}
        </div>
    </div>
@endsection
