<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="/source/adminhtml/js/fontawesome.js"></script>
    {{-- import sweetalert2 library: https://sweetalert2.github.io/#usage --}}
    <script src="/source/adminhtml/js/sweetalert2@11.js"></script>
    {{-- import jquery library --}}
    <script src="/source/adminhtml/js/jquery-3.7.1.min.js"></script>
    <script src="/source/adminhtml/js/jquery-ui/jquery-ui.min.js"></script>
    {{-- import select2 library https://select2.org/ --}}
    <script src="/source/adminhtml/js/select2.min.js"></script>
    {{-- insert bootstrap support --}}
    <script src="/source/adminhtml/js/popper.min.js"></script>
    <script src="/source/adminhtml/js/bootstrap.min.js"></script>
    <script src="/source/adminhtml/js/tinymce/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
    {{-- insert filemanager support --}}
    <script src="/vendor/laravel-filemanager/js/filemanager.min.js"></script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    {{-- insert knockoutJs library --}}
    <script src="/source/adminhtml/js/knockout.js"></script>
    {{-- insert underScore library --}}
    <script src="/source/adminhtml/js/underscore.js"></script>
    <script src="/source/adminhtml/js/require.js" data-main="/source/adminhtml/js/requireMain"></script>

    <style>
        #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
        #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; background-color: violet }
        #sortable li span { position: absolute; margin-left: -1.3em; }
        </style>
</head>

<body>
    <div>
        <h3>demo for import knockoutJs library.</h3>
        <script>
            require([
                'text'
            ], function(text) {
                'use strict';
                console.log(text);
            });
        </script>
    </div>

    <script>
        $( function() {
          $( "#sortable" ).sortable();
        } );
        </script>

    <ul id="sortable">
        <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 1</li>
        <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 2</li>
        <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 3</li>
        <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 4</li>
        <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 5</li>
        <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 6</li>
        <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 7</li>
      </ul>

	 {{-- <script src="/source/adminhtml/js/core/popper.min.js"></script> --}}
 {{-- <script src="/source/adminhtml/js/core/bootstrap.min.js"></script> --}}
 {{-- <script src="/source/adminhtml/js/plugins/perfect-scrollbar.min.js"></script> --}}
 {{-- insert requireJs in foot body tag will run well however to use <script> tag in body front of insert, developer must use document.ready --}}


 <!-- Github buttons -->
 {{-- <script async defer src="https://buttons.github.io/buttons.js"></script> --}}
 <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->

</body>

</html>
