@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Daftar Kendaraan Pengguna</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama Pengguna</th>
                <th>Merk</th>
                <th>Warna</th>
                <th>Tipe</th>
                <th>Tarif</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->user->name }}</td>
                    <td>{{ $vehicle->merk }}</td>
                    <td>{{ $vehicle->warna }}</td>
                    <td>{{ $vehicle->tipe }}</td>
                    <td>Rp {{ number_format($vehicle->tarif, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
