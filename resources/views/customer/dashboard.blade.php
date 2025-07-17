@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Dashboard Customer</h1>
    <p>Selamat datang, {{ Auth::user()->name }}</p>
    <div class="row">
        @foreach ($slots as $slot)
            <div class="col-md-3">
                <div class="card mb-3 {{ $slot->status == 'terisi' ? 'bg-danger' : 'bg-success' }} text-white">
                    <div class="card-body">
                        Slot: {{ $slot->kode_slot }}<br>
                        Status: {{ ucfirst($slot->status) }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection