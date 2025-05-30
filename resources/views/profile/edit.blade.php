@extends('layouts.app')

     @section('content')
         <div class="container">
             <h2>Edit Profile</h2>
             @if (session('status'))
                 <div class="alert alert-success">{{ session('status') }}</div>
             @endif
             <form method="POST" action="{{ route('profile.update') }}">
                 @csrf
                 @method('PATCH')
                 <div class="mb-3">
                     <label for="name" class="form-label">Name</label>
                     <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                 </div>
                 <div class="mb-3">
                     <label for="email" class="form-label">Email</label>
                     <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                 </div>
                 <button type="submit" class="btn btn-primary">Update</button>
             </form>
         </div>
     @endsection