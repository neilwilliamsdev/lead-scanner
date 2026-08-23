<div>
    <label for="name">Business name</label>
    <input
        type="text"
        id="name"
        name="name"
        value="{{ old('name', $business->name ?? '') }}"
        required
    >

    @error('name')
        <p>{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="website">Website</label>
    <input
        type="url"
        id="website"
        name="website"
        value="{{ old('website', $business->website ?? '') }}"
        required
    >

    @error('website')
        <p>{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="industry">Industry</label>
    <input
        type="text"
        id="industry"
        name="industry"
        value="{{ old('industry', $business->industry ?? '') }}"
    >
</div>

<div>
    <label for="location">Location</label>
    <input
        type="text"
        id="location"
        name="location"
        value="{{ old('location', $business->location ?? '') }}"
    >
</div>

<div>
    <label for="contact_name">Contact name</label>
    <input
        type="text"
        id="contact_name"
        name="contact_name"
        value="{{ old('contact_name', $business->contact_name ?? '') }}"
    >
</div>

<div>
    <label for="contact_email">Contact email</label>
    <input
        type="email"
        id="contact_email"
        name="contact_email"
        value="{{ old('contact_email', $business->contact_email ?? '') }}"
    >
</div>

<div>
    <label for="notes">Notes</label>
    <textarea id="notes" name="notes">{{ old('notes', $business->notes ?? '') }}</textarea>
</div>