@extends('layout.master')
@section('konten')
    <div class="d-flex gap-3 flex-column">
        @foreach ($data as $post)
            <div class="card">
                <div class="card-header">
                    Featured
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $post->nama ?? 'No data available' }}</h5>
                    <p class="card-text">{{ $post->postingan ?? 'No post available' }}</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
