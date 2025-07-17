@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Pembayaran</h1>
    <form method="POST" action="{{ route('payment.store') }}">
        @csrf
        <div class="mb-3">
            <label for="booking_id" class="form-label">ID Booking</label>
            <input type="text" class="form-control" name="booking_id" required>
        </div>
        <div class="mb-3">
            <label for="metode" class="form-label">Metode Pembayaran</label>
            <select class="form-select" name="metode">
                <option value="qris">QRIS</option>
                <option value="transfer">Transfer Bank</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" class="form-control" name="jumlah" required>
        </div>
        <button type="submit" class="btn btn-primary">Bayar Sekarang</button>
    </form>
</div>
@endsection