<?php

namespace App\Http\Controllers;

use App\Exceptions\UploadValidationFailedException;
use App\Imports\PeopleImport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Resources\PeopleCollection;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Maatwebsite\Excel\Facades\Excel;

class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $people = Person::get();

        return new PeopleCollection($people);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|max:255',
            'last_name'     => 'required|max:255',
            'email_address' => 'required|email',
            'status'        => ['required', Rule::in(['active', 'archived'])],
        ]);

        $person = Person::create($request->all());

        return (new PersonResource($person))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new PersonResource(Person::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $person = Person::findOrFail($id);

        $request->validate([
            'first_name'    => 'required_without_all:last_name,email_address,status,group_id|max:255',
            'last_name'     => 'required_without_all:first_name,email_address,status,group_id|max:255',
            'email_address' => 'required_without_all:first_name,last_name,status,group_id|email',
            'status'        => ['required_without_all:first_name,last_name,email_address,group_id', Rule::in(['active', 'archived'])],
            'group_id'      => 'required_without_all:first_name,last_name,email_address,status|integer|exists:groups,id',
        ]);


        $person->update($request->all());

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        $person->delete();

        return response()->json(null, 204);
    }

    /***
     * Bulk upload
     */
    public function bulkUpload(Request $request){
       $request->validate([
            'file' => 'required|file|mimetypes:application/vnd.ms-excel,text/plain,text/csv,text/tsv',
       ]);
        $error = null;
       try {
           Excel::import(
               new PeopleImport,
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
