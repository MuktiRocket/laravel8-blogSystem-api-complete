<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRequest;
use App\Http\Requests\CreateBlogRequest;
use App\Http\Resources\BlogListResource;
use App\Http\Resources\EditResource;
use App\Models\Blog;
use Illuminate\Http\Response;
use App\Http\Resources\BlogResource;

class BlogController extends BaseController
{
    public function createBlog(CreateBlogRequest $request)
    {

        $blog = Blog::create([
            'user_id' => $request->user()->id,
            'blog_subject' => $request->blog_subject,
            'blog_content' => $request->blog_content,
        ]);
        return $this->respond('Value successfully Inserted !!', Response::HTTP_CREATED, ['blog' => new BlogResource($blog)]);
    }



    public function blogListing()
    {
        $blogs = Blog::all();
        $data = [
            'blogs' => BlogListResource::collection($blogs),
        ];
        return $this->respond('Data fetched Successfully!!', Response::HTTP_OK, $data);
    }


    public function  editBlog($id)
    {
        $blog = Blog::where('id', $id)->first();
        return $this->respond('Data Succesfully Found', Response::HTTP_OK, ['blog' => new EditResource($blog)]);
    }


    public function updateBlog(UpdateRequest $request, $id)
    {
        $id = $request->id;
        Blog::where('id', $id)->update([
            'blog_subject' => $request->blog_subject,
            'blog_content' => $request->blog_content,
        ]);

        return $this->respond('User Successfully Updated', Response::HTTP_CREATED);
    }



    public function deleteBlog($id)
    {
        Blog::find($id)->delete();
        return $this->respond('Blog Deleted Successfully', Response::HTTP_OK);
    }
}
