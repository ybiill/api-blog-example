<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MBlog;
use App\Models\MKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use PhpParser\Node\Stmt\TryCatch;

class AdminView extends Controller
{
    public function ViewUser()
    {
        try {
            $user = User::get();
            return response()->json([
                'message' => 'Fetch all posts',
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal',
                'success' => true,
                'data' => $e
            ]);
        }
    }
    public function ViewBlog()
    {
        try {
            $blog = MBlog::get();
            return response()->json([
                'data' => $blog,
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

    public function ActiveUser($id)
    {
        $user = User::where('id', $id)->first();
        try {
            $user->update([
                'status' => 'aktif',
            ]);
            return response()->json([
                'message' => 'User Actived successfully',
                'success' => true,
                'data' => $user

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal',
                'success' => true,
                'data' => $e
            ]);
        }
    }

    //Get All Kategori Data
    public function Getkategori()
    {
        try {
            $Kategori = MKategori::get();
            return response()->json([
                'data' => $Kategori,
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

    //Create Kategori Data
    public function Postkategori(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            $createkategori = MKategori::create([
                'kategori'  => $validatordata['kategori'],
            ]);

            return response()->json([
                'data' => $createkategori,
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
    //Update Kategori
    public function Updatekategori(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
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
        $Kategori = MKategori::where('id', $id)->first();
        try {
            $Kategori->update([
                'kategori'  => $validatordata['kategori'],
            ]);

            return response()->json([
                'data' => $Kategori,
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
    //Delete Kategori
    public function destroyKategori($id)
    {
        try {
            $Kategori = MKategori::where('id', $id)->first();
            $Kategori->delete();
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
