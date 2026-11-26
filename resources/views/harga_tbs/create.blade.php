@extends('layouts.petani-panel')
@section('title', 'Tambah Harga TBS')
@section('content')
<div class="container py-4" style="max-width:640px">
    <h4 class="mb-3">Tambah Harga TBS</h4>
    @include('partials.flash')
    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('harga-tbs.store') }}" method="POST">
            @csrf
            @include('harga_tbs.form')
        </form>
    </div></div>
</div>
@endsection