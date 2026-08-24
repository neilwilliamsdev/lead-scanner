@extends('layouts.app')

@section('content')
    <div class="mb-8 flex items-start justify-between">
        <div>
            <a
                href="{{ route('businesses.index') }}"
                class="text-sm text-blue-600 hover:text-blue-800 hover:underline"
            >
                ← Businesses
            </a>

            <h1 class="mt-2 text-2xl font-semibold">
                {{ $business->name }}
            </h1>
        </div>

        <a
            href="{{ route('businesses.edit', $business) }}"
            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
        >
            Edit business
        </a>
    </div>

    <div class="mb-8 rounded-lg border border-gray-200 bg-white p-6">
        <dl class="grid gap-5 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">Website</dt>
                <dd class="mt-1">
                    <a
                        href="{{ $business->website }}"
                        target="_blank"
                        rel="noopener"
                        class="text-blue-600 hover:underline"
                    >
                        {{ $business->website }}
                    </a>
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                    {{ $business->status }}
                </dd>
            </div>

            @if ($business->industry)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Industry</dt>
                    <dd class="mt-1">
                        {{ $business->industry }}
                    </dd>
                </div>
            @endif

            @if ($business->location)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                    <dd class="mt-1">
                        {{ $business->location }}
                    </dd>
                </div>
            @endif

            @if ($business->contact_name)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Contact</dt>
                    <dd class="mt-1">
                        {{ $business->contact_name }}
                    </dd>
                </div>
            @endif

            @if ($business->contact_email)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1">
                        <a
                            href="mailto:{{ $business->contact_email }}"
                            class="text-blue-600 hover:underline"
                        >
                            {{ $business->contact_email }}
                        </a>
                    </dd>
                </div>
            @endif
        </dl>
    </div>

    @if ($business->notes)
        <div class="mb-8 rounded-lg border border-gray-200 bg-white p-6">
            <h2 class="mb-3 text-lg font-semibold">Notes</h2>

            <p class="whitespace-pre-line text-gray-600">
                {{ $business->notes }}
            </p>
        </div>
    @endif

    <div class="mb-8">
        <h2 class="mb-4 text-xl font-semibold">Scans</h2>

        @if ($business->scans->isEmpty())
            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <p class="text-gray-500">No scans yet.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                <ul class="divide-y divide-gray-200">
                    @foreach ($business->scans as $scan)
                        <li class="flex items-center justify-between px-6 py-4">
                            <a
                                href="{{ route('scans.show', $scan) }}"
                                class="font-medium text-blue-600 hover:text-blue-800 hover:underline"
                            >
                                Scan #{{ $scan->id }}
                            </a>

                            <div class="text-sm text-gray-500">
                                {{ $scan->status }}

                                @if ($scan->score !== null)
                                    <span class="ml-3">
                                        Score: {{ $scan->score }}
                                    </span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="border-t border-gray-200 pt-6">
        <form
            method="POST"
            action="{{ route('businesses.destroy', $business) }}"
            onsubmit="return confirm('Are you sure you want to delete this business?')"
        >
            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="rounded-md border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50"
            >
                Delete business
            </button>
        </form>
    </div>
@endsection