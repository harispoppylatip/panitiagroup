@extends('layout.masteradmin')
@section('konten')
    <h1>Editor Post</h1>
    <table class="table text-light">
        <thead class="table-dark">
            <tr>
                <th scope="col">No</th>
                <th scope="col">Judul</th>
                <th scope="col">Deks</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody class="table-group-divider table-dark">
            @foreach ($data as $item)
                <tr>
                    <th scope="row">{{ $item->id }}</th>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->Nim }}</td>
                    <td>
                        <a href="/edit/{{ $item->id }}" class="btn btn-outline-warning" role="button">Edit</a>
                        {{-- <a href="/token/del/{{ $item->id }}" class="btn btn-outline-danger" role="button">Delete</a> --}}
                        <form action="{{ route('admin.token.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
