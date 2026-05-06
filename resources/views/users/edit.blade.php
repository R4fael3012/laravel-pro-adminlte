@extends('layouts.default')
@section('page-title', 'Editar Usuário')
@section('content')
@session('status')
    <div class="alert alert-success">
        {{ $value }}
    </div>
@endsession
<form action="{{ route('users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
  <div class="mb-3">
    <label class="form-label">Nome</label>
    <input type="text" name="name" value="{{ old('name') ?? $user->name }}" class="form-control @error('name') is-invalid @enderror">
    @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
    
  </div>  

  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="text" name="email" value="{{ old('email') ?? $user->email }}" class="form-control @error('email') is-invalid @enderror">
    @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
    
  </div>

  <div class="mb-3">
    <label class="form-label">Senha</label>
    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
    @error('password')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
  </div>
  
  <button type="submit" class="btn btn-primary">Editar</button>
</form>
@endsection 