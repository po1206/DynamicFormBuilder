@extends('layouts.app')
@section('custom_css')
<link rel="stylesheet" type="text/css" media="screen" href="{{('/vendor/jqGrid/js/themes/redmond/jquery-ui.custom.css')}}" />
<link rel="stylesheet" type="text/css" media="screen" href="{{('/vendor/jqGrid/js/jqgrid/css/ui.jqgrid.css')}}" />
<link href="http://cdn.jsdelivr.net/gh/wenzhixin/multiple-select@1.2.1/multiple-select.css" rel="stylesheet" />
<style>
#customToolbar {
    padding: 5px;
    border-bottom: 1px solid #ccc;
    display: none; /* Hide it by default until inserted */
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
@endsection
@section('content')
  
    <div class="ps-1 pe-1 w-100 h-100" id="container" style="height: 100%; width: 100%;">
        {{-- <div id="customToolbar">
            <button id="openDialog">Open Form Dialog</button>
        </div> --}}
        <table id="jqGrid" class=""></table>
        <div id="jqGridPager"></div>    
    </div>

<!-- Hidden Dialog -->
<div id="formDialog" title="Enter Information"  style="display:none;">
    <form id="infoForm" class="FormGrid ui-jqdialog-content ui-widget-content">
        <div class="row">
            <label for="firstName" class="col-md-4">First Name</label>
            <input type="text" id="firstName" name="firstName" class="col-md-8">    
        </div>
        <div class="row">
            <label for="lastName" class="col-md-4">Last Name</label>
            <input type="text" id="lastName" name="lastName" class="col-md-8">
        </div>
        <div class="row">
            <label for="email" class="col-md-4">Email</label>
            <input type="email" id="email" name="email" class="col-md-8">
        </div>
    </form>
</div>

