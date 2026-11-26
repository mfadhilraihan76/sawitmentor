@extends('layouts.petani-panel')
@section('title', 'Edit Harga TBS')
@section('content')
<div class="container py-4" style="max-width:640px">
    <h4 class="mb-3">Edit Harga TBS</h4>
    @include('partials.flash')
    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('harga-tbs.update', $harga) }}" method="POST">
            @csrf @method('PUT')
            @include('harga_tbs.form')
        </form>
    </div></div>
</div>
@endsection