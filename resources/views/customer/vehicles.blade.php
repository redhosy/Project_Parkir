@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Kendaraan Saya</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Merk</th>
                <th>Warna</th>
                <th>Tipe</th>
                <th>Tarif</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vehicles as $vehicle)
                <tr>
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