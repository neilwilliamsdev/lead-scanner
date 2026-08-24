@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-semibold">Candidates</h1>

        <p class="mt-1 text-sm text-gray-500">
            Businesses discovered but not yet accepted into the CRM.
        </p>
    </div>

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
                            Status
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
                                {{ $candidate->status }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection