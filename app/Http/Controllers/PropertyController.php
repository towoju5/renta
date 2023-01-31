<?php

namespace App\Http\Controllers;

use App\Models\PropertyModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validateUser = Validator::make(
            $request->all(),
            [
                'property_type'         =>  'required',
                'property_location'     =>  'required',
                'property_price'        =>  'required',
                'property_description'  =>  'required',
                'property_features'     =>  'required',
                'property_name'         =>  'required',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $addProperty = PropertyModel::create([
            'property_type'     =>  $request->property_type,
            'property_location'     =>  $request->property_location,
            'property_price'        =>  $request->property_price,
            'property_description'      =>  $request->property_description,
            'property_features'     =>  $request->property_features,
            'property_name'     =>  $request->property_name,
        ]);

        if($addProperty):
            return get_success_response(["msg" => "Property added successfully"]);
        else: 
            return get_error_response(["error" => "Unable to add property"], 400);
        endif;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $property = PropertyModel::where('id', $id)->first();
            if($property):
                return get_success_response(["msg" => $property]);
            else :
                return get_error_response(["error" => "Property not found"], 404);
            endif;
        } catch (\Throwable $th) {
            return get_error_response(["error" => $th->getMessage()], 500);
        }
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
        $validateUser = Validator::make(
            $request->all(),
            [
                'property_type'         =>  'required',
                'property_location'     =>  'required',
                'property_price'        =>  'required',
                'property_description'  =>  'required',
                'property_features'     =>  'required',
                'property_name'         =>  'required',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $property = PropertyModel::where('id', $id)->first();
        $property->property_type         =  $request->property_type;
        $property->property_location     =  $request->property_location;
        $property->property_price        =  $request->property_price;
        $property->property_description  =  $request->property_description;
        $property->property_features     =  $request->property_features;
        $property->property_name         =  $request->property_name;

        if($property->save()):
            return get_success_response(["msg" => "Property added successfully"]);
        else: 
            return get_error_response(["error" => "Unable to add property"], 400);
        endif;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
