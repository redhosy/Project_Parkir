@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, Admin {{ Auth::user()->name }}</p>
    <p>Total Slot: {{ $totalSlot }} | Terisi: {{ $terisi }} | Kosong: {{ $kosong }}</p>
</div>
@endsection

// resources/views/admin/vehicle_list.blade.php
@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Daftar Kendaraan</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama Pengguna</th>
                <th>Merk</th>
                <th>Warna</th>
                <th>Tipe</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->user->name }}</td>
                    <td>{{ $vehicle->merk }}</td>
                    <td>{{ $vehicle->warna }}</td>
                    <td>{{ $vehicle->tipe }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection