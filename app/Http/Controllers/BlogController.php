<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\BlogRepositoryInterface;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    private $blogRepository;

    public function __construct(BlogRepositoryInterface $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = $this->blogRepository->browse();

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

        $newBlog = $this->blogRepository->add($request->all());

        if ($newBlog) {
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
        $specificBlog = $this->blogRepository->read($id);

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
        
        $specificBlog = $this->blogRepository->edit($id, $request->all());

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
        $specificBlog = $this->blogRepository->delete($id);

        return response()->json([
            'message' => 'Blog Successfully Deleted!',
            'status' => 200,
        ]);
    }
}
