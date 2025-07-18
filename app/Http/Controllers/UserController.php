<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function users(){
        $user = User::paginate(10);
        return view('user.users', [
            'user' => $user,
        ]);
    }
    public function create()
    {
        $user = User::paginate(10);
        return view('user.users', [
            'user' => $user,
        ]);
    }

    // insert data to table
    public function store(Request $request)
    {
        // dd($request->all());
        // data yg akan diterima function store
        $name = $request->name;
        $level = $request->level;
        $email = $request->email;
        $password = Hash::make($request->password);


        // validasi sebelum insert ke tabel
        $request->validate([
            'name' => 'required|min:3',
            'level' => 'required',
             'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        // buat object untuk simpan data ke table
        $user = new User();

        //kirim nilai2 yg didapat dari form ke table
        $user->name = $name;
        $user->level = $level;
        $user->email = $email;
        
        $user->password = $password;
        // simpan data yg telah diterima ke dalam table
        $user->save();

        // redirect ke halaman users
        return redirect('/user');
    }

    public function edit($id)
    {
        $user = User::find($id); // SELECT * FROM user WHERE id = $id
        // dd($user);

        // tampilkan form edit dan kirim datanya
        return view('user.edit', compact('user'));
    }

    // update data selected
    public function update(Request $request)
    {
        // data yg akan diterima function store

        $id = $request->id;
        $name = $request->name;
        $level = $request->level;
         $email = $request->email;

        // buat object untuk simpan data ke table
        $user = User::find($id);

        if($request->password) {
            $password = Hash::make($request->password);
        } else {
            $password = $user->password;
        }

        //kirim nilai2 yg didapat dari form ke table
        $user->name = $name;
        $user->level = $level;
        $user->email = $email;
        $user->password = $password;

        // simpan data yg telah diterima ke dalam table
        // $user->save();

        $user->update();

        // redirect ke halaman users
        return redirect('/user');
    }

    public function delete($id)
    {
        // query/perintah hapus data berdasarkan id
        User::find($id)->delete();

        // kembalikan ke halaman users
        return redirect('/user');
    }

    public function logout(Request $request)
    {
        Auth::logout();
 
        request()->session()->invalidate();
 
        request()->session()->regenerateToken();
 
        return redirect('/');
    }
}
