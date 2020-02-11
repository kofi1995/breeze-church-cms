<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupsCollection;
use App\Http\Resources\GroupResource;
use App\Imports\GroupImport;
use App\Models\Group;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GroupsController extends Controller
{

    /**
     * @return GroupsCollection
     */
    public function index()
    {
        $groups = Group::with('people')->get();
        return new GroupsCollection($groups);
    }


    public function create(Request $request)
    {
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_name'    => 'required|string|max:255',
        ]);

        $group = Group::create($request->all());

        return (new GroupResource($group))
            ->response()
            ->setStatusCode(201);
    }


    /**
     * @param $id
     * @return GroupResource
     */
    public function show($id)
    {
        return new GroupResource(Group::findOrFail($id));
    }


    public function edit($id)
    {
        //
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $request->validate([
            'group_name'    => 'required|string|max:255',
        ]);

        $group->update($request->all());

        return response()->json(null, 204);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();

        return response()->json(null, 204);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkUpload(Request $request){
        $request->validate([
            'file' => 'required|file|mimetypes:application/vnd.ms-excel,text/plain,text/csv,text/tsv',
        ]);
        $error = null;

        try{
            Excel::import(
                new GroupImport,
                $request->file('file'),
                null,
                \Maatwebsite\Excel\Excel::CSV
            );
        }
        catch(\Exception $e) {
                $error = $e->getMessage();
        }

       if($error) {
           return response()->json(["message" => $error], 400);
       }

        return response()->json(["message"=>"Success"], 201);
    }
}
