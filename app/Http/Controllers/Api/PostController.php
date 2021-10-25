<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Controllers\Api\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    use ApiResponseTrait;
    public function index()
    {
       $post=PostResource::collection(Post::get());
       return $this->apiResponse($post,'ok',200);

    }

    public function show($id){

        $post=Post::find($id);
        if ($post)
         return $this->apiResponse(new PostResource($post),'ok',200);
         else
         return $this->apiResponse(null,'the post not found',401);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(),400);

        }


        $post=Post::create($request->all());
        if($post)
            return $this->apiResponse(new PostResource($post),'the post save',201);
        else
            return $this->apiResponse(null,'the post not save',400);


    }


    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(),400);

        }


        $post=Post::find($id);

        if(!$post)
          return $this->apiResponse(null,'the post not found',404);

        $post->update($request->all());
        if($post)

            return $this->apiResponse(new PostResource($post),'the post update',201);
        else
          return $this->apiResponse(null,'the post not found',401);

    }

    public function destroy($id)
    {
        $post=Post::find($id);
        if(!$post)
          return $this->apiResponse(null,'the post not found',404);
        $post->delete($id);
        if($post)

            return $this->apiResponse(new PostResource($post),'the post deleted',200);


    }
}
