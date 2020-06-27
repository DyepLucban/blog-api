<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\BlogRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $flag = 'post';

        $data = $this->imageDecoder($request->all(), $flag);

        $newBlog = $this->blogRepository->add($data);

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

        $flag = 'update';

        $data = $this->imageDecoder($request->all(), $flag);

        $specificBlog = $this->blogRepository->edit($id, $data);

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

    public function showAllDeleted()
    {
        $deletedBlogs = $this->blogRepository->showAllDeleted();

        return response()->json([
            'data' => $deletedBlogs,
            'status' => 200
        ]);        
    }

    public function restoreDeleted($id)
    {
        $deletedBlogId = $this->blogRepository->restoreDeleted($id);

        if ($deletedBlogId) {
            return response()->json([
                'message' => 'Blog Successfully Restored!',
                'status' => 200
            ]);          
        }
    }

    public function searchBlog(Request $request)
    {
        $searchedBlog = $this->blogRepository->searchBlog($request->input('text'));

        if ($searchedBlog) {
            return response()->json([
                'data' => $searchedBlog,
                'status' => 200
            ]);          
        }
    }

    public function imageDecoder($params, $flag)
    {
        if ($flag == 'post') {

            $explode = explode(',', $params['image']);

            $decode = base64_decode($explode[1]);

            str_contains($explode[0], 'jpeg') ? $extension = 'jpg' : $extension = 'png';

            $filename = Str::random(12) . '.' .$extension;
            
            $path = public_path() . '/images/' . $filename;

            file_put_contents($path, $decode);

            $data = [
                'title' => $params['title'],
                'image' => $filename,
                // 'image' => "https://heroku-blog-api.herokuapp.com/images/" . $filename,
                'content' => $params['content'],
            ];

            return $data; 
            
        } else {

            $checkImage = str_contains($params['image'], 'data');

            if ($checkImage) 
            {
                $explode = explode(',', $params['image']);

                $decode = base64_decode($explode[1]);

                str_contains($explode[0], 'jpeg') ? $extension = 'jpg' : $extension = 'png';

                $filename = Str::random(12) . '.' .$extension;
                
                $path = public_path() . '/images/' . $filename;

                file_put_contents($path, $decode);

                $data = [
                    'title' => $params['title'],
                    'image' => $filename,
                    'image' => "https://heroku-blog-api.herokuapp.com/images/" . $filename,
                    // 'content' => $params['content'],
                ];

                return $data;

            } else {

                $data = [
                    'title' => $params['title'],
                    'image' => $params['image'],
                    'image' => "https://heroku-blog-api.herokuapp.com/images/" . $filename,
                    // 'content' => $params['content'],
                ];

                return $data;

            }

        }
       
    }

}
