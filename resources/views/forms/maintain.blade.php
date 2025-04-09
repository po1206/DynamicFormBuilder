@extends('forms.layout')
@section('title', 'Maintain Form')
@section('content')
@endsection
@section('custom_js')
<script type="text/javascript">     
    $(document).ready(function () {
        $.jgrid.nav.addtext = "Add";
        $.jgrid.nav.edittext = "Edit";
        
        const settingEditDialog = {
            editCaption: "The Edit Dialog",
            recreateForm: true,

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
            url: "/forms/maintain",
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
                                            url: '/forms/maintain/getForms',
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
                            }, 700);
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
                        var editButton = '<a class="ui-custom-icon ui-icon ui-icon-pencil" title="Edit this row" href="javascript:void(0);" onclick="jQuery(this).dblclick();"></a>';
                        var deleteButton = '<a class="ui-custom-icon ui-icon ui-icon-trash" title="Delete this row" href="javascript:void(0);" onclick="jQuery(\'#jqGrid\').resetSelection(); jQuery(\'#jqGrid\').setSelection(' + rowObject.id + '); jQuery(\'#del_jqGrid\').click(); "></a>';
                        
                        // Return the HTML template that includes the action buttons
                        return editButton + ' '+ deleteButton ;
                    },
                },
            ],
            sortname: 'form_definition_id',
            sortorder: 'asc',
            width: '100%',
            height: $('#container').height() - 40 - 40 - 40 - 30 - 40 - 10,
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
            editurl: '/forms/maintain',
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
        });
        $('#jqGrid').jqGrid('filterToolbar');
        $('#jqGrid').navGrid('#jqGridPager',
            { edit: true, add: true, del: true, search: false, refresh: false, view: false, position: "left", cloneToTop: true },
            settingEditDialog,         // options for the Edit Dialog
            {   // options for the Add Dialog
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
                onclickSubmit: function(params, postdata) {
                    return {
                        _token: csrfToken,
                    };
                },
                errorTextFormat: function (data) {
                    return 'Error: ' + data.responseText
                }
        });
    });
</script>
@endsection