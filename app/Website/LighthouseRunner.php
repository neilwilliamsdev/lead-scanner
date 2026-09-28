<?php

namespace App\Website;

/**
 * Runs a Lighthouse audit against a website.
 */
class LighthouseRunner
{
    /**
     * Runs a Lighthouse audit against the given URL.
     *
     * @param string $url
     * @return string
     */
    public function run(string $url): string
    {
        $binDirectory = '/Users/neilwilliams/Library/Application Support/Herd/config/nvm/versions/node/v22.22.3/bin';

        $nodePath = $binDirectory . '/node';
        $lighthousePath = $binDirectory . '/lighthouse';

        $outputPath = storage_path('app/lighthouse.json');

        $command = sprintf(
            '%s %s %s --output=json --output-path=%s',
            escapeshellarg($nodePath),
            escapeshellarg($lighthousePath),
            escapeshellarg($url),
            escapeshellarg($outputPath)
        );

        exec($command, $output, $exitCode);

        if ($exitCode !== 0 || ! is_file($outputPath)) {
            throw new \RuntimeException('Lighthouse audit failed.');
        }

        $json = file_get_contents($outputPath);

        if ($json === false) {
            throw new \RuntimeException('Unable to read Lighthouse results.');
        }

        return $json;
    }
}