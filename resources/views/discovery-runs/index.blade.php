@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-semibold">Discovery Runs</h1>

        <p class="mt-1 text-sm text-gray-500">
            Previous and current business discovery runs.
        </p>
    </div>

    @if ($discoveryRuns->isEmpty())
        <div class="rounded-lg border border-gray-200 bg-white p-8 text-center">
            <p class="text-gray-500">No discovery runs yet.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Run
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Source
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Search
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Candidates
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Date
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @foreach ($discoveryRuns as $run)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">
                                <a
                                    href="{{ route('discovery-runs.show', $run) }}"
                                    class="text-blue-600 hover:text-blue-800 hover:underline"
                                >
                                    #{{ $run->id }}
                                </a>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $run->source }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $run->category ?? '—' }}

                                @if ($run->location)
                                    <span class="text-gray-400">
                                        — {{ $run->location }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $run->status }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $run->candidates_found }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $run->created_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection