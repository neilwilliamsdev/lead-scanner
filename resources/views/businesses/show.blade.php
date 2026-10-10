@extends('layouts.app')

@section('content')
    <div class="mb-8 flex items-start justify-between">
        <div>
            <a
                href="{{ route('businesses.index') }}"
                class="text-sm text-blue-600 hover:text-blue-800 hover:underline"
            >
                ← Targets
            </a>

            <h1 class="mt-2 text-2xl font-semibold">
                {{ $business->name }}
            </h1>
        </div>

        <a
            href="{{ route('businesses.edit', $business) }}"
            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
        >
            Edit target
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
                    <span class="capitalize rounded-full px-3 py-1 text-sm font-semibold {{ $business->status_classes }}">
                        {{ $business->status }}
                    </span>
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

    <div class="mb-8 rounded-lg border border-gray-200 bg-white p-6">
        <div>
            <h2 class="text-lg font-semibold">Website analysis</h2>

            {{-- Basic website checks --}}
            <h3 class="mt-4 font-medium">Basic checks</h3>

            <dl class="flex flex-wrap justify-between gap-4 mt-3 space-y-3">
                @foreach ($business->websiteCheckResults as $result)
                    <div>
                        <dt class="font-medium">{{ $result->check }}</dt>
                        <dd class="text-sm text-gray-600">
                            {{ $result->message }}
                            ({{ $result->score }})
                        </dd>
                    </div>
                @endforeach
            </dl>

            {{-- Lighthouse results --}}
            <h3 class="mt-6 font-medium">Lighthouse audit</h3>

            @php
                $lighthouseCategories = $business->lighthouseResults->filter(
                    fn ($result) =>
                        str_starts_with($result->check, 'Lighthouse: ')
                        && ! str_contains($result->check, ' - ')
                );
            @endphp

            @forelse ($lighthouseCategories as $category)
                @php
                    $categoryName = substr($category->check, strlen('Lighthouse: '));
                    $issuePrefix = $category->check . ' - ';

                    $categoryScore = isset($category->details['score'])
                        ? round($category->details['score'] * 100)
                        : null;

                    $scoreClass = match (true) {
                        $categoryScore === null => 'bg-gray-100 text-gray-600',
                        $categoryScore >= 90 => 'bg-green-100 text-green-800',
                        $categoryScore >= 50 => 'bg-amber-100 text-amber-800',
                        default => 'bg-red-100 text-red-800',
                    };

                    $issues = $business->lighthouseResults->filter(
                        fn ($result) => str_starts_with($result->check, $issuePrefix)
                    );
                @endphp

                <details class="mt-4 rounded-md border border-gray-200 p-4">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400">▸</span>
                            <h4 class="font-semibold">{{ $categoryName }}</h4>
                        </div>

                        <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $scoreClass }}">
                            {{ $categoryScore !== null ? $categoryScore . '/100' : 'N/A' }}
                        </span>
                    </summary>

                    @if ($issues->isNotEmpty())
                        <ul class="mt-3 space-y-3">
                            @foreach ($issues as $issue)
                                <li class="border-t border-gray-100 pt-3">
                                    <details>
                                        <summary class="cursor-pointer list-none">
                                            <div class="flex items-center justify-between gap-4">
                                                <span class="text-sm font-medium">
                                                    {{ $issue->details['title'] ?? substr($issue->check, strlen($issuePrefix)) }}
                                                </span>

                                                @if (! empty($issue->details['displayValue']))
                                                    <span class="text-sm font-medium text-gray-600">
                                                        {{ $issue->details['displayValue'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        </summary>

                                        @if (($issue->details['details']['type'] ?? null) === 'table')
                                            @php
                                                $items = $issue->details['details']['items'] ?? [];
                                            @endphp

                                            @if (! empty($items))
                                                <div class="mt-3 space-y-3">
                                                    @foreach ($items as $item)
                                                        <div class="rounded-md bg-gray-50 p-3 text-sm">

                                                            {{-- Resource-based finding --}}
                                                            @if (! empty($item['url']))
                                                                <p class="break-all font-medium">
                                                                    {{ $item['url'] }}
                                                                </p>

                                                                <dl class="mt-2 grid grid-cols-2 gap-x-4 gap-y-1 text-gray-600">
                                                                    @if (isset($item['totalBytes']))
                                                                        <div>
                                                                            <dt class="font-medium">Size</dt>
                                                                            <dd>{{ number_format($item['totalBytes'] / 1024, 1) }} KiB</dd>
                                                                        </div>
                                                                    @endif

                                                                    @if (isset($item['wastedBytes']))
                                                                        <div>
                                                                            <dt class="font-medium">Potential saving</dt>
                                                                            <dd>{{ number_format($item['wastedBytes'] / 1024, 1) }} KiB</dd>
                                                                        </div>
                                                                    @endif
                                                                </dl>

                                                            {{-- DOM element finding --}}
                                                            @elseif (! empty($item['node']))
                                                                @if (! empty($item['node']['selector']))
                                                                    <p class="font-medium">Affected element</p>

                                                                    <p class="mt-1 break-all font-mono text-xs text-gray-600">
                                                                        {{ $item['node']['selector'] }}
                                                                    </p>
                                                                @endif

                                                                @if (! empty($item['node']['snippet']))
                                                                    <p class="mt-3 font-medium">HTML</p>

                                                                    <pre class="mt-1 overflow-x-auto rounded bg-white p-2 text-xs text-gray-600">{{ $item['node']['snippet'] }}</pre>
                                                                @endif

                                                            {{-- Other table types --}}
                                                            @else
                                                                <dl class="space-y-2 text-gray-600">
                                                                    @foreach ($item as $key => $value)
                                                                        @if (is_scalar($value) && $value !== '')
                                                                            <div>
                                                                                <dt class="font-medium text-gray-900">
                                                                                    {{ \Illuminate\Support\Str::headline($key) }}
                                                                                </dt>

                                                                                <dd class="mt-1 break-all">
                                                                                    {{ $value }}
                                                                                </dd>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </dl>
                                                            @endif

                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @else
                                            @if (! empty($issue->details['displayValue']))
                                                <p class="mt-3 text-sm text-gray-600">
                                                    {{ $issue->details['displayValue'] }}
                                                </p>
                                            @endif
                                        @endif
                                    </details>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-2 text-sm text-gray-600">
                            No contributing issues recorded for this category.
                        </p>
                    @endif
                </details>
            @empty
                <p class="mt-3 text-sm text-gray-600">
                    Lighthouse results are not available yet.
                </p>
            @endforelse
        </div>
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