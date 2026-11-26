@extends('layouts.petani-panel')
@section('title', 'Edit Panen')
@section('content')
<div class="container py-4" style="max-width:640px">
    <h4 class="mb-3">Edit Hasil Panen</h4>
    @include('partials.flash')
    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('panen.update', $panen) }}" method="POST">
            @csrf @method('PUT')
            @include('panen.form')
        </form>
    </div></div>
</div>
@endsection