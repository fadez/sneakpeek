<?php

declare(strict_types=1);

it('shows copyright symbol', function () {
    $page = visit('/');

    $page->assertSeeIn('@footer', '©');
})->flaky();

it('shows built with love in Ukraine message', function () {
    $page = visit('/');

    $page->assertSeeIn('@footer', 'Built with');
})->flaky();

it('shows author attribution', function () {
    $page = visit('/');

    $authorName = packageJsonData()['author']['name'];
    $authorUrl = packageJsonData()['author']['url'];

    $page->assertSeeIn('@footer-author-link', $authorName);
    $page->assertAttribute('@footer-author-link', 'href', $authorUrl);
})->flaky();

it('shows link to current version', function () {
    $page = visit('/');

    $repoUrl = packageJsonData()['repository']['url'];

    $commitHash = trim((string) shell_exec('git rev-parse --short HEAD'));

    expect($commitHash)->not->toBeEmpty();

    $versionUrl = sprintf('%s/commit/%s', $repoUrl, $commitHash);

    $page->assertSeeIn('@footer-app-version-link', $commitHash);
    $page->assertAttribute('@footer-app-version-link', 'href', $versionUrl);
})->flaky();

it('shows link to GitHub repository', function () {
    $page = visit('/');

    $repoUrl = packageJsonData()['repository']['url'];

    $page->assertSeeIn('@footer-repo-link', 'GitHub');
    $page->assertAttribute('@footer-repo-link', 'href', $repoUrl);
})->flaky();
