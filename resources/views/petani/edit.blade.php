@extends('layouts.petani-panel')
@section('title', 'Edit Petani')
@section('content')
<div class="container py-4" style="max-width:640px">
    <h4 class="mb-3">Edit Data Petani</h4>
    @include('partials.flash')
    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('petani.update', $petani) }}" method="POST">
            @csrf @method('PUT')
            @include('petani.form')
        </form>
    </div></div>
</div>
@endsection