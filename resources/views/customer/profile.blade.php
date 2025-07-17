@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Profil Saya</h1>
    <ul class="list-group">
        <li class="list-group-item">Nama: {{ $user->name }}</li>
        <li class="list-group-item">Email: {{ $user->email }}</li>
        <li class="list-group-item">Role: {{ $user->role }}</li>
    </ul>
</div>
@endsection