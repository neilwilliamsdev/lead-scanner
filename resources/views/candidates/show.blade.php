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
                <dt class="text-sm font-medium text-gray-500">Technologies</dt>
                <dd class="mt-1">
                    @if ($candidate->technologies->isNotEmpty())
                        {{ $candidate->technologies->pluck('name')->join(', ') }}
                    @else
                        Unknown
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Website score</dt>
                <dd class="mt-1 text-lg font-semibold">
                    {{ $candidate->score() }}/100
                </dd>
            </div>
        </dl>
    </div>

    <div class="mb-8 rounded-lg border border-gray-200 bg-white p-6">
        <div>
            <h2 class="text-lg font-semibold">Website analysis</h2>

            {{-- Basic website checks --}}
            <h3 class="mt-4 font-medium">Basic checks</h3>

            <dl class="mt-3 space-y-3">
                @foreach ($candidate->scanResults->filter(fn ($result) => ! str_starts_with($result->check, 'Lighthouse: ')) as $result)
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
                $lighthouseCategories = $candidate->scanResults->filter(
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

                    $issues = $candidate->scanResults->filter(
                        fn ($result) => str_starts_with($result->check, $issuePrefix)
                    );
                @endphp

                <div class="mt-4 rounded-md border border-gray-200 p-4">
                    <div class="flex items-center justify-between gap-4">
                        <h4 class="font-semibold">{{ $categoryName }}</h4>

                        <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $scoreClass }}">
                            {{ $categoryScore !== null ? $categoryScore . '/100' : 'N/A' }}
                        </span>
                    </div>

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
                </div>
            @empty
                <p class="mt-3 text-sm text-gray-600">
                    Lighthouse results are not available yet.
                </p>
            @endforelse
        </div>
    </div>

    @if ($candidate->status === 'new')
        <div class="flex gap-3">
            <form method="POST" action="{{ route('candidates.accept', $candidate) }}">
                @csrf

                <button
                    type="submit"
                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 hover:cursor-pointer"
                >
                    Accept candidate
                </button>
            </form>
            <form action="{{ route('candidates.reject', $candidate) }}" method="POST">
            @csrf

            <button 
                type="submit"                     
                class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 hover:cursor-pointer"
            >
                Reject Candidate
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