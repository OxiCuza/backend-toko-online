@extends('layouts.global')

@section('title') Edit Category @endsection

@section('content')
    @if(session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
    @endif

    <div class="col-md-8">
        <form
            action="{{route('categories.update', [$category->id])}}"
            enctype="multipart/form-data"
            method="POST"
            class="bg-white shadow-sm p-3"
        >
            @csrf
            @method('PUT')

            <label for="name">Category name</label>
            <br>
            <input
                id="name"
                type="text"
                class="form-control {{$errors->first('name') ? 'is-invalid' : ''}}"
                value="{{old('name') ? old('name') : $category->name}}"
                name="name" />
            <div class="invalid-feedback">
                {{$errors->first('name')}}
            </div>
            <br>
            <br>

            <label for="slug">Cateogry slug</label>
            <input
                id="slug"
                type="text"
                class="form-control {{$errors->first('slug') ? 'is-invalid' : ''}}"
                value="{{old('slug') ? old('slug') : $category->slug}}"
                name="slug" />
            <br>
            <br>

            @if($category->image)
                <span>Current image</span><br>
                <img src="{{asset('storage/'. $category->image)}}" width="120px" alt="img-category" />
                <br>
                <br>
            @endif
            <input
                type="file"
                class="form-control {{$errors->first('image') ? 'is-invalid' : ''}}"
                name="image">
            <small
                class="text-muted">
                Kosongkan jika tidak ingin mengubah gambar
            </small>
            <div class="invalid-feedback">
                {{$errors->first('image')}}
            </div>
            <br>
            <br>

            <input type="submit" class="btn btn-primary" value="Update">
        </form>
    </div>
@endsection
