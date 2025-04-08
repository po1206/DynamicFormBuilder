<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormField;
use App\Models\FormDefinition;

class MaintainFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $page = $request->input('page', 1);
            $limit = $request->input('rows', 10);
            $sidx = $request->input('sidx', 'id');
            $sord = $request->input('sord', 'asc');

            $name = $request->input('name');
            $fieldType = $request->input('field_type');
            $formDefinitionName = $request->input('form_definition_name');

            $query = FormField::query()
                ->select('form_fields.*') // Explicitly select fields from the main table
                ->leftJoin('form_definitions', 'form_fields.form_definition_id', '=', 'form_definitions.id');

            // Apply filters
            if (!empty($name)) {
                $query->where('form_fields.name', 'like', '%' . $name . '%');
            }

            if (!empty($fieldType)) {
                $query->where('form_fields.field_type', 'like', '%' . $fieldType . '%');
            }

            if (!empty($formDefinitionName)) {
                $query->where('form_definitions.name', 'like', '%' . $formDefinitionName . '%');
            }


            // Apply ordering
            if ($sidx == "form_definition_name"){
                $sidx = "form_definition_id";
            }

            $query->orderBy($sidx, $sord);

            $count = $query->count();
            $fields = $query->skip(($page - 1) * $limit)->take($limit)->get();

            $formattedFields = $fields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'name' => $field->name,
                    'field_type' => $field->field_type,
                    'validation_rules' => $field->validation_rules,
                    'error_message' => $field->error_message,
                    'order' => $field->order,
                    'form_definition_name' => optional($field->formDefinition)->name, // Safe access
                ];
            });

            return response()->json([
                'page' => $page,
                'total' => ceil($count / $limit),
                'records' => $count,
                'rows' => $formattedFields
            ]);

        }
        return view('forms.maintain');
    }

    // jqGrid sends POST for create and edit (with oper=add/edit)
    public function store(Request $request)
    {
        if ($request->input('oper') === 'add') {
            $formField = new FormField();
        } elseif ($request->input('oper') === 'edit') {
            $formField = FormField::findOrFail($request->input('id'));
        } elseif ($request->input('oper') === 'del') {
            $id = $request->input('id');
            FormField::destroy($id);
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Invalid oper'], 400);
        }
        
        $form_definition_name = $request->input('form_definition_name');
        if ($request->input('oper') == 'add') {
            if (is_numeric($form_definition_name) && (int)$form_definition_name == $form_definition_name) {
                $formField->form_definition_id = (int)$form_definition_name;
            } else {
                // Create new FormDefinition
                $formDefinition = FormDefinition::where('name', $form_definition_name)->first();
                if (is_null($formDefinition)){
                    $formDefinition = new FormDefinition();
                    $formDefinition->name = $form_definition_name;
                    $formDefinition->save();    
                }
                $formField->form_definition_id = $formDefinition->id;
            }
        } 
        $duplicate = FormField::where('form_definition_id', $formField->form_definition_id)->where('name', $request->input('name'))->first();
        if (is_null($duplicate) == false) {
            if ($request->input('oper') == 'add' || $duplicate->id != $formField->id) {
                return response()->json(['error' => 'Duplicate Field'], 400);
            }
        }
        $formField->name = $request->input('name');
        $formField->field_type = $request->input('field_type');
        $formField->validation_rules = $request->input('validation_rules');
        $formField->error_message = $request->input('error_message');
        $formField->save();      
        return response()->json(['success' => true]);
    }


    // (Optional) not used by jqGrid but included for REST completeness
    public function show($id, Request $request) {
        if ($id == "getForms") {
            $terms = $request->input('term');
            // Fetch forms and map them to the desired structure
            $forms = FormDefinition::where('name', 'LIKE', '%' . $terms . '%')
                ->get()
                ->map(function ($form) {
                    return [
                        'id' => $form->id,
                        'label' => $form->name,
                        'value' => $form->name
                    ];
                })
                ->values() 
                ->toArray(); // Convert collection to array
            return response()->json($forms);
        }
    }

    public function create() {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy(Request $request, $id = null) {}
}
