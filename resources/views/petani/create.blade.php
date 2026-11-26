@extends('layouts.petani-panel')
@section('title', 'Tambah Petani')
@section('content')
<div class="container py-4" style="max-width:640px">
    <h4 class="mb-3">Tambah Petani Baru</h4>
    @include('partials.flash')
    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('petani.store') }}" method="POST">
            @csrf
            @include('petani.form')
        </form>
    </div></div>
</div>
@endsection