<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function createUser(Request $request)
    {
        $this->validate($request, [
            'firstName' => 'required',
            'lastName' => 'required',
            'phone' => 'required',
            'userType' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            ]);
            $password = bcrypt($request->password);
            if ($request->role_id) {
                $user = User::create([
                    'firstName' => $request->firstName,
                    'lastName' => $request->lastName,
                    'email' => $request->email,
                    'password' => $password,
                    'phone' => $request->phone,
                    'userType' => $request->userType,
                    'role_id' => $request->role_id,
                    ]);
            } else {
                $user = User::create([
                    'firstName' => $request->firstName,
                    'lastName' => $request->lastName,
                    'email' => $request->email,
                    'password' => $password,
                    'phone' => $request->phone,
                    'userType' => $request->userType,
                    ]);
            }
                    return $user;
    }
    public function editUser(Request $request)
    {
        $this->validate($request, [
            'firstName' => 'required',
            'lastName' => 'required',
            'phone' => 'required',
            'userType' => 'required',
            'email' => "bail|required|email|unique:users,email,$request->id",
            'password' => 'min:6',
            ]);
                $data = [
                    'firstName' => $request->firstName,
                    'lastName' => $request->lastName,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'userType' => $request->userType,
                ];
                if ($request->password) {
                    $password = bcrypt($request->password);
                    $data['password'] = $password; 
                }
                if ($request->role_id) {
                    $data['role_id'] = $password; 
                }

                $user = User::where('id',$request->id)->update($data);
                return $user;
    }

    public function getUsers(Request $request)
    {
        return User::get();
    }
    public function deleteUser(Request $request) {
        //validate request
        $this->validate($request, [
            'id' => 'required'
        ]);
        return User::where('id', $request->id)->delete();
    
    }
    public function getCategories() 
    {
        return Category::orderBy('id', 'desc')->get();
    
    }
    public function upload(Request $request) 
    {
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,jpg,png',
        ]);
        $picName = time(). '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $picName);
        return $picName;
    }
    public function deleteImage(Request $request) 
    {
        $fileName = $request->image;
        $this->deleteFileFromServer($fileName, false);
        return 'done';
    }
    public function deleteFileFromServer($fileName, $hasFullPath = false) 
    {
        if (!$hasFullPath) {
            $filePath = public_path(). $fileName;
            
        }
        //dd($filePath);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        return;
    }
    public function createCategory(Request $request)
    {
                //validate request
        $this->validate($request, [
            'category_name' => 'required',
            'desc' => 'required',
            'image' => 'required',
        ]);
        return Category::create([
            'category_name' => $request->category_name,
            'image' => $request->image,
            'desc' => $request->desc,
        ]);
    }
}
