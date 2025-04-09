@extends('forms.layout')
@section('title', 'Assign Child Form To Parent Form')
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
        url: "/forms/relation",
        mtype: "GET",
        datatype: "json",
        colModel: [
            { label: 'Id', name: 'id', hidden: true},
            { label: 'Form', name: 'form_definition_name', width: 150, editable: false, search: true},
            { label: 'FormDefinitionId', name: 'form_definition_id', hidden: true},
            { label: 'ParentFormDefintionId', name: 'parent_form_id', hidden: true},
            { label: 'Parent Form', name: 'parent_form_name', width: 150, editable: true, edittype: 'select', search: true, 
                    editoptions: {                            
                        dataInit: function (element) {
                            element.name = 'parent_form_name';
                            window.setTimeout(function () {
                                const rowId = $(element).closest('table').find('#id_g').val();
                                const rowData = $('#jqGrid').jqGrid('getRowData', rowId);
                                $select = $(element);
                                $select.select2({
                                    ajax: {
                                        dataType: 'json',
                                        url: "/forms/relation/getForms",
                                        data: function (params) {
                                            var query = {
                                                id: rowData.id,
                                                name: params.term,
                                            };
                                            // Query parameters will be ?search=[term]&page=[page]
                                            return query;
                                        },
                                        type: 'GET',
                                        processResults: function (data, params) {
                                            return {
                                                results: data,
                                                pagination: {
                                                    more: false
                                                }
                                            };
                                        },
                                    },
                                    minimumInputLength: 2,
                                    onSelect: function()
                                    {
                                        jQuery(this).trigger('change'); 
                                    },
                                });

                                $select.on('select2:select', function (e) {
                                    const selected = e.params.data;

                                    // Set the value manually for jqGrid
                                    $select.val(selected.id).trigger('change'); // this updates the <select> value
                                });
                            }, 100);

                        },
                    }
            },
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
        sortname: 'id',
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
        caption: "Assign Child Form To Parent Form",
        multiselect: true,
        pager: "#jqGridPager",
        editurl: '/forms/relation',
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
        { edit: true,  add: false,  del: true,  search: false, refresh: false, view: false, position: "left", cloneToTop: true },
        settingEditDialog,         // options for the Edit Dialog
        {},
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