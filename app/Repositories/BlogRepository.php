<?php

namespace App\Repositories;

use App\Blog;
use App\Repositories\Interfaces\BlogRepositoryInterface;

class BlogRepository implements BlogRepositoryInterface
{

    public function browse()
    {
        $blogs = Blog::all();

        return $blogs;
    }

    public function read($id)
    {
        $blog = Blog::find($id);

        return $blog;
    }

    public function add($request)
    {

        $blog = Blog::create([
            'title' => $request['title'],
            'image' => 'test image',
            'content' => $request['content'],
        ]);

        return $blog;
    }

    public function edit($id, $request)
    {
        $specificBlog = Blog::where('id', $id)->first();

        if ($specificBlog) {
            $specificBlog->title = $request['title'];
            $specificBlog->image = $request['image'];
            $specificBlog->content = $request['content'];
            $specificBlog->save();

            return true;

        }

    }

    public function delete($id)
    {
        $specificBlog = Blog::where('id', $id)->first();

        if ($specificBlog) {
            $specificBlog->delete();

            return true;
        }
    }

    public function showAllDeleted()
    {
        $deletedBlogs = Blog::onlyTrashed()->get();

        return $deletedBlogs;

    }

    public function restoreDeleted($id)
    {
        $deletedBlog = Blog::where('id', $id)->restore();

        return $deletedBlog;
    }

    public function searchBlog($params)
    {
        $searchedBlog = Blog::where('title', 'like', '%'.$params.'%')->get();

        return $searchedBlog;
    }
}