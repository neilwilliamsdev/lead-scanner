@extends('layouts.app')

@section('content')
    <a href="{{ route('businesses.index') }}">
        ← Businesses
    </a>

    <h1>{{ $candidate->name }}</h1>

    <p>
        <strong>Website:</strong>
        <a href="{{ $candidate->website }}" target="_blank" rel="noopener">
            {{ $candidate->website }}
        </a>
    </p>

    <p>
        <strong>Domain:</strong>
        {{ $candidate->domain }}
    </p>

    <p>
        <strong>Location:</strong>
        {{ $candidate->location ?? '—' }}
    </p>

    <p>
        <strong>Category:</strong>
        {{ $candidate->category ?? '—' }}
    </p>

    <p>
        <strong>Status:</strong>
        {{ $candidate->status }}
    </p>

    <p>
        <strong>Website reachable:</strong>
        {{ $candidate->website_reachable === null ? 'Unknown' : ($candidate->website_reachable ? 'Yes' : 'No') }}
    </p>

    <p>
        <strong>WordPress:</strong>
        {{ $candidate->is_wordpress === null ? 'Unknown' : ($candidate->is_wordpress ? 'Yes' : 'No') }}
    </p>

    @if ($candidate->status === 'new')
        <form method="POST" action="{{ route('candidates.accept', $candidate) }}">
            @csrf

            <button type="submit">
                Accept candidate
            </button>
        </form>
    @endif
    
@endsection