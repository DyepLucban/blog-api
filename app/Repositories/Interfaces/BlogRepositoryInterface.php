<?php

namespace App\Repositories\Interfaces;

interface BlogRepositoryInterface
{
    public function browse();

    public function read($id);
    
    public function add($request);

    public function edit($id, $request);

    public function delete($id);

    public function showAllDeleted();

    public function restoreDeleted($id);

    public function searchBlog($request);

}