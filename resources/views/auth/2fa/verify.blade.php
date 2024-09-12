@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Verificação de Dois Fatores (2FA)</h2>
    <p>Insira o código do Google Authenticator para concluir o login.</p>

    <form action="{{ route('verify.2fa.post') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="2fa_code">Código 2FA:</label>
            <input type="text" name="2fa_code" class="form-control" required autofocus>
        </div>

        @if ($errors->has('2fa_code'))
            <div class="alert alert-danger">{{ $errors->first('2fa_code') }}</div>
        @endif

        <button type="submit" class="btn btn-primary">Verificar</button>
    </form>
</div>
@endsection
