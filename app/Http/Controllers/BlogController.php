<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\BlogRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

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

        if (!$blogs->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $blogs,
            ]);            
        }

        return response()->json([
            'status' => 'error',
            'code' => 404,            
            'message' => 'No Result Found!',
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

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 405,
                'message' => 'Dont leave title or content blank',
            ]);
        }        

        $flag = 'post';

        $data = $this->imageDecoder($request->all(), $flag);

        if ($data) {
            $newBlog = $this->blogRepository->add($data);

            if ($newBlog) {
                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Blog Successfully Posted!',
                ]);
            }
        }

        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => 'Bad Request',
        ]);        

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

        if ($specificBlog) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $specificBlog,
            ]);            
        }

        return response()->json([
            'status' => 'error',
            'code' => 404,            
            'message' => 'No Result Found!',
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

        if ($specificBlog) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Blog Successfully updated',
            ]);            
        }

        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => 'Bad Request',
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

        if ($specificBlog)
        {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Blog Successfully Deleted!',
            ]);            
        }

        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => 'Bad Request',
        ]);        
    }

    public function showAllDeleted()
    {
        $deletedBlogs = $this->blogRepository->showAllDeleted();

        if (!$deletedBlogs->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $deletedBlogs,
            ]);            
        }

        return response()->json([
            'status' => 'error',
            'code' => 404,
            'message' => 'No Result Found!',
        ]);        
    }

    public function restoreDeleted($id)
    {
        $deletedBlogId = $this->blogRepository->restoreDeleted($id);

        if ($deletedBlogId) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Blog Successfully Restored!',
            ]);          
        }

        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => 'Bad Request',
        ]);       
    }

    public function searchBlog(Request $request)
    {
        $searchedBlog = $this->blogRepository->searchBlog($request->input('text'));

        if (!$searchedBlog->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $searchedBlog,
            ]);          
        }

        return response()->json([
            'status' => 'error',
            'code' => 404,
            'message' => 'No Result Found!',
        ]);         
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
                // 'image' => 'http://127.0.0.1:8000/images/' . $filename,
                'image' => "https://heroku-blog-api.herokuapp.com/images/" . $filename,
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
                    // 'image' => 'http://127.0.0.1:8000/images/' . $filename,
                    'image' => "https://heroku-blog-api.herokuapp.com/images/" . $filename,
                    'content' => $params['content'],
                ];

                return $data;

            } else {

                $data = [
                    'title' => $params['title'],
                    'image' => $params['image'],
                    // 'image' => "https://heroku-blog-api.herokuapp.com/images/" . $filename,
                    'content' => $params['content'],
                ];

                return $data;

            }

        }
       
    }

}
