<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Categorias;
use App\Models\Precos;
use App\Models\Cores;
use App\Models\TShirts;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Models\Encomendas;
use App\Models\ItensEncomenda;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class PaginaUserController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(): View
    {   
        $user = Auth::user();

        if($user->user_type == 'A'){

            $tipoUser = 'Administrador';

            $numencomendas = Encomendas::count();

            $numutilizadores = User::whereNull('deleted_at')->count();

            $numCategorias = Categorias::whereNull('deleted_at')->count();

            $numCores = Cores::whereNull('deleted_at')->count();
            
            return view('administradores.index',compact('user','tipoUser','numencomendas','numutilizadores','numCategorias','numCores'));
        }

        if($user->user_type == 'E'){
            
            $tipoUser = 'Funcionário';

            $numencomendas = Encomendas::where('status','=','pending')->orwhere('status','=','paid')->count();

            return view('funcionarios.index',compact('user','tipoUser','numencomendas'));
        }

        if($user->user_type == 'C'){

            $tipoUser = 'Cliente';

            $numencomendas = Encomendas::where('customer_id', '=', $user->id)->count();

            $numTshirts = TShirts::whereNull('deleted_at')->where('customer_id', '=', $user->id)->count();
            
            return view('clientes.index',compact('user','tipoUser','numencomendas', 'numTshirts'));
        }
    }

    public function edit(User $user): View{

        if($user->user_type == 'A'){
            $tipoUser = 'Administrador';
            return view('administradores.edit',compact('user','tipoUser'));
        }

        if($user->user_type == 'E'){
            $tipoUser = 'Funcionário';
            return view('funcionarios.edit',compact('user','tipoUser'));
        }

        if($user->user_type == 'C'){
            $tipoUser = 'Cliente';
            return view('clientes.edit',compact('user','tipoUser'));
        }
        
    }

    public function update(UserRequest $request, User $user): RedirectResponse{
        
        $formData = $request->validated();
        $user = DB::transaction(function () use ($formData, $user, $request) {

            $user->name = $formData['name'];

            // enviar email de confirmação se for diferente 

            if ($user->email != $formData['email']){
                $user->email_verified_at = null;
            }

            $user->email = $formData['email'];
            
            if (Auth::user()->user_type == 'A'){
                $user->user_type = $formData['userType'];
            }
            
            $user->save();

            if ($user->user_type === 'C'){
                $cliente = $user->cliente;
                $cliente->nif = $formData['nif'];
                $cliente->address = $formData['address'];
                $cliente->default_payment_type = $formData['default_payment_type'];
                $cliente->default_payment_ref = $formData['default_payment_ref'];
                $cliente->save();
            }

            if ($request->hasFile('image')) {
                if ($user->photo_url) {
                    Storage::delete('public/photos/' . $user->photo_url);
                }
                $path = $request->image->store('public/photos');
                $user->photo_url = basename($path);
                $user->save();
            }

            return $user;
        });

        
        if ($user->email_verified_at == null){
            $user->sendEmailVerificationNotification();
        }

        $tipoUser = self::getTipoUser($user);

        $htmlMessage = "O $tipoUser $user->name foi alterado com sucesso!";
        
        Alert::success('Editado com sucesso!', $htmlMessage);
        
        return Auth::user()->id == $user->id ? redirect()->route('user', $user) : redirect()->route('user.gerirUsers', Auth::user());
    }

    public function destroy_foto(User $user): RedirectResponse
    {
        if ($user->photo_url) {
            Storage::delete('public/photos/' . $user->photo_url);
            $user->photo_url = null;
            $user->save();
        }

        $tipoUser = self::getTipoUser($user);

        $htmlMessage = "A foto do $tipoUser $user->name foi eliminada!";

        Alert::success('Eliminada com sucesso!', $htmlMessage);

        return Auth::user() == $user ? redirect()->route('user', $user) : redirect()->route('user.gerirUsers', Auth::user());
    }

    public function showUsers(Request $request, User $user): View{

        $queryUsers = User::query();

        $pesquisaFiltro = $request->pesquisa ?? '';

        if ($pesquisaFiltro !== ''){

            $queryUsers->where(function ($query) use ($pesquisaFiltro) {
                $query->where('users.name', 'LIKE', '%' . $pesquisaFiltro . '%')
                    ->orWhere('users.email', 'LIKE', '%' . $pesquisaFiltro . '%');
            });


        }

        $selecionarFiltro = $request->selecionar ?? 'todos';

        if ($selecionarFiltro != 'todos'){
            $queryUsers->where('user_type',$selecionarFiltro);
        }

        $ordenarFiltro = $request->ordenar ?? 'date_desc';

        if (str_contains($ordenarFiltro,'date')){
            $ordenarArray = preg_split("/[_\s:]/",$ordenarFiltro);
            $queryUsers->orderBy('created_at',$ordenarArray[1]);
        }


        $utilizadores = $queryUsers->whereNull('deleted_at')->paginate(15);

        return view('administradores.users', compact('user','utilizadores','pesquisaFiltro','selecionarFiltro','ordenarFiltro'));
    }

    public function showMinhasTShirts(User $user): View{

        $t_shirts = TShirts::whereNull('deleted_at')->where('customer_id', '=', $user->id)->orderBy('created_at')->paginate(15);

        return view('clientes.minhasTshirts', compact('user', 't_shirts'));
    }

    public function updateStatusBlock (Request $request, User $user): RedirectResponse{
        $blocked = $user->blocked;
        $user->blocked = $request->blocked;
        $user->save();

        $tipoUser = self::getTipoUser($user);

        if ($blocked){
            $htmlMessage = "A conta do $tipoUser $user->name foi desbloqueada!";
            Alert::success('Desbloqueado com sucesso!', $htmlMessage);
        }else{
            $htmlMessage = "A conta do $tipoUser $user->name foi bloqueada!";
            Alert::success('Bloqueada com sucesso!', $htmlMessage);
        }

        return Auth::user() == $user ? redirect()->route('user', $user) : redirect()->route('user.gerirUsers', Auth::user());
    }

    public function destroy_user (User $user): RedirectResponse{

        if($user->cliente != null){
            // apenas pode dar soft delete a cliente
            $user->cliente->delete();
        }

        $user->delete();

        $tipoUser = self::getTipoUser($user);

        $htmlMessage = "A conta do $tipoUser $user->name foi eliminada!";

        Alert::success('Eliminada com sucesso!', $htmlMessage);

        return Auth::user() == $user ? redirect()->route('user', $user) : redirect()->route('user.gerirUsers', Auth::user());
    }

    public function create(): View{

        $user = new User();
        return view('users.create', compact('user'));
    }

    public function store(UserRequest $request): RedirectResponse{

        $formData = $request->validated();
        $user = DB::transaction(function() use ($formData, $request){

            $newUser = new User();

            $newUser->name = $formData['name'];
            $newUser->email = $formData['email'];
            $newUser->user_type = $formData['userType'];
            $newUser->blocked = '0';
            //password
            $newUser->password = Hash::make($formData['password']);

            if ($request->hasFile('image')){
                $path = $request->image->store('public/photos/');
                $newUser->photo_url = basename($path);  
            }

            $newUser->save();

            $newUser->sendEmailVerificationNotification();
            return $newUser;
        });

        $tipoUser = self::getTipoUser($user);

        $htmlMessage = "A conta do $tipoUser $user->name foi criada!";

        Alert::success('Criada com sucesso!', $htmlMessage);

        return redirect()->route('user.gerirUsers', Auth::user());
    }
    
    // tem de ser sempre a ultima 

    private function getTipoUser(User $user){

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

        return $tipoUser;
    }
}
