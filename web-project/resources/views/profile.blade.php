@extends('layouts.app')

@section('content')

<div class="header">
    <h2>Profil Pengguna</h2>
    <button class="btn" onclick="openModal()">Edit Profil</button>
</div>

<div class="card" style="display:flex; gap:20px; align-items:center;">
    <div>
        <img src="{{ $user->foto ? asset('uploads/'.$user->foto) : 'https://via.placeholder.com/100' }}" 
             style="width:100px; border-radius:50%;">
    </div>

    <div>
        <h2>{{ $user->name }}</h2>
        <p>{{ $user->role }}</p>
        <small>{{ $user->status }}</small>
    </div>
</div>

<div class="cards" style="margin-top:20px;">
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
        <p>{{ $user->alamat }}</p>
    </div>
</div>

<div id="modalEdit" class="modal">
    <div class="modal-content">
        <h3>Edit Profil</h3>

        <form action="/profile/update" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="text" name="name" value="{{ $user->name }}"><br><br>
            <input type="text" name="role" value="{{ $user->role }}"><br><br>
            <input type="text" name="status" value="{{ $user->status }}"><br><br>
            <input type="email" name="email" value="{{ $user->email }}"><br><br>
            <input type="text" name="hp" value="{{ $user->hp }}"><br><br>
            <input type="text" name="alamat" value="{{ $user->alamat }}"><br><br>

            <input type="file" name="foto"><br><br>

            <button type="submit">Simpan</button>
            <button type="button" onclick="closeModal()">Batal</button>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById("modalEdit").style.display = "block";
}

function closeModal() {
    document.getElementById("modalEdit").style.display = "none";
}
</script>

@endsection