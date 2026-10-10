@extends('layouts.app')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Targets</h1>
            <p class="mt-1 text-sm text-gray-500">
                Businesses you've accepted as potential leads.
            </p>
        </div>

        <a
            href="{{ route('businesses.create') }}"
            class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
        >
            Add business
        </a>
    </div>

    @if ($businesses->isEmpty())
        <div class="rounded-lg border border-gray-200 bg-white p-8 text-center">
            <p class="text-gray-500">No businesses yet.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Name
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
                    @foreach ($businesses as $business)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <a
                                    href="{{ route('businesses.show', $business) }}"
                                    class="font-medium text-blue-600 hover:text-blue-800 hover:underline"
                                >
                                    {{ $business->name }}
                                </a>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $business->website }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $business->location ?? '—' }}
                            </td>

                            @php
                                $statusClasses = [
                                    'new' => 'bg-gray-100 text-gray-700',
                                    'reviewing' => 'bg-blue-100 text-blue-800',
                                    'ready_to_contact' => 'bg-indigo-100 text-indigo-800',
                                    'contacted' => 'bg-purple-100 text-purple-800',
                                    'interested' => 'bg-green-100 text-green-800',
                                    'follow_up' => 'bg-amber-100 text-amber-800',
                                    'won' => 'bg-emerald-100 text-emerald-800',
                                    'lost' => 'bg-red-100 text-red-800',
                                ];
                            @endphp

                            <td class="px-6 py-4">
                                <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $statusClasses[$business->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ str($business->status)->replace('_', ' ')->title() }}
                                </span>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection