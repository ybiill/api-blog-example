<?php

namespace App\Http\Controllers;

use App\Models\MBlog;
use App\Models\MKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class Blog extends Controller
{
    //Mengambil Data All Blog
    public function Getblog()
    {
        try {
            $Blog = MBlog::get();
            return response()->json([
                'data' => $Blog,
                'message' => 'Fetch all posts',
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal',
                'success' => true,
                'data' => $e
            ]);
        }
    }

    //Create Blog 
    public function Postblog(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'    => 'required',
            'content'  => 'required',
            'kategori' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [],
                'message' => $validator->errors(),
                'success' => false
            ]);
        }
        $validatordata = $validator->validated();
        try {
            $createblog = MBlog::create([
                'title'     => $validatordata['title'],
                'content'   => $validatordata['content'],
                'kategori'  => $validatordata['kategori'],
            ]);

            return response()->json([
                'data' => $createblog,
                'message' => 'Konten Berhasil Diinput.',
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal',
                'success' => true,
                'data' => $e
            ]);
        }
    }

    //Show Detail Blog
    public function get_detail_blog($id)
    {
        try {
            $Blog = MBlog::where('id', $id)->get();
            return response()->json([
                'data' => $Blog,
                'message' => 'Data Detail Blog',
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal',
                'success' => true,
                'data' => $e
            ]);
        }
    }

    //Update Blog Data
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'    => 'required',
            'content'  => 'required',
            'kategori' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [],
                'message' => $validator->errors(),
                'success' => false
            ]);
        }
        $validatordata = $validator->validated();
        $blog = MBlog::where('id', $id)->first();
        try {
            $blog->update([
                'title' => $validatordata['title'],
                'content' => $validatordata['content'],
                'kategori' => $validatordata['kategori'],
            ]);

            return response()->json([
                'data' => $blog,
                'message' => 'Post updated successfully',
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal',
                'success' => true,
                'data' => $e
            ]);
        }
    }

    //Delete Blog Data
    public function destroy($id)
    {
        try {
            $blog = MBlog::where('id', $id)->first();
            $blog->delete();
            return response()->json([
                'success' => true,
                'message' => 'Blog deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal',
                'success' => true,
                'data' => $e
            ]);
        }
    }

    
}
