<?php

namespace App\Repo;

use App\Models\Blog;
use http\Exception\BadConversionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BlogClass implements Interfaces\BlogInterface
{

    public function getAllBlogs()
    {
        // TODO: Implement getAllBlogs() method.
        $qry=Blog::query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function saveBlog($request)
    {
        // TODO: Implement saveBlog() method.
        if ($request->hasFile('file')) {
            $uniqueid = uniqid();
            $original_name = $request->file('file')->getClientOriginalName();
            $size = $request->file('file')->getSize();
            $extension = $request->file('file')->getClientOriginalExtension();
            $name = Carbon::now()->format('Ymd') . '_' . $uniqueid . '.' . $extension;
            $imagepath = url('/storage/uploads/blog-images/' . $name);
            $path = $request->file('file')->storeAs('public/uploads/blog-images/', $name);
        }else{
            $name='empty';
        }

        $slug = Str::slug($request->title, '-');


        $sy =new Blog();
        $sy->title=$request->title;
        $sy->image=$name;
        $sy->description=$request->description;
        $sy->slug=$slug;
        $sy->status=$request->status;
        if($sy->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function deleteblog($id)
    {
        // TODO: Implement deleteblog() method.
        $country=Blog::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editBlog($id)
    {
        // TODO: Implement editBlog() method.
        return $country=Blog::find($id);
    }

    public function updateBlog($request)
    {
        // TODO: Implement updateBlog() method.
        $imgname = 0;
        if ($request->hasFile('file')) {
            $uniqueid = uniqid();
            $original_name = $request->file('file')->getClientOriginalName();
            $size = $request->file('file')->getSize();
            $extension = $request->file('file')->getClientOriginalExtension();
            $imgname = Carbon::now()->format('Ymd') . '_' . $uniqueid . '.' . $extension;
            $imagepath = url('/storage/uploads/blog-images/' . $imgname);
            $path = $request->file('file')->storeAs('public/uploads/blog-images/', $imgname);
        }

        $slug = Str::slug($request->title, '-');
        $sy=Blog::find($request->id);
        $sy->title=$request->title;
        if($imgname!=0)
        {
            $sy->image=$imgname;
        }
        $sy->description=$request->description;
        $sy->slug=$slug;
        $sy->status=$request->status;
        $sy->save();
        return 1;
    }
}
