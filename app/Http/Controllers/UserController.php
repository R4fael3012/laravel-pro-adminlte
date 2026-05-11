<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query();
        $users = User::orderby('id', 'asc');


        $users->when($request->keyword, function($query, $keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
        });
        return view('users.index', [
            'users' => $users->paginate(10)
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);    

        User::create($input);

        return redirect()
        ->route('users.index')
        ->with('status', 'Usuário adicionado com sucesso!');
    }

    public function edit(User $user)
    {    
        $user ->load(['profile', 'interests']);
        $roles = Role::all();
        return view('users.edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(User $user, Request $request)
    {
        $input = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'exclude if:password,null|min:6'
        ]);
        $user-> fill($input);              
        $user->save();

        return redirect()
        ->route('users.index')
        ->with('status', 'Usuário editado com sucesso!');               
    }

    public function updateProfile(User $user, Request $request)
    {
        $input = $request->validate([
            'type' => 'required',
            'address' => 'nullable'
        ]);
        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            $input
        );

        return back()
            ->with ('status', 'Perfil atualizado com sucesso!');
    }

    public function updateInterests(User $user, Request $request)
    {                
        $input = $request->validate([
            'interests' => 'nullable|array',
        ]);

        /* $interests = array_map(fn($item) => ['name' => $item], $input['interests']); */

        $user->interests()->delete();
        
        if(!empty($input['interests'])) {
            $interests = array_map(function($item) {
            return ['name' => $item];
            }, $input['interests']);
        
            $user->interests()->createMany($interests);
        }

        return back()->with ('status', 'Interesses atualizados com sucesso!');
    }

    public function updateRoles(User $user, Request $request)
    {    
                 
        $input = $request->validate([
            'roles' => 'required|array',
        ]);

        $user->roles()->sync($input['roles']);

        return back()->with ('status', 'Funções atualizadas com sucesso!');
    }

    public function destroy(User $user)
    {
        
        $user->delete();

        return back()
        /* ->route('users.index') */
        ->with('status', 'Usuário deletado com sucesso!');
    }
}
