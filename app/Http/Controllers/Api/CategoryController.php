<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response_category = DB::select('select * from tbl_category');
        $res = $this->sendResponse($response_category, "udah bener kok gaes");
        //return $response_category;
        return $res;
    }

    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];
        return response()->json($response, 200);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id = $request->id;
        $category = $request->category;
        //DB::insert("INSERT INTO tbl_category (category) VALUES ('".$category."'); ");
        DB::update("UPDATE tbl_category SET category = '" . $category . "' WHERE id=" . $id . ";");
        $response = [
            'success' => true,
            'message' => 'success'
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $update_category = $request->update_category;
        //DB::update("UPDATE tbl_category SET category = '".$category."' WHERE id=".$id.";");
        dd($update_category);
        dd("UPDATE tbl_category SET category = 'tes' WHERE id=" . $id . ";");
        $response = [
            'success' => true,
            'message' => 'success'
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::delete(("DELETE FROM tbl_category WHERE id=" . $id . ";"));
        $response = [
            'success' => true,
            'message' => 'success'
        ];
        return response()->json($response, 200);
    }
}
