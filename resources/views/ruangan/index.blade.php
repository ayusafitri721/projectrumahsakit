<!-- resources/views/ruangan/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Data Ruangan</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('ruangan.create') }}"> Tambah Ruangan Baru</a>
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
            <th>No</th>
            <th>Kode Ruangan</th>
            <th>Nama Ruangan</th>
            <th>Daya Tampung</th>
            <th>Lokasi</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($ruangan as $item)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $item->kodeRuangan }}</td>
            <td>{{ $item->namaRuangan }}</td>
            <td>{{ $item->dayaTampung }} orang</td>
            <td>{{ $item->lokasi }}</td>
            <td>
                <form action="{{ route('ruangan.destroy',$item->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('ruangan.show',$item->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('ruangan.edit',$item->id) }}">Edit</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

    {!! $ruangan->links() !!}
</div>
@endsection