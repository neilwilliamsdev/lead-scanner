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
            Start Discovery
        </h1>
    </div>

    <form
        method="POST"
        action="{{ route('discovery-runs.store') }}"
        class="max-w-xl rounded-lg border border-gray-200 bg-white p-6"
    >
        @csrf

        <div class="space-y-6">
            <div>
                <label
                    for="source"
                    class="block text-sm font-medium text-gray-700"
                >
                    Source
                </label>

                <select
                    id="source"
                    name="source"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                >
                    <option value="test">Test</option>
                </select>

                @error('source')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label
                    for="category"
                    class="block text-sm font-medium text-gray-700"
                >
                    Category
                </label>

                <input
                    type="text"
                    id="category"
                    name="category"
                    value="{{ old('category') }}"
                    placeholder="e.g. plumbers"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                >

                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label
                    for="location"
                    class="block text-sm font-medium text-gray-700"
                >
                    Location
                </label>

                <input
                    type="text"
                    id="location"
                    name="location"
                    value="{{ old('location') }}"
                    placeholder="e.g. Cannock"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                >

                @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            >
                Start Discovery
            </button>
        </div>
    </form>
@endsection