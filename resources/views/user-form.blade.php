@extends('layouts.app')
@section('custom_css')
<link rel="stylesheet" type="text/css" media="screen" href="{{secure_asset('/vendor/jquery-ui-1.14.1.custom/jquery-ui.min.css')}}" />
<link rel="stylesheet" type="text/css" media="screen" href="{{secure_asset('/vendor/jqGrid/css/ui.jqgrid.css')}}" />
@endsection
@section('content')
<table id="jqGrid"></table>
<div id="jqGridPager"></div>
@endsection
@section('custom_js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{secure_asset('/vendor/jqGrid/js/i18n/grid.locale-en.js')}}" type="text/javascript"></script>
<script src="{{secure_asset('/vendor/jqGrid/js/jquery.jqGrid.min.js')}}" type="text/javascript"></script>
<script type="text/javascript"> 
    $(document).ready(function () {
        $("#jqGrid").jqGrid({
            url: 'http://trirand.com/blog/phpjqgrid/examples/jsonp/getjsonp.php?callback=?&qwery=longorders',
            mtype: "GET",
            datatype: "jsonp",
             colModel: [
                { label: 'OrderID', name: 'OrderID', key: true, width: 75 },
                { label: 'Customer ID', name: 'CustomerID', width: 150 },
                { label: 'Order Date', name: 'OrderDate', width: 150,
				formatter : 'date', formatoptions: { srcformat : 'Y-m-d H:i:s', newformat :'ShortDate'}},
                { label: 'Freight', name: 'Freight', width: 150 },
                { label:'Ship Name', name: 'ShipName', width: 150 }
            ],
			viewrecords: true,
            width: 780,
            height: 250,
            rowNum: 20,
            pager: "#jqGridPager"
         });
     });
 
   </script>
@endsection