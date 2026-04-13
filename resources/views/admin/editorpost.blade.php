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
                    <td>{{ $item->postingan }}</td>
                    <td>
                        <a href="/edit/{{ $item->id }}" class="btn btn-outline-warning" role="button">Edit</a>
                        <a href="/edit/del/{{ $item->id }}" class="btn btn-outline-danger" role="button">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
