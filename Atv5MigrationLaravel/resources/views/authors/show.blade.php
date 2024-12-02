@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Detalhes dos Autores</h1>

    <div class="card">
        <div class="card-header">
            Autor: {{ $autor->name }}
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $autor->id }}</p>
            <p><strong>Nome:</strong> {{ $autor->name }}</p>
        </div>
    </div>

    <a href="{{ route('authors.index') }}" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>
@endsection