<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ChangePasswordController extends Controller
{

    public function show(): View
    {
        return view('auth.passwords.change');
    }

    public function store(ChangePasswordRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->password = Hash::make($request->validated()['password']);
        $user->save();

        switch($user->user_type){
            case 'C': 
                $tipoUser = "Cliente"; 
                break;
            case 'A':
                $tipoUser = "Administrador";
                break;
            case 'E':
                $tipoUser = "Funcionário";
                break;
        }
        
        Alert::success('Alterada com sucesso!', 'A palavra-passe do '.$tipoUser.' '.$user->name.' foi alterada com sucesso');
        return redirect()->route('user', $user);
    }
}
