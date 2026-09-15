<?php

namespace App\Website;

use Illuminate\Support\Facades\Http;

/**
 * Class WebsiteAnalyzer
 *
 * Analyzes a website and returns structured analysis results.
 */
class WebsiteAnalyzer
{
    /**
     * Analyses a website and returns structured analysis results.
     *
     * @param string $url
     * @return array<AnalysisResult>
     */
    public function analyse(string $url): array
    {
        $https = str_starts_with(strtolower($url), 'https://');

        try {
            // Attempt to fetch the website content
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Lead Scanner/1.0',
                ])
                ->get($url);

            $html = $response->body();
            $title = $this->extractTitle($html);
            $metaDescription = $this->extractMetaDescription($html);
            $hasViewport = $this->hasViewport($html);

            // Return the analysis results from the website content.
            return [
                new AnalysisResult(
                    check: 'HTTPS',
                    passed: $https,
                    message: $https
                        ? 'Website uses HTTPS.'
                        : 'Website does not use HTTPS.',
                    score: $https ? 0 : -10,
                ),

                new AnalysisResult(
                    check: 'Page title',
                    passed: $title !== null,
                    message: $title !== null
                        ? 'Page has a title.'
                        : 'Page is missing a title.',
                    score: $title !== null ? 0 : -10,
                ),

                new AnalysisResult(
                    check: 'Meta description',
                    passed: $metaDescription !== null,
                    message: $metaDescription !== null
                        ? 'Page has a meta description.'
                        : 'Page is missing a meta description.',
                    score: $metaDescription !== null ? 0 : -10,
                ),
                new AnalysisResult(
                    check: 'Viewport',
                    passed: $hasViewport,
                    message: $hasViewport
                        ? 'Page has a viewport meta tag.'
                        : 'Page is missing a viewport meta tag.',
                    score: $hasViewport ? 0 : -10,
                ),
            ];
        } catch (\Throwable $e) {
            // Return a default response indicating an error occurred while fetching the website content.
            return [
                new AnalysisResult(
                    check: 'Website',
                    passed: false,
                    message: 'Website could not be analysed.',
                    score: -20,
                ),
            ];
        }
    }

    /**
     * Extracts the title from the given HTML content.
     *
     * @param string $html
     * @return string|null
     */
    private function extractTitle(string $html): ?string
    {
        if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $matches)) {
            return html_entity_decode(trim(strip_tags($matches[1])));
        }

        return null;
    }

    /**
     * Extracts the meta description from the given HTML content.
     *
     * @param string $html
     * @return string|null
     */
    private function extractMetaDescription(string $html): ?string
    {
        if (preg_match(
            '/<meta[^>]+name=["\']description["\'][^>]+content=["\'](.*?)["\']/is',
            $html,
            $matches
        )) {
            return html_entity_decode(trim($matches[1]));
        }

        return null;
    }

    /**
     * Checks whether the page has a mobile viewport meta tag.
     *
     * @param string $html
     * @return bool
     */
    private function hasViewport(string $html): bool
    {
        return preg_match(
            '/<meta[^>]+name=["\']viewport["\']/is',
            $html
        ) === 1;
    }
}