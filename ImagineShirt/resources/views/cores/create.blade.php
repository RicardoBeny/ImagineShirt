@extends('template.layout', ['dados' => ['active1' => '',
                                        'active2' => '',
                                        'active3' => '',
                                        'active4' => '']]) 

@section('titulo',' | Editar Cor')

@section('main')

    <!-- Breadcrumb Section Begin -->
<section class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__text">
                    <h4>Perfil - {{$user->name}}</h4>
                    <div class="breadcrumb__links">
                        <a href="{{ route('root') }}">Página Inicial</a>
                        <a href="{{ route('user', $user) }}">Perfil</a>
                        <a href="{{ route('cores') }}">Gerir Cores</a>
                        <span style = "font-weight: bold;">Editar Cor</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    @include('cores.shared.fields_fill', ['allowCreate' => true])

@endsection