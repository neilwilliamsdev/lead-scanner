@extends('layouts.app')

@section('content')
    <h1>Edit Business</h1>

    <form method="POST" action="{{ route('businesses.update', $business) }}">
        @csrf
        @method('PUT')

        @include('businesses._form')

        <button type="submit">Update business</button>
    </form>
@endsection