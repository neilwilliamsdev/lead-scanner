# Lead Scanner

Lead Scanner is a Laravel application for discovering potential business leads from external sources, enriching those businesses with website and technology information, and ultimately identifying businesses that may be suitable prospects.

The project is being developed incrementally, with an emphasis on keeping the architecture extensible as additional discovery sources and technologies are added.

## Current status

The current discovery pipeline is:

```text
Discovery Run
     ↓
Discovery Source
     ↓
Google Places
     ↓
Business
     ↓
Candidate
     ↓
Website reachability check
     ↓
Technology detection
```

Currently implemented:

- Discovery runs
- Queue-based discovery jobs
- `DiscoverySource` contract
- Google Places discovery source
- Candidate creation
- Business creation and deduplication
- Website reachability checking
- Technology model
- Candidate/technology many-to-many relationship
- WordPress technology detection
- Discovery run UI
- Asynchronous queue processing

## Architecture

### Discovery Runs

A `DiscoveryRun` represents a single discovery operation.

It stores information such as:

- Source
- Category
- Location
- Radius
- Status
- Number of candidates found
- Start/completion timestamps
- Errors

A run may produce many candidates.

```text
DiscoveryRun
    └── Candidates
```

The discovery process is performed by the `DiscoverBusinesses` queued job.

### Discovery Sources

Discovery sources are defined by the `DiscoverySource` contract.

This allows the application to work with different external discovery providers without coupling the job directly to a particular provider.

For example:

```text
DiscoverySource
      │
      ├── GooglePlacesSource
      ├── FutureSource
      └── FutureSource
```

The job depends on the contract rather than directly on Google Places.

Laravel's service container resolves the concrete implementation when the job's `handle()` method is executed.

This means the job can continue to use:

```php
DiscoverySource $source
```

without needing to know which discovery provider is currently being used.

## Google Places

`GooglePlacesSource` currently uses Google Places to search for businesses based on:

- Category
- Location

The source returns normalised business data rather than exposing the Google API response throughout the application.

The current data includes:

- Business name
- Website
- Source ID

The number of results can be limited by the Google Places request.

Google's source ID is retained on the Candidate so that the original discovery source can be identified.

## Businesses vs Candidates

A key architectural distinction is made between a **Business** and a **Candidate**.

### Business

A Business represents the actual company.

It is intended to be the canonical record for that company and can exist independently of any particular discovery run.

Current fields include:

- Name
- Website
- Domain
- Industry
- Location
- Contact details
- Status
- Notes

### Candidate

A Candidate represents a business discovered during a particular discovery run.

It retains discovery-specific information such as:

- Discovery run
- Business
- Source
- Source ID
- Website reachability
- Technology information

The relationship is:

```text
Business
    ↑
    │
Candidate
    ↑
    │
DiscoveryRun
```

This allows the same Business to appear in multiple discovery runs without creating duplicate Business records.

## Business deduplication

Businesses are currently deduplicated using their domain.

For example:

```text
https://example.com
http://example.com/
https://www.example.com
```

can be normalised around the same domain identity.

The current implementation uses:

```php
Business::firstOrCreate(
    ['domain' => $domain],
    [...]
);
```

This means an existing Business is reused when the same domain is discovered again.

A subsequent discovery can therefore create another Candidate while continuing to point to the same Business.

```text
Run #1
    ↓
Candidate #1 ──→ Business #1

Run #2
    ↓
Candidate #2 ──→ Business #1
```

Domain-based identity is intentionally a simple first implementation and may be made more sophisticated later.

## Queue processing

Discovery is performed asynchronously using Laravel queues.

The controller dispatches:

```php
DiscoverBusinesses::dispatch($discoveryRun);
```

The job receives the `DiscoveryRun` through its constructor.

Services such as `DiscoverySource` and `WebsiteChecker` are injected into the job's `handle()` method.

This is important because queued jobs are serialised when dispatched. Services should generally be resolved when the job is actually executed rather than stored as part of the queued job payload.

The worker is run locally with:

```bash
php artisan queue:work
```

When changing job code during development, restart the worker so that it loads the latest version.

## Website checking

`WebsiteChecker` is responsible for determining whether a candidate's website can be reached.

The checker currently returns:

```php
[
    'reachable' => true,
    'status' => 200,
]
```

or, for example:

```php
[
    'reachable' => true,
    'status' => 403,
]
```

An HTTP response means the server was reachable, even if the response is an error status such as `403`.

A connection failure, timeout, DNS failure, etc. results in:

```php
[
    'reachable' => false,
    'status' => null,
]
```

The Candidate stores the resulting boolean in `website_reachable`.

Technology detection is currently only attempted when the website is reachable.

## Technology detection

Technology detection is separated from business discovery.

The application currently has a WordPress detector:

```text
Technology
    ↓
WordPressDetector
```

The detector accepts a website URL and returns a `Technology` value when it identifies WordPress.

For example:

```php
$detector->detect('https://wordpress.org');
```

returns a technology object representing:

```text
WordPress
```

An unknown technology returns `null`.

The intention is to extend this architecture with additional detectors in the future rather than hard-coding WordPress-specific logic into the discovery process.

Potential future detectors could include:

- WordPress
- Shopify
- Wix
- Squarespace
- Webflow
- Drupal
- Magento
- Custom PHP
- Other technologies

## Technology storage

Technologies are stored independently in the `technologies` table.

Candidates and technologies have a many-to-many relationship:

```text
Candidate
    │
    ├── WordPress
    ├── WooCommerce
    └── Other technology
```

This is intentionally more flexible than storing a single technology directly on the Candidate.

A Candidate may use multiple technologies.

The relationship is implemented through:

```text
candidate_technology
```

The `Technology` model uses a slug for stable identification.

Technologies are created with:

```php
Technology::firstOrCreate(
    ['slug' => Str::slug($technology->name)],
    ['name' => $technology->name]
);
```

## Current discovery flow

The current `DiscoverBusinesses` job performs the following:

1. Marks the Discovery Run as running.
2. Requests businesses from the configured `DiscoverySource`.
3. Extracts each business's domain.
4. Finds or creates the corresponding Business.
5. Creates a Candidate linked to the Business and Discovery Run.
6. Checks whether the website is reachable.
7. Stores the reachability result.
8. Runs technology detection when the website is reachable.
9. Creates/reuses the detected Technology.
10. Attaches the Technology to the Candidate.
11. Marks the Discovery Run as completed.
12. Stores the number of candidates found.

## Development philosophy

The application is deliberately being built in small, separable pieces.

The main architectural goals are:

- Keep external providers behind contracts.
- Keep discovery separate from enrichment.
- Keep Businesses separate from discovery Candidates.
- Avoid duplicate Business records.
- Make technology detection extensible.
- Use queued jobs for potentially slow external operations.
- Keep provider-specific API responses out of the rest of the application.
- Prefer small services with a single responsibility.

The intention is to avoid building the entire application around Google Places or WordPress so that additional providers and technologies can be introduced later.

## Current project direction

The immediate next architectural area is the **technology detection system**.

WordPress detection currently works, but the goal is to make the detector system extensible so that adding another technology does not require modifying the discovery job itself.

The eventual architecture should allow something closer to:

```text
Candidate
    ↓
Technology Detection
    ↓
┌──────────────────────────┐
│ WordPressDetector        │
│ ShopifyDetector          │
│ WixDetector              │
│ SquarespaceDetector      │
│ ...                      │
└──────────────────────────┘
    ↓
Detected technologies
```

This should allow the application to grow beyond simply finding WordPress businesses while keeping the discovery pipeline unchanged.
