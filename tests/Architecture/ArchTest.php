<?php

use App\Events\BroadcastableEvent;
use App\Events\Event;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

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
    ->toHaveMethodsDocumented();

arch('controllers')
    ->expect('App\Http\Controllers')
    ->toExtendNothing()
    ->toHaveMethod('__invoke')
    ->toHaveMethodsDocumented();

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
    ->toBeFinal()
    ->toBeReadonly()
    ->toExtendNothing();

arch('event base classes')
    ->expect([Event::class, BroadcastableEvent::class])
    ->toBeClasses()
    ->toBeAbstract()
    ->toImplement(ShouldDispatchAfterCommit::class);

arch('events')
    ->expect('App\Events')
    ->toExtend(Event::class)
    ->toBeClasses()
    ->toBeFinal()
    ->ignoring([Event::class, BroadcastableEvent::class]);

arch('services')
    ->expect('App\Services')
    ->toBeClasses()
    ->toBeFinal()
    ->not->toBeAbstract()
    ->toHaveMethodsDocumented()
    ->toHavePropertiesDocumented();
