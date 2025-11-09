 {!! view('components.adminhtml.formfields.formValidate') !!}
 <form
     @isset($method)
		method="{{ $method }}"
		@if ($method !== 'GET')
			enctype="multipart/form-data"
		@endif
	@else
		method="GET"
	@endisset
     action="{{ $action }}">

     @isset($method)
         @if (in_array($method, ['POST', 'PUT', 'DELETE']))
             @csrf
         @endif
     @endisset
     <div class="row d-flex">
         <div class="p-1 col-md-5">
             @foreach ($listAttributes as $field)
                 @switch($field['type'])
                     @case(\App\Models\Types\FormInterface::TYPE_CHECKBOX)
                         @include('components.adminhtml.formfields.checkbox', [
                             'field' => $field,
                         ])
                     @break

                     @case(\App\Models\Types\FormInterface::TYPE_TEXTAREA)
                         @include('components.adminhtml.formfields.textarea', [
                             'field' => $field,
                         ])
                     @break

                     @case(\App\Models\Types\FormInterface::TYPE_FILE)
                         @include('components.adminhtml.formfields.file', [
                             'field' => $field,
                         ])
                     @break

                     @case(\App\Models\Types\FormInterface::TYPE_EMAIL)
                     @case(\App\Models\Types\FormInterface::TYPE_DATE)

                     @case(\App\Models\Types\FormInterface::TYPE_PHONE)
                     @case(\App\Models\Types\FormInterface::TYPE_TEXT)
                         @include('components.adminhtml.formfields.textField', [
                             'field' => $field,
                         ])
                     @break

                     @case(\App\Models\Types\FormInterface::TYPE_TEXTEDITOR)
                         {!! view('components.adminhtml.formfields.textEditor', [
                             'field' => $field,
                         ]) !!}
                     @break

                     @case(\App\Models\Types\FormInterface::TYPE_IMAGE_CHOOSE)
                         {!! view('components.adminhtml.formfields.imageChoose', [
                             'field' => $field,
                         ]) !!}
                     @break

                     @case(\App\Models\Types\FormInterface::TYPE_SELECT)
                         {!! view('components.adminhtml.formfields.select', [
                             'field' => $field,
                         ]) !!}
                     @break

                     @case(\App\Models\Types\FormInterface::TYPE_MULTISELECT)
                         {!! view('components.adminhtml.formfields.multiSelect', [
                             'field' => $field,
                         ]) !!}
                     @break

                     @default
                 @endswitch
             @endforeach
             <div class="justify-content-center d-flex">
                 <button type="submit" class="btn btn-primary col-md-4">Submit</button>
             </div>
         </div>
         <div class="col-md-7">
            @yield('formBaseContentRight')
         </div>
     </div>
 </form>
