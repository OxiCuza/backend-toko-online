@extends('layouts.global')

@section('title') Create Category @endsection

@section('content')
    @if(session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
    @endif

    <form
        enctype="multipart/form-data"
        class="bg-white shadow-sm p-3"
        action="{{route('categories.store')}}"
        method="POST">
        @csrf

        <label for="name">Category name</label>
        <br>
        <input
            type="text"
            class="form-control {{$errors->first('name') ? 'is-invalid' : ''}}"
            value="{{old('name')}}"
            id="name"
            name="name" />
        <div class="invalid-feedback">
            {{$errors->first('name')}}
        </div>
        <br>

        <label for="image">Category image</label>
        <input
            id="image"
            type="file"
            class="form-control {{$errors->first('image') ? 'is-invalid' : ''}}"
            name="image" />
        <div class="invalid-feedback">
            {{$errors->first('image')}}
        </div>
        <br>
        <input
            type="submit"
            class="btn btn-primary"
            value="Save" />
    </form>

@endsection
