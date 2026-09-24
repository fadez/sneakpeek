<?php

declare(strict_types=1);

use App\Events\BroadcastableEvent;
use App\Events\Event;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Routing\Attributes\Controllers\WithoutMiddleware;
use Illuminate\Support\Collection;
use Pest\Expectation;
use Symfony\Component\Finder\SplFileInfo;

/*
|--------------------------------------------------------------------------
| Architecture Testing
|--------------------------------------------------------------------------
|
| These opinionated architecture tests are here to enforce best practices
| throughout a modern Laravel application, for example: strict typing,
| the single-responsibility principle for Actions/Controllers, etc.
|
*/

arch()->preset()->php();
arch()->preset()->laravel();
arch()->preset()->security();

arch('app')
    ->expect('App')
    ->toBeCasedCorrectly()
    ->toUseStrictTypes()
    ->toUseStrictEquality()
    ->toHavePropertiesDocumented()
    ->toHaveMethodsDocumented();

arch('avoid extension')
    ->expect('App')
    ->classes()
    ->toBeFinal()
    ->ignoring([
        Event::class,
        BroadcastableEvent::class,
    ]);

arch('avoid inheritance')
    ->expect('App')
    ->classes()
    ->toExtendNothing()
    ->ignoring([
        'App\Console\Commands',
        'App\Events',
        'App\Exceptions',
        'App\Extensions',
        'App\Http\Requests',
        'App\Http\Resources',
        'App\Jobs',
        'App\Mail',
        'App\Models',
        'App\Notifications',
        'App\Providers',
        'App\View',
    ]);

arch('avoid mutation')
    ->expect('App')
    ->classes()
    ->toBeReadonly()
    ->ignoring([
        'App\Console\Commands',
        'App\Events',
        'App\Exceptions',
        'App\Extensions',
        'App\Http\Requests',
        'App\Http\Resources',
        'App\Jobs',
        'App\Mail',
        'App\Models',
        'App\Notifications',
        'App\Providers',
        'App\View',
    ]);

arch('actions')
    ->expect('App\Actions')
    ->toBeClasses()
    ->toHaveMethod('handle')
    ->not->toHavePublicMethodsBesides(['__construct', 'handle'])
    ->not->toHaveSuffix('Action');

arch('controllers')
    ->expect('App\Http\Controllers')
    ->toBeClasses()
    ->toHaveMethod('__invoke')
    ->not->toHavePublicMethodsBesides(['__construct', '__invoke'])
    ->not->toHaveAttribute(Middleware::class)
    ->not->toHaveAttribute(WithoutMiddleware::class);

arch('commands')
    ->expect('App\Console\Commands')
    ->toBeClasses()
    ->not->toBeAbstract()
    ->toHaveAttribute(Signature::class)
    ->toHaveAttribute(Description::class);

arch('events')
    ->expect('App\Events')
    ->toBeClasses()
    ->toExtend(Event::class)
    ->ignoring([Event::class, BroadcastableEvent::class]);

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

arch('interfaces')
    ->expect('App\Contracts')
    ->toBeInterfaces();

arch('jobs')
    ->expect('App\Jobs')
    ->toBeClasses()
    ->toHaveSuffix('Job')
    ->toUseTrait(Queueable::class);

arch('DTOs')
    ->expect('App\DTOs')
    ->toBeClasses()
    ->not->toHaveSuffix('DTO');

arch('value objects')
    ->expect('App\ValueObjects')
    ->toBeClasses()
    ->not->toHaveSuffix('ValueObject');

arch('tests use strict types')
    ->expect(fn (): Collection => allTestFiles())
    ->each(function (Expectation $expectation) {
        /** @var SplFileInfo $file */
        $file = $expectation->value;

        $lines = file($file->getRealPath());

        expect($lines[2] ?? '')->toContain('declare(strict_types=1);');
    });
