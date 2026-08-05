<?php

declare(strict_types=1);

use App\Events\BroadcastableEvent;
use App\Events\Event;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Routing\Attributes\Controllers\WithoutMiddleware;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Pest\Expectation;
use Symfony\Component\Finder\SplFileInfo;

// Opinionated architectural tests to enforce best practices in a modern Laravel app
// such as strict typing, single-responsibility for Actions/Controllers, and more

arch()->preset()->php();
arch()->preset()->laravel();
arch()->preset()->security();

arch('app')
    ->expect('App')
    ->toBeCasedCorrectly()
    ->toUseStrictTypes()
    ->toUseStrictEquality();

arch('actions')
    ->expect('App\Actions')
    ->toBeClasses()
    ->toBeFinal()
    ->toBeReadonly()
    ->toExtendNothing()
    ->toHaveMethod('handle')
    ->toHaveMethodsDocumented()
    ->not->toHavePublicMethodsBesides(['__construct', 'handle'])
    ->not->toHaveSuffix('Action');

arch('controllers')
    ->expect('App\Http\Controllers')
    ->toExtendNothing()
    ->toHaveMethod('__invoke')
    ->toHaveMethodsDocumented()
    ->not->toHavePublicMethodsBesides(['__construct', '__invoke'])
    ->not->toHaveAttribute(Middleware::class)
    ->not->toHaveAttribute(WithoutMiddleware::class);

arch('commands')
    ->expect('App\Console\Commands')
    ->toBeClasses()
    ->toBeFinal()
    ->not->toBeAbstract()
    ->toHaveAttribute(Signature::class)
    ->toHaveAttribute(Description::class);

arch('DTOs')
    ->expect('App\DTOs')
    ->toBeClasses()
    ->toExtendNothing()
    ->toBeFinal()
    ->toBeReadonly()
    ->not->toHaveSuffix('DTO');

arch('event base class')
    ->expect(Event::class)
    ->toBeClass()
    ->toBeAbstract()
    ->toImplement(ShouldDispatchAfterCommit::class);

arch('broadcastable event base class')
    ->expect(BroadcastableEvent::class)
    ->toBeClass()
    ->toExtend(Event::class)
    ->toBeAbstract()
    ->toImplement(ShouldBroadcast::class);

arch('events')
    ->expect('App\Events')
    ->toBeClasses()
    ->toExtend(Event::class)
    ->toBeFinal()
    ->ignoring([Event::class, BroadcastableEvent::class]);

arch('services')
    ->expect('App\Services')
    ->toBeClasses()
    ->toBeFinal()
    ->not->toBeAbstract()
    ->not->toUse('App\Http')
    ->toHaveMethodsDocumented()
    ->toHavePropertiesDocumented();

arch('tests use strict types')
    ->expect(fn (): Collection => collect(File::allFiles(base_path('tests')))
        ->filter(fn (SplFileInfo $file): bool => $file->getExtension() === 'php'))
    ->each(function (Expectation $expectation) {
        /** @var SplFileInfo $file */
        $file = $expectation->value;

        expect(File::get($file->getRealPath()))
            ->toContain('declare(strict_types=1);');
    });
