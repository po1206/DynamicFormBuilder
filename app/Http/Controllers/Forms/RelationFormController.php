<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormDefinition;

class RelationFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $page = $request->get('page', 1);
            $limit = $request->get('rows', 10);
            $sortBy = $request->get('sidx', 'id');
            $sortOrder = $request->get('sord', 'asc');
        
            // Map UI field names to actual DB columns
            $sortMap = [
                'form_definition_name' => 'form_definitions.name',
                'parent_form_name' => 'parents.name',
                'id' => 'form_definitions.id'
            ];
        
            $sortByDb = $sortMap[$sortBy] ?? 'form_definitions.id';
        
            // Base query with join for sorting
            $query = FormDefinition::leftJoin('form_definitions as parents', 'form_definitions.parent_form_id', '=', 'parents.id')
                ->select(
                    'form_definitions.*',
                    'parents.name as parent_form_name_alias'
                );
        
            // Optional filtering
            $parent_form_name = $request->input('parent_form_name');
            $form_definition_name = $request->input('form_definition_name');
            if (!empty($parent_form_name)) {
                $query->where('parents.name', 'LIKE', '%' . $parent_form_name . '%');
            }
            if (!empty($form_definition_name)) {
                $query->where('form_definitions.name', 'LIKE', '%' . $form_definition_name . '%');
            }
        
            // Pagination
            $count = $query->count();
            $totalPages = $count > 0 ? ceil($count / $limit) : 0;
            $page = min($page, $totalPages);
            $offset = ($page - 1) * $limit;
        
            // Fetch rows
            $rows = $query
                ->orderBy($sortByDb, $sortOrder)
                ->skip($offset)
                ->take($limit)
                ->get()
                ->map(function ($formDef) {
                    return [
                        'id' => $formDef->id,
                        'form_definition_name' => $formDef->name,
                        'parent_form_id' => $formDef->parent_form_id,
                        'parent_form_name' => $formDef->parent_form_name_alias,
                    ];
                });
        
            // Response in jqGrid expected format
            return response()->json([
                'page' => $page,
                'total' => $totalPages,
                'records' => $count,
                'rows' => $rows,
            ]);

        }
        return view('forms.relation');
    }

    // jqGrid sends POST for create and edit (with oper=add/edit)
    public function store(Request $request)
    {
        if ($request->input('oper') === 'edit') {
            $formDefinition = FormDefinition::findOrFail($request->input('id'));
            $parent_form_id = $request->input('parent_form_name');
            if (!is_null($parent_form_id)) {
                $parentForm = FormDefinition::findOrFail($parent_form_id);
                if ($parentForm->parent_form_id == $request->input('id')) {
                    return response()->json(['error' => 'The target is your child!'], 400);
                }
            }
            $formDefinition->parent_form_id = $parent_form_id;
            $formDefinition->save();
        } elseif ($request->input('oper') === 'del') {
            $id = $request->input('id');
            FormDefinition::destroy($id);
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Invalid oper'], 400);
        }
        
        return response()->json(['success' => true]);
    }


    // (Optional) not used by jqGrid but included for REST completeness
    public function show($id, Request $request) {
        if ($id == "getForms") {
            // Fetch forms and map them to the desired structure
            $name = $request->get('name', "");
            $id = $request->get('id', 1);

            $forms = FormDefinition::where('name', 'LIKE', "%".$name."%")->where('id', '<>', $id)->get()
                ->map(function ($form) {
                    return [
                        'id' => $form->id,
                        'text' => $form->name
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
