@extends('layouts.app')

@section('content')
    <div>
        <a href="{{ route('businesses.index') }}">← Businesses</a>

        <h1>{{ $business->name }}</h1>

        <p>
            <a href="{{ $business->website }}" target="_blank" rel="noopener">
                {{ $business->website }}
            </a>
        </p>

        <p>
            <strong>Status:</strong>
            {{ $business->status }}
        </p>

        @if ($business->industry)
            <p>
                <strong>Industry:</strong>
                {{ $business->industry }}
            </p>
        @endif

        @if ($business->location)
            <p>
                <strong>Location:</strong>
                {{ $business->location }}
            </p>
        @endif

        @if ($business->contact_name)
            <p>
                <strong>Contact:</strong>
                {{ $business->contact_name }}
            </p>
        @endif

        @if ($business->contact_email)
            <p>
                <strong>Email:</strong>
                <a href="mailto:{{ $business->contact_email }}">
                    {{ $business->contact_email }}
                </a>
            </p>
        @endif

        @if ($business->notes)
            <div>
                <h2>Notes</h2>
                <p>{{ $business->notes }}</p>
            </div>
        @endif

        <p>
            <a href="{{ route('businesses.edit', $business) }}">
                Edit business
            </a>
        </p>

        <form
            method="POST"
            action="{{ route('businesses.destroy', $business) }}"
        >
            @csrf
            @method('DELETE')

            <button type="submit">
                Delete business
            </button>
        </form>
    </div>
@endsection