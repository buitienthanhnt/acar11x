<style>
    #holder>img {
        width: 120px;
        height: 120px !important;
        border-radius: 100%;
        object-fit: cover;
    }

    .img-galleries>img {
        margin: 5px;
        border-radius: 10px;
    }
</style>
@include('components.adminhtml.formfields.label', [
    'field' => $field,
])
<div class="input-group">
    <span class="input-group-btn">
        <a id="lfm-{{ $field['key'] }}" data-input="id-{{ $field['key'] }}" data-preview="holder-{{ $field['key'] }}"
            class="btn btn-primary">
            <i class="fa fa-picture-o"></i> Choose
        </a>
    </span>
    <input id="id-{{ $field['key'] }}" class="form-control" type="text" name="{{ $field['key'] }}"
        onchange="onChangeImages(this.value)"
        @isset($field['value']) value="{{ $field['value'] }}"@endisset>
</div>
<div id="holder-{{ $field['key'] }}" class="img-galleries"
    style="margin-top:15px;
        max-height: 120px;
        position: relative;
        display: inline-block;
        border-radius: 1rem;
        overflow: hidden;">
    @isset($field['value'])
        @foreach (explode(',', $field['value']) as $path)
            <img src="{{ $path }}" alt="" class="img-galleries"
                style="margin-top:15px;
        max-height: 120px;
        position: relative;
        display: inline-block;
        border-radius: 1rem;
        overflow: hidden;">
        @endforeach
    @endisset
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#lfm-{{ $field['key'] }}").filemanager('Images', {
            prefix: filemanager_url_base
        });
    });

    function onChangeImages(value) {}
</script>
