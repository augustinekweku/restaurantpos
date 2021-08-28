<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Role;
use App\Models\User;
use App\Models\Table;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $user = User::where('id', $request->id)->update($data);
        return $user;
    }

    public function getUsers(Request $request)
    {
        return User::get();
    }
    public function deleteUser(Request $request)
    {
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
        $picName = time() . '.' . $request->file->extension();
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
            $filePath = public_path() . $fileName;
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
    public function editCategory(Request $request)
    {
        //validate request
        $this->validate($request, [
            'category_name' => 'required',
            'desc' => 'required',
            'image' => 'required',
        ]);
        return Category::where('id', $request->id)->update([
            'category_name' => $request->category_name,
            'image' => $request->image,
            'desc' => $request->desc,
        ]);
    }
    public function deleteCategory(Request $request)
    {
        //first delete the original file from the server
        $this->deleteFileFromServer($request->image);
        //validate request
        $this->validate($request, [
            'id' => 'required'
        ]);
        return Category::where('id', $request->id)->delete();
    }



    public function createItem(Request $request)
    {
        //validate request
        $this->validate($request, [
            'item_name' => 'required',
            'item_description' => 'required',
            'image' => 'required',
            'price' => 'required',
            'category_id' => 'required',
        ]);
        return Item::create([
            'item_name' => $request->item_name,
            'item_description' => $request->item_description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => $request->image,
        ]);
    }


    public function getItems()
    {
        $items = Item::paginate(2);
        return response()->json($items);
    }

    public function editItem(Request $request)
    {
        $this->validate($request, [
            'item_name' => 'required',
            'item_description' => 'required',
            'image' => 'required',
            'price' => 'required',
            'category_id' => 'required',
        ]);
        return Item::where('id', $request->id)->update([
            'item_name' => $request->item_name,
            'item_description' => $request->item_description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => $request->image,
        ]);
    }

    public function deleteItem(Request $request)
    {
        //first delete the original file from the server
        $this->deleteFileFromServer($request->image);
        //validate request
        $this->validate($request, [
            'id' => 'required'
        ]);
        return Item::where('id', $request->id)->delete();
    }

    public function getRoles()
    {
        return Role::get();
    }

    public function createRole(Request $request)
    {
        //validate request
        $this->validate($request, [
            'role_name' => 'required'
        ]);
        return Role::create([
            'role_name' => $request->role_name,
        ]);
    }

    public function createTable(Request $request)
    {
        //validate request
        $this->validate($request, [
            'table_name' => 'required',
            'status' => 'required'
        ]);
        return Table::create([
            'table_name' => $request->table_name,
            'status' => $request->status,
        ]);
    }
    public function getEmptyAndUnpaidTables()
    {
        //return Table::where('status', 3)->get();
        $getTables = DB::table('Tables')->where('status', '=', 2)
            ->orWhere('status', '=', 3)
            ->get();
        return $getTables;
    }
    public function getAllTables()
    {
        return Table::orderBy('id', 'desc')->get();
    }
    public function getItemsForPos()
    {
        return Item::orderBy('id', 'desc')->get();
    }
    public function getCategoryId($category_id)
    {
        $data =  Category::where('id', '=', $category_id)->get('category_name');
        return $data;
    }
}
