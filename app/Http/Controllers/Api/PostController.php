<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = Post::all();
        return response()->json($post,200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message =[
            'required' => 'The :attribute filed is required'
        ];
        $validator = validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required'
        ], $message);
        if($validator->fails()){
            return response()->json(['msg' => $validator->errors()],200);

        }else{
            $post = Post::create([
                'title' => $request->title,
                'description' => $request->description
    
            ]);
            return response()->json(['post' => $post, 'msg' => 'Data Created Successfully'],200);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::findorfail($id);
        return response()->json($post,200);
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
        
        $post = Post::findorfail($id);
        $post->update([
            'title' => $request->title,
            'description' => $request->description
        ]);
        return response()->json(['msg' => 'update success'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findorfail($id);
        $post->delete();
        return response()->json(['deletePost' => $post,'msg' => 'deleted success'],200);
    }
}
