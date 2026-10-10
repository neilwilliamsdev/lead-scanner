<div class="max-w-2xl space-y-5">

    <div>
        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-900">
            Business name
        </label>
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name', $business->name ?? '') }}"
            class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600"
        >
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="website" class="mb-1.5 block text-sm font-medium text-gray-900">
            Website
        </label>
        <input
            type="url"
            name="website"
            id="website"
            value="{{ old('website', $business->website ?? '') }}"
            class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600"
        >
        @error('website')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="industry" class="mb-1.5 block text-sm font-medium text-gray-900">
                Industry
            </label>
            <input
                type="text"
                name="industry"
                id="industry"
                value="{{ old('industry', $business->industry ?? '') }}"
                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600"
            >
            @error('industry')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="location" class="mb-1.5 block text-sm font-medium text-gray-900">
                Location
            </label>
            <input
                type="text"
                name="location"
                id="location"
                value="{{ old('location', $business->location ?? '') }}"
                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600"
            >
            @error('location')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="contact_name" class="mb-1.5 block text-sm font-medium text-gray-900">
                Contact name
            </label>
            <input
                type="text"
                name="contact_name"
                id="contact_name"
                value="{{ old('contact_name', $business->contact_name ?? '') }}"
                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600"
            >
            @error('contact_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="contact_email" class="mb-1.5 block text-sm font-medium text-gray-900">
                Contact email
            </label>
            <input
                type="email"
                name="contact_email"
                id="contact_email"
                value="{{ old('contact_email', $business->contact_email ?? '') }}"
                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600"
            >
            @error('contact_email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="status" class="mb-1.5 block text-sm font-medium text-gray-900">
            Status
        </label>
        <select name="status" id="status" class="py-2 bg-white rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"> 
            @foreach (
                [ 
                    'new' => 'New', 
                    'reviewing' => 'Reviewing', 
                    'ready-to-contact' => 'Ready to contact', 
                    'contacted' => 'Contacted', 
                    'interested' => 'Interested', 
                    'follow-up' => 'Follow-up', 
                    'won' => 'Won', 
                    'lost' => 'Lost', 
                ] 
                as $value => $label) 
                <option value="{{ $value }}" @selected($business->status === $value)> {{ $label }} </option> 
            @endforeach 
        </select>
    </div>

    <div>
        <label for="notes" class="mb-1.5 block text-sm font-medium text-gray-900">
            Notes
        </label>
        <textarea
            name="notes"
            id="notes"
            rows="4"
            class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600"
        >{{ old('notes', $business->notes ?? '') }}</textarea>
        @error('notes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

</div>