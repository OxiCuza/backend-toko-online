@extends('layouts.global')

@section('title')
Create User
@endsection

@section('content')
<div class="col-md-8">

    @if(session('status'))
    <div class="alert alert-success">
        {{session('status')}}
    </div>
    @endif

    <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data" class="bg-white shadow-sm p-3">
        @csrf
        <label for="name">Name</label>
        <input
            type="text"
            name="name"
            value="{{old('name')}}"
            class="form-control {{$errors->first('name') ? 'is-invalid' : ''}}"
            placeholder="Full Name"
            id="name" />
        <div
            class="invalid-feedback">
            {{$errors->first('name')}}
        </div>
        <br>

        <label for="username">Username</label>
        <input
            type="text"
            class="form-control {{$error->first('username') ? 'is-invalid' : ''}}"
            id="username"
            placeholder="username"
            value="{{old('username')}}"
            name="username" />
        <div class="invalid-feedback">
            {{$errors->first('usernmae')}}
        </div>
        <br>

        <label for="roles">Roles</label> <br>
        <input
            type="checkbox"
            name="roles[]"
            id="ADMIN"
            value="ADMIN"
            class="form-control {{$errors->first('roles') ? 'is-invalid' : ''}}" />
        <label for="ADMIN">Administrator</label>

        <input
            type="checkbox"
            name="roles[]"
            id="STAFF"
            value="STAFF"
            class="form-control {{$errors->first('roles') ? 'is-invalid' : ''}}" />
        <label for="STAFF">Staff</label>

        <input
            type="checkbox"
            name="roles[]"
            id="CUSTOMER"
            value="CUSTOMER"
            class="form-control {{$errors->first('roles') ? 'is-invalid' : ''}}" />
        <label for="CUSTOMER">Customer</label> <br>
        <div class="invalid-feedback">
            {{$errors->first('roles')}}
        </div>
        <br>

        <label for="phone">Phone Number</label> <br>
        <input
            id="phone"
            type="text"
            name="phone"
            value="{{old('phone')}}"
            class="form-control {{$errors->first('phone') ? 'is-invalid' : ''}}" />
        <div class="invalid-feedback">
            {{$errors->phone}}
        </div>
        <br>

        <label for="address">Address</label>
        <textarea
            name="address"
            id="address"
            class="form-control {{$errors->first('address') ? 'is-invalid' : ''}}">{{old('address')}}</textarea>
        <div class="invalid-feedback">
            {{$errors->address}}
        </div>

        <br>
        <label for="avatar">Avatar image</label>
        <br>
        <input
            id="avatar"
            name="avatar"
            type="file"
            class="form-control {{$errors->first('avatar')}}" />
        <div class="invalid-feedback">
            {{$errors->first('avatar')}}
        </div>

        <hr class="my-3">

        <label for="email">Email</label>
        <input
            class="form-control {{$errors->first('email') ? 'is-invalid' : ''}}"
            placeholder="user@mail.com"
            type="text"
            name="email"
            value="{{old('email')}}"
            id="email" />
        <div class="invalid-feedback">
            {{$error->first('email')}}
        </div>
        <br>

        <label for="password">Password</label>
        <input
            class="form-control {{$errors->first('password')}}"
            placeholder="password"
            type="password"
            name="password"
            id="password" />
        <div class="invalid-feedback">
            {{$errors->password}}
        </div>
        <br>

        <label for="password_confirmation">Password Confirmation</label>
        <input
            class="form-control {{$errors->first('password_confirmation') ? 'is-invalid' : ''}}"
            placeholder="password confirmation"
            type="password"
            name="password_confirmation"
            id="password_confirmation" />
        <div class="invalid-feedback">
            {{$errors->first('password_confirmation')}}
        </div>
        <br>

        <input class="btn btn-primary" type="submit" value="Save" />

    </form>
</div>
@endsection
