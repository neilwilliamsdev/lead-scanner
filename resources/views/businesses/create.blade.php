@extends('layouts.app')

@section('content')
    <h1>Add Business</h1>

    <form method="POST" action="{{ route('businesses.store') }}">
        @csrf

        @include('businesses._form')

        <button type="submit">Save business</button>
    </form>
@endsection