@endsection
@section('custom_js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{('/vendor/jqGrid/js/jqgrid/js/i18n/grid.locale-en.js')}}" type="text/javascript"></script>
<script src="{{('/vendor/jqGrid/js/jqgrid/js/jquery.jqGrid.min.js')}}" type="text/javascript"></script>
<script src="{{('/vendor/jqGrid/js/themes/jquery-ui.custom.min.js')}}" type="text/javascript"></script>	

<script src="//cdn.jsdelivr.net/gh/wenzhixin/multiple-select@1.2.1/multiple-select.js"></script>	

<script type="text/javascript"> 
    $(document).ready(function () {
        $.jgrid.nav.addtext = "Add";
        $.jgrid.nav.edittext = "Edit";
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
        const settingEditDialog = {
            editCaption: "The Edit Dialog",
            recreateForm: true,
            //checkOnUpdate : true,
            //checkOnSubmit : true,
            beforeShowForm: function ($form) {
                settingCenterDialog($form);
                $($form[0]).find('input[name="form_definition_name"]').prop('disabled', true);
            },
            beforeSubmit : function( postdata, form , oper) {
                postdata._token = csrfToken;
                return [true,''];                
            },
            closeAfterEdit: true,
            errorTextFormat: function (data) {
                return 'Error: ' + data.responseText
            }
        };
        $("#jqGrid").jqGrid({
            url: "{{url('/forms')}}",
            mtype: "GET",
            datatype: "json",
            colModel: [
                { label: 'Form', name: 'form_definition_name', width: 150, editable: true, search: true,
                    editoptions: {                            
                        dataInit: function (element) {
                            window.setTimeout(function () {
                                $(element).autocomplete({
                                    id: 'AutoComplete',
                                    source: function(request, response){
                                        this.xhr = $.ajax({
                                            url: "{{url('/forms/getForms')}}",
                                            data: request,
                                            dataType: "json",
                                            success: function( data ) {
                                                response( data );
                                            },
                                            error: function(model, response, options) {
                                                response([]);
                                            }
                                        });
                                    },
                                    autoFocus: true,
                                    select: function (event, ui) {
                                        $(element).data('selected-id', ui.item.id); // Save the selected id
                                    },
                                });
                            }, 100);
                        },
                    }
                },
                { label: 'Field', name: 'name', width: 150, editable: true},
                { label: 'Type', name: 'field_type', width: 150, editable: true},
                { label: 'Validation', name: 'validation_rules', width: 150, editable: true, search: false},
                { label: 'Error', name: 'error_message', width: 150, editable: true, search: false},
                {
                    label: "Actions",
                    name: "actions",
                    width: 100,
                    search: false,
                    formatter: function(cellvalue, options, rowObject) {
                        // Custom HTML template for Actions (you can customize this as needed)
                        
                        var editButton = '<a class="ui-custom-icon ui-icon ui-icon-pencil" title="" href="javascript:void(0);" onclick="jQuery(this).dblclick();"></a>';
                        // var deleteButton = '<a class="ui-custom-icon ui-icon ui-icon-trash" title="Delete this row" href="javascript:void(0);" onclick="jQuery(\'#jqGrid\').resetSelection(); jQuery(\'#jqGrid\').setSelection(\''+cl+'\'); jQuery(\'#del_list_accounts\').click(); "></a>';
                        
                        // Return the HTML template that includes the action buttons
                        return editButton ;
                    },
                },
            ],
            sortname: 'form_definition_id',
            sortorder: 'asc',
            width: '100%',
            height: $('#container').height() - 40 - 40 - 40 - 30 - 10,
            autowidth: true,
			viewrecords: true,
            rowNum: 20,
            rownumbers: true, 
            autoResizing : true,
            actionicon: false,
            search: true,
            caption: "Maintain Form",
            multiselect: true,
            pager: "#jqGridPager",
            editurl: '{{ url("/forms") }}',
            autofilter : true,
            toppager: true,
            headertitles: true,
            gridComplete: function() {
                // Custom CSS to force the scrollbar to show
                $(".ui-jqgrid-bdiv").css('overflow-y', 'scroll');
                 // Delegate a click event on the edit buttons after grid is loaded
                $(".ui-jqgrid .ui-pg-div .ui-icon-pencil").on('click', function(event) {
                    event.preventDefault();
                    var rowId = $(this).closest('tr.jqgrow').attr('id');
                    $("#jqGrid").jqGrid('editGridRow', rowId, settingEditDialog);
                });
            },
            ondblClickRow: function (rowid) {
                // This will trigger the editing mode when you double-click on a row
                $("#jqGrid").jqGrid('editGridRow', rowid, settingEditDialog);
            },
            // toolbar: [true,"top"],
            // loadComplete: function () {
            //     // Make sure the toolbar exists only once
            //     if (!$('#t_jqGrid .custom-toolbar').length) {
            //         $('#t_jqGrid').append($('#customToolbar').show()); // Append manually
            //     }
            // }
        });
        // $('#jqGrid').jqGrid('filterToolbar');
        $('#jqGrid').jqGrid("inlineNav", {
            add: true,
            cancel: true,
        });
        $('#jqGrid').navGrid('#jqGridPager',
        // the buttons to appear on the toolbar of the grid
        { edit: true, add: true, del: true, search: false, refresh: false, view: false, position: "left", cloneToTop: true },
        // options for the Edit Dialog
        settingEditDialog,
        // options for the Add Dialog
        {
            closeAfterAdd: true,
            recreateForm: true,
            beforeShowForm: settingCenterDialog,
            beforeSubmit : function( postdata, form , oper) {
                var $input = $("input[name='form_definition_name']");
                var selectedId = $input.data('selected-id');
                if (selectedId) {
                    postdata.form_definition_name = selectedId; // Replace value with ID
                }
                postdata._token = csrfToken;
                return [true,''];
            },
            errorTextFormat: function (data) {
                return 'Error: ' + data.responseText
            }
        },
        // options for the Delete Dailog
        {
            beforeShowForm: settingCenterDialog,
            beforeSubmit : function( postdata, form , oper) {
                postdata._token = csrfToken;
                return [true,''];
            },
            errorTextFormat: function (data) {
                return 'Error: ' + data.responseText
            }
        });
     });
     
    //  $("#formDialog").dialog({
    //     autoOpen: false,
    //     modal: true,
    //     buttons: {
    //     "Submit": function () {
    //         const data = {
    //             firstName: $("#firstName").val(),
    //             lastName: $("#lastName").val(),
    //             email: $("#email").val()
    //         };
    //         console.log("Form Data:", data); // Handle your form logic here
    //         $(this).dialog("close");
    //     },
    //     "Cancel": function () {
    //         $(this).dialog("close");
    //     }
    //     }
    // });
    // // Open the dialog when button is clicked
    // $("#openDialog").click(function () {
    //     $("#formDialog").dialog("open");
    // });
    </script>
@endsection