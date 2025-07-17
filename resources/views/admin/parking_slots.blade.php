@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Manajemen Slot Parkir</h1>
    <form method="POST" action="{{ route('admin.slots.store') }}">
        @csrf
        <div class="mb-3">
            <label for="kode_slot" class="form-label">Kode Slot</label>
            <input type="text" class="form-control" name="kode_slot" required>
        </div>
        <button type="submit" class="btn btn-success">Tambah Slot</button>
    </form>

    <h3 class="mt-4">Daftar Slot</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode Slot</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($slots as $slot)
                <tr>
                    <td>{{ $slot->kode_slot }}</td>
                    <td>{{ ucfirst($slot->status) }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.slots.updateStatus', $slot->id) }}">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="form-select">
                                <option value="kosong" {{ $slot->status == 'kosong' ? 'selected' : '' }}>Kosong</option>
                                <option value="terisi" {{ $slot->status == 'terisi' ? 'selected' : '' }}>Terisi</option>
                            </select>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection