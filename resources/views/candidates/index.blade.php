@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-semibold">Candidates</h1>

        <p class="mt-1 text-sm text-gray-500">
            Businesses discovered and awaiting review.        
        </p>
    </div>

    <form method="GET" action="{{ route('candidates.index') }}" class="mb-6 rounded-lg border border-gray-200 bg-white p-4">
        <div class="flex items-end gap-4">
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">
                    Location
                </label>

                <select
                    name="location"
                    id="location"
                    class="mt-1 rounded-md border-gray-300 text-sm"
                >
                    <option value="">All locations</option>

                    @foreach ($locations as $location)
                        <option value="{{ $location }}" @selected(request('location') === $location)>
                            {{ $location }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="technology" class="block text-sm font-medium text-gray-700">
                    Technology
                </label>

                <select
                    name="technology"
                    id="technology"
                    class="mt-1 rounded-md border-gray-300 text-sm"
                >
                    <option value="">All technologies</option>

                    @foreach ($technologies as $technology)
                        <option value="{{ $technology->id }}" @selected(request('technology') == $technology->id)>
                            {{ $technology->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button
                type="submit"
                class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800"
            >
                Filter
            </button>

            @if (request()->filled('location'))
                <a
                    href="{{ route('candidates.index') }}"
                    class="px-2 py-2 text-sm text-gray-600 hover:text-gray-900"
                >
                    Clear
                </a>
            @endif
        </div>
    </form>

    @if ($candidates->isEmpty())
        <div class="rounded-lg border border-gray-200 bg-white p-8 text-center">
            <p class="text-gray-500">No candidates yet.</p>
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
                            Tech
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Score
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @foreach ($candidates as $candidate)
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
                                {{ $candidate->technologies->pluck('name')->join(', ') ?: '—' }}
                            </td>

                            @php
                                $statusClass = match ($candidate->status) {
                                    'accepted' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp

                            <td class="px-6 py-4">
                                <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $statusClass }}">
                                    {{ ucfirst($candidate->status) }}
                                </span>
                            </td>
                            @php
                                $score = $candidate->score();

                                $scoreClass = match (true) {
                                    $score >= 90 => 'bg-green-100 text-green-800',
                                    $score >= 50 => 'bg-amber-100 text-amber-800',
                                    default => 'bg-red-100 text-red-800',
                                };
                            @endphp

                            <td class="px-6 py-4">
                                <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $scoreClass }}">
                                    {{ $score }}/100
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection