@extends('layouts.petani-panel')
@section('title', 'Edit Kebun')
@section('content')
<div class="container py-4" style="max-width:640px">
    <h4 class="mb-3">Edit Data Kebun</h4>
    @include('partials.flash')
    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('kebun.update', $kebun) }}" method="POST">
            @csrf @method('PUT')
            @include('kebun.form')
        </form>
    </div></div>
</div>
@endsection