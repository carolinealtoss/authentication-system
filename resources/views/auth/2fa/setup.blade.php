@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Configuração de Autenticação de Dois Fatores (2FA)</h2>
    <p>Escaneie o seguinte QR Code no aplicativo Google Authenticator ou insira manualmente o código secreto.</p>

    <div>
        {!! $QR_Image !!}
    </div>

    <p>Ou insira o código manualmente: <strong>{{ $secret }}</strong></p>

    <form action="{{ route('enable.2fa') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="2fa_code">Insira o código gerado pelo Google Authenticator:</label>
            <input type="text" name="2fa_code" class="form-control" required>
        </div>

        @if ($errors->has('2fa_code'))
            <div class="alert alert-danger">{{ $errors->first('2fa_code') }}</div>
        @endif

        <button type="submit" class="btn btn-primary">Ativar 2FA</button>
    </form>
</div>
@endsection
