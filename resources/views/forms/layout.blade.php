<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>

        <link rel="stylesheet" type="text/css" media="screen" href="{{('/vendor/jqGrid/js/themes/redmond/jquery-ui.custom.css')}}" />
        <link rel="stylesheet" type="text/css" media="screen" href="{{('/vendor/jqGrid/js/jqgrid/css/ui.jqgrid.css')}}" />
        <link href="{{('/vendor/jqGrid/js/integration/select2/4.0.4/select2.min.css')}}" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/gh/wenzhixin/multiple-select@1.2.1/multiple-select.css" rel="stylesheet" />
        <style>
            body, html {
                margin: 2px;
                padding: 0;
                height: calc(100% - 4px);
            }
            .ui-autocomplete{
                z-index: 1000;
            }
        
            .ui-jqdialog input:disabled {
                background-color: #f0f0f0;  /* Light gray background */
                color: #aaa;                /* Light gray text */
                border: 1px solid #ccc;     /* Lighter border */
                cursor: not-allowed;        /* Change cursor to indicate it's disabled */
            }
        </style>    
        @yield('custom_css')
    </head>
    <body role="application"> 
        <div class="ps-1 pe-1 w-100 h-100" id="container" style="height: 100%; width: 100%;">
            <table id="jqGrid"></table>
            <div id="jqGridPager"></div>
            @yield('content')
        </div>
    </body>
    <script src="{{('/vendor/jqGrid/js//jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{('/vendor/jqGrid/js/jqgrid/js/i18n/grid.locale-en.js')}}" type="text/javascript"></script>
    <script src="{{('/vendor/jqGrid/js/jqgrid/js/jquery.jqGrid.min.js')}}" type="text/javascript"></script>
    <script src="{{('/vendor/jqGrid/js/themes/jquery-ui.custom.min.js')}}" type="text/javascript"></script>	
    <script src="{{('/vendor/jqGrid/js/integration/select2/4.0.4/select2.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/gh/wenzhixin/multiple-select@1.2.1/multiple-select.js"></script>
    <script type="text/javascript">
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        const settingCenterDialog = ($form) => {
            var $dialog = $form.closest('.ui-jqdialog');
            var windowWidth = $(window).width();
            var windowHeight = $(window).height();
            var dialogWidth = $dialog.width();
            var dialogHeight = $dialog.height();
            
            // Calculate the left and top position to center the dialog
            var left = (windowWidth - dialogWidth) / 2;
            var top = (windowHeight - dialogHeight) / 2;

            // Set the dialog position
            $dialog.css({
                'left': left + 'px',
                'top': top + 'px'
            });
        };

        function resizeGrid() {
            var newWidth = $('#container').width() - 4; // Adjust as needed
            var newHeight = $('#container').height() - 40 - 40 - 40 - 30 - 40 - 10; // Adjust based on your layout (header/footer/etc)

            $("#jqGrid").jqGrid('setGridWidth', newWidth);
            $("#jqGrid").jqGrid('setGridHeight', newHeight);
        }
        $(window).on('resize', function () {
            resizeGrid();
        });
    </script>
    @yield('custom_js')
</html>
