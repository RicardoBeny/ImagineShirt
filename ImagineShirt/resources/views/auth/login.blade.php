@extends('layouts.app')

<style>
    .form-check-input:checked{
        background-color: #e63334!important;
        border-color: #e63334!important;
    }
</style>

@section('content')
<div class="container">
    @if (session('alert-msg'))
    <div class="row justify-content-center" style="margin-top: 20px">
        <div class="col-lg-6">
            <div class="alert alert-{{ session('alert-type') }} alert-dismissible">
                {{ session('alert-msg') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div style="font-size: 1.1rem; font-weight: bold;" class="card-header text-center">{{ __('Login') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-6">
                                <input id="email" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <i class="fa-solid fa-envelope" style="color: #e63334;"></i>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Credenciais erradas!</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            

                            <div class="col-md-6">
                                <input id="password" placeholder="Palavra-Passe" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button style = "background-color: #e63334; border-color: #e63334" type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link link-offset-3" href="{{ route('password.request') }}">
                                        {{ __('Esqueceu-se da palavra-passe?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
