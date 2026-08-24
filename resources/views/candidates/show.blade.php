@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <a
            href="{{ route('businesses.index') }}"
            class="text-sm text-blue-600 hover:text-blue-800 hover:underline"
        >
            ← Businesses
        </a>

        <h1 class="mt-2 text-2xl font-semibold">
            {{ $candidate->name }}
        </h1>
    </div>

    <div class="mb-8 rounded-lg border border-gray-200 bg-white p-6">
        <dl class="grid gap-5 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">Website</dt>
                <dd class="mt-1">
                    <a
                        href="{{ $candidate->website }}"
                        target="_blank"
                        rel="noopener"
                        class="text-blue-600 hover:underline"
                    >
                        {{ $candidate->website }}
                    </a>
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Domain</dt>
                <dd class="mt-1">
                    {{ $candidate->domain }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Location</dt>
                <dd class="mt-1">
                    {{ $candidate->location ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Category</dt>
                <dd class="mt-1">
                    {{ $candidate->category ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Source</dt>
                <dd class="mt-1">
                    {{ $candidate->source }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                    {{ $candidate->status }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Website reachable</dt>
                <dd class="mt-1">
                    @if ($candidate->website_reachable === null)
                        Unknown
                    @elseif ($candidate->website_reachable)
                        Yes
                    @else
                        No
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">WordPress</dt>
                <dd class="mt-1">
                    @if ($candidate->is_wordpress === null)
                        Unknown
                    @elseif ($candidate->is_wordpress)
                        Yes
                    @else
                        No
                    @endif
                </dd>
            </div>
        </dl>
    </div>

    @if ($candidate->status === 'new')
        <div class="flex gap-3">
            <form
                method="POST"
                action="{{ route('candidates.accept', $candidate) }}"
            >
                @csrf

                <button
                    type="submit"
                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    Accept candidate
                </button>
            </form>
        </div>
    @elseif ($candidate->status === 'accepted' && $candidate->business)
        <div class="rounded-lg border border-green-200 bg-green-50 p-4">
            <p class="text-sm text-green-800">
                This candidate has been accepted as a business.
            </p>

            <a
                href="{{ route('businesses.show', $candidate->business) }}"
                class="mt-2 inline-block text-sm font-semibold text-green-700 hover:underline"
            >
                View business →
            </a>
        </div>
    @elseif ($candidate->status === 'rejected')
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
            <p class="text-sm text-gray-600">
                This candidate has been rejected.
            </p>
        </div>
    @endif
@endsection