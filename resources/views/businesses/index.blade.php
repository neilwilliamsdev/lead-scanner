@extends('layouts.app')

@section('content')
    <div>
        <h1>Businesses</h1>

        <a href="{{ route('businesses.create') }}">
            Add business
        </a>
    </div>

    @if ($businesses->isEmpty())
        <p>No businesses yet.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Website</th>
                    <th>Location</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($businesses as $business)
                    <tr>
                        <td>
                            <a href="{{ route('businesses.show', $business) }}">
                                {{ $business->name }}
                            </a>
                        </td>
                        <td>{{ $business->website }}</td>
                        <td>{{ $business->location }}</td>
                        <td>{{ $business->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection