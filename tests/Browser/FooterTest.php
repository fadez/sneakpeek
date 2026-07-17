<?php

declare(strict_types=1);
use Tests\TestCase;

/**
 * @return array{author: array{name: string, url: string}, repository: array{url: string}}
 */
function packageJsonData(): array
{
    return json_decode(file_get_contents(base_path('package.json')), true);
}

beforeEach(function () {
    /** @var TestCase $this */
    $this->page = visit('/');
});

it('shows copyright symbol', function () {
    $this->page->assertSeeIn('@footer', '©');
})->flaky();

it('shows built with love in Ukraine message', function () {
    $this->page->assertSeeIn('@footer', 'Built with');
})->flaky();

it('shows author attribution', function () {
    $authorName = packageJsonData()['author']['name'];
    $authorUrl = packageJsonData()['author']['url'];

    $this->page->assertSeeIn('@footer-author-link', $authorName);
    $this->page->assertAttribute('@footer-author-link', 'href', $authorUrl);
})->flaky();

it('shows link to current version', function () {
    $repoUrl = packageJsonData()['repository']['url'];

    $commitHash = trim((string) shell_exec('git rev-parse --short HEAD'));

    expect($commitHash)->not->toBe('');

    $versionUrl = sprintf('%s/commit/%s', $repoUrl, $commitHash);

    $this->page->assertSeeIn('@footer-app-version-link', $commitHash);
    $this->page->assertAttribute('@footer-app-version-link', 'href', $versionUrl);
})->flaky();

it('shows link to GitHub repository', function () {
    $repoUrl = packageJsonData()['repository']['url'];

    $this->page->assertSeeIn('@footer-repo-link', 'GitHub');
    $this->page->assertAttribute('@footer-repo-link', 'href', $repoUrl);
})->flaky();
