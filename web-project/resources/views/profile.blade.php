@extends('layouts.app')

@section('content')

<style>
body {
    background: #f3f4f6;
    font-family: Arial, sans-serif;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.btn {
    background: #22c55e;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.card {
    background: #f9fafb;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.profile-card {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
}

.profile-card img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
}

.cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.4);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 12px;
    width: 400px;
}

input {
    width: 100%;
    padding: 8px;
    margin: 5px 0 10px;
}
</style>

<div class="header">
    <h2>Profil Pengguna</h2>
    <button class="btn" onclick="openModal()">Edit Profil</button>
</div>

{{-- NOTIFIKASI --}}
@if(session('success'))
    <div style="background:#d1fae5; padding:10px; border-radius:8px; margin-bottom:10px;">
        {{ session('success') }}
    </div>
@endif

{{-- ERROR VALIDASI --}}
@if ($errors->any())
    <div style="background:#fee2e2; padding:10px; border-radius:8px; margin-bottom:10px;">
        <ul style="margin:0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card profile-card">
    <div>
        <img src="{{ $user->foto ? asset('uploads/'.$user->foto) : 'https://via.placeholder.com/100' }}">
    </div>

    <div>
        <h2 style="margin:0;">{{ strtoupper($user->name) }}</h2>
        <p style="margin:5px 0;">{{ strtoupper($user->role) }}</p>
        <small>{{ strtoupper($user->status) }}</small>
    </div>
</div>

<div class="cards">
    <div class="card">
        <h4>Email</h4>
        <p>{{ $user->email }}</p>
    </div>

    <div class="card">
        <h4>No HP</h4>
        <p>{{ $user->hp }}</p>
    </div>

    <div class="card">
        <h4>Alamat</h4>
        <p>{{ strtoupper($user->alamat) }}</p>
    </div>
</div>

<div id="modalEdit" class="modal">
    <div class="modal-content">
        <h3>Edit Profil</h3>

        <form action="/profile/update" method="POST" enctype="multipart/form-data">
            @csrf

            <label>Nama</label>
            <input type="text" name="name" value="{{ $user->name }}" required>

            <label>Role</label>
            <input type="text" name="role" value="{{ $user->role }}" required>

            <label>Status</label>
            <input type="text" name="status" value="{{ $user->status }}">

            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email }}" required>

            <label>No HP</label>
            <input type="text" name="hp" value="{{ $user->hp }}" required>

            <label>Alamat</label>
            <input type="text" name="alamat" value="{{ $user->alamat }}" required>

            <label>Foto</label>
            <input type="file" name="foto">

            <button type="submit" class="btn">Simpan</button>
            <button type="button" onclick="closeModal()">Batal</button>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById("modalEdit").style.display = "flex";
}

function closeModal() {
    document.getElementById("modalEdit").style.display = "none";
}
</script>

@endsection