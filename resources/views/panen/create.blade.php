@extends('layouts.petani-panel')
@section('title', 'Catat Panen')
@section('content')
<div class="container py-4" style="max-width:640px">
    <h4 class="mb-3">Catat Hasil Panen</h4>
    @include('partials.flash')
    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('panen.store') }}" method="POST">
            @csrf
            @include('panen.form')
        </form>
    </div></div>
</div>
@endsection