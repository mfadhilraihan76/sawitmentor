@extends('layouts.petani-panel')
@section('title', 'Tambah Kebun')
@section('content')
<div class="container py-4" style="max-width:640px">
    <h4 class="mb-3">Tambah Kebun Baru</h4>
    @include('partials.flash')
    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('kebun.store') }}" method="POST">
            @csrf
            @include('kebun.form')
        </form>
    </div></div>
</div>
@endsection