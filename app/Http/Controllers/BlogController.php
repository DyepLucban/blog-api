<?php

namespace App\Http\Controllers;

use App\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::all();

        return response()->json([
            'data' => $blogs,
            'status' => 200
        ]);
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

        $blog = Blog::create([
            'title' => $request->input('title'),
            'image' => $request->input('image'),
            'content' => $request->input('content'),
        ]);

        if ($blog) {
            return response()->json([
                'message' => 'Blog Successfully Posted!',
                'status' => 200,
            ]);
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
        $specificBlog = Blog::find($id);

        return response()->json([
            'data' => $specificBlog,
        ]);
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
        $specificBlog = Blog::where('id', $id)->first();

        if ($specificBlog) {
            $specificBlog->title = $request->input('title');
            $specificBlog->image = $request->input('image');
            $specificBlog->content = $request->input('content');
            $specificBlog->save();
        }

        return response()->json([
            'message' => 'Blog Successfully updated',
            'status' => 200,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $specificBlog = Blog::where('id', $id)->first();

        if ($specificBlog) {
            $specificBlog->delete();
        }

        return response()->json([
            'message' => 'Blog Successfully Deleted!',
            'status' => 200,
        ]);
    }
}
