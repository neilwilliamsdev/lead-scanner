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

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $business->status }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection