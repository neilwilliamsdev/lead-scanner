@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Edit Business</h1>

    <form method="POST" action="{{ route('businesses.update', $business) }}">
        @csrf
        @method('PUT')

        @include('businesses._form')

        <button type="submit" class="mt-4 cursor-pointer rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">Update business</button>
    </form>
@endsection