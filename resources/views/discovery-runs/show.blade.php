@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <a
            href="{{ route('discovery-runs.index') }}"
            class="text-sm text-blue-600 hover:text-blue-800 hover:underline"
        >
            ← Discovery Runs
        </a>

        <h1 class="mt-2 text-2xl font-semibold">
            Discovery Run #{{ $discoveryRun->id }}
        </h1>
    </div>

    <div class="mb-8 rounded-lg border border-gray-200 bg-white p-6">
        <dl class="grid gap-5 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">Source</dt>
                <dd class="mt-1">{{ $discoveryRun->source }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">{{ $discoveryRun->status }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Category</dt>
                <dd class="mt-1">{{ $discoveryRun->category ?? '—' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Location</dt>
                <dd class="mt-1">{{ $discoveryRun->location ?? '—' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Radius</dt>
                <dd class="mt-1">
                    {{ $discoveryRun->radius ? $discoveryRun->radius . ' miles' : '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Candidates found</dt>
                <dd class="mt-1">{{ $discoveryRun->candidates_found }}</dd>
            </div>
        </dl>
    </div>

    <div>
        <h2 class="mb-4 text-xl font-semibold">Candidates</h2>

        @if ($discoveryRun->candidates->isEmpty())
            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <p class="text-gray-500">
                    No candidates found.
                </p>
            </div>
        @else
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Business
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Website
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Location
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Status
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @foreach ($discoveryRun->candidates as $candidate)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <a
                                        href="{{ route('candidates.show', $candidate) }}"
                                        class="font-medium text-blue-600 hover:text-blue-800 hover:underline"
                                    >
                                        {{ $candidate->name }}
                                    </a>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $candidate->domain }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $candidate->location ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $candidate->status }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection