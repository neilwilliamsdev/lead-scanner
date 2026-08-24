@extends('layouts.app')

@section('content')
    <a href="{{ route('businesses.show', $scan->business) }}">
        ← {{ $scan->business->name }}
    </a>

    <h1>Scan</h1>

    <p>
        <strong>Status:</strong>
        {{ $scan->status }}
    </p>

    @if ($scan->score !== null)
        <p>
            <strong>Score:</strong>
            {{ $scan->score }}
        </p>
    @endif

    @if ($scan->started_at)
        <p>
            <strong>Started:</strong>
            {{ $scan->started_at }}
        </p>
    @endif

    @if ($scan->completed_at)
        <p>
            <strong>Completed:</strong>
            {{ $scan->completed_at }}
        </p>
    @endif
@endsection