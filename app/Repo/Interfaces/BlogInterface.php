<?php
namespace App\Repo\Interfaces;

interface BlogInterface{

    public function getAllBlogs();
    public function saveBlog($request);
    public function deleteblog($id);

    public function editBlog($id);
    public function updateBlog($request);


}
