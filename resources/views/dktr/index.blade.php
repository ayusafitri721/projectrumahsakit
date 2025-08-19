@extends('template')

@section('content')

<!-- Navbar dengan Profile Photo -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dktr.index') }}">
            <i class="fas fa-hospital"></i> SIRS Dashboard
        </a>
        
        <div class="navbar-nav ms-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                    @if(Auth::user()->profile_photo && \Storage::disk('public')->exists(Auth::user()->profile_photo))
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                             class="rounded-circle me-2" width="30" height="30" 
                             style="object-fit: cover;">
                    @endif
                    {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu">
                    <li><span class="dropdown-item-text"><small>{{ Auth::user()->email }}</small></span></li>
                    <li><span class="dropdown-item-text"><small>Role: {{ Auth::user()->role }}</small></span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="row mt-5 mb-5">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h2>CRUD DOKTER</h2>
        </div>
        <div class="float-right">
            <a class="btn btn-success" href="{{ route('dktr.create') }}"> Input Dokter</a>
            <a class="btn btn-success" href="{{ route('home') }}"> Home</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<table class="table table-bordered">
    <tr>
        <th width="20px" class="text-center">Id</th>
        <th>ID Dokter</th>
        <th width="280px" class="text-center">Nama Dokter</th>
        <th width="280px" class="text-center">Tanggal Lahir</th>
        <th width="280px" class="text-center">Spesialis</th>
        <th width="20%" class="text-center">Action</th>
    </tr>
    @foreach ($dktr as $dokter)
    <tr>
        <td class="text-center">{{ ++$i }}</td>
        <td>{{ $dokter->idDokter }}</td>
        <td>{{ $dokter->namaDokter }}</td>
        <td>{{ $dokter->tanggalLahir ?? '-' }}</td>
        <td>{{ $dokter->spesialis ?? '-' }}</td>
        <td class="text-center">
            <form action="{{ route('dktr.destroy', $dokter->id) }}" method="POST">
                <a class="btn btn-info btn-sm" href="{{ route('dktr.show', $dokter->id) }}">Show</a>
                <a class="btn btn-primary btn-sm" href="{{ route('dktr.edit', $dokter->id) }}">Edit</a>

                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

{!! $dktr->links() !!}

@endsection