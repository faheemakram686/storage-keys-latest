<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\BlogInterface;
use Illuminate\Http\Request;

class BlogController extends Controller
{

    private $blog;

    public function __construct(BlogInterface $blog)
    {
        $this->blog = $blog;
    }
    public function index()
    {
        return view('backend.blog.index');
    }

    public function create()
    {
        return view('backend.blog.create');
    }
    public function saveBlog(Request $request)
    {

        $data = $this->blog->saveBlog($request);
        return redirect()->route('blog.index')->withSuccess(['Record Saved successfully']);
    }
    public function getBlog()
    {
        return $res=$this->blog->getAllBlogs();
    }
    public function deleteBlog(Request $request)
    {
        $this->blog->deleteblog($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }
    public function editBlog(Request $request)
    {
    $data['st']=$this->blog->editBlog($request->id);
    return view('backend.blog.edit')->with(compact('data'));
    }
    public function updateBlog(Request $request)
    {

        $res=$this->blog->updateBlog($request);
        return redirect()->route('blog.index')->withSuccess(['Record updated successfully']);
//        return response()->json(['success' => 'Record updated successfully'], 200);
    }

}
