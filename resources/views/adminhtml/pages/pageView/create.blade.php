@extends('adminhtml.layouts.left-bar')

@section('title')
    create new page
@endsection

@section('afCss')
@endsection

@section('formBaseContentRight')
    <div data-bind='component: "pageContentId"'></div>
    <script type="text/javascript">
        var defaultSupportFields = JSON.parse(`{!! $defaultSupportFields !!}`);
        var customFields = JSON.parse(`{!! $customFields !!}`);

        var pageContentString = `{!! $contentFields ?? json_encode([]) !!}`; // render json string object
        var pageContentFields = JSON.parse(pageContentString); // should pass param for Component PageContent 

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
        {!! view('components.adminhtml.formfields.formBase', [
            'method' => 'POST',
            'action' => url('adminhtml/page/register'),
            'listAttributes' => $listAttributes,
            'rightContent' => '',
        ]) !!}
    </div>
@endsection
