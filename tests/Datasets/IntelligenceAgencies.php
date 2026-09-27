<?php

declare(strict_types=1);

use App\Contracts\AcceptsSurveillanceReports;
use App\Contracts\ShouldSendSurveillanceReports;
use App\Services\Snitching\IntelligenceAgencies\Cia;
use App\Services\Snitching\IntelligenceAgencies\Fbi;
use App\Services\Snitching\IntelligenceAgencies\IntelligenceAgency;
use App\Services\Snitching\IntelligenceAgencies\Mi5;
use App\Services\Snitching\IntelligenceAgencies\Mi6;
use App\Services\Snitching\IntelligenceAgencies\Mossad;
use App\Services\Snitching\IntelligenceAgencies\Nsa;
use Illuminate\Support\Collection;

$agencyClasses = [
    Cia::class,
    Fbi::class,
    Mi5::class,
    Mi6::class,
    Mossad::class,
    Nsa::class,
];

/** @var Collection<string, IntelligenceAgency> $agencies */
$agencies = collect($agencyClasses)
    ->mapWithKeys(function (string $class): array {
        $agency = resolve($class);

        return [$agency->abbreviation() => $agency];
    });

dataset('intelligence_agencies', $agencies->toArray());

dataset(
    'intelligence_agencies_that_accept_reports',
    $agencies
        ->filter(fn (IntelligenceAgency $agency): bool => $agency instanceof AcceptsSurveillanceReports)
        ->toArray(),
);

dataset(
    'intelligence_agencies_that_should_receive_reports',
    $agencies
        ->filter(fn (IntelligenceAgency $agency): bool => $agency instanceof ShouldSendSurveillanceReports)
        ->toArray(),
);

dataset(
    'intelligence_agencies_that_should_not_receive_reports',
    $agencies
        ->filter(fn (IntelligenceAgency $agency): bool => ! $agency instanceof AcceptsSurveillanceReports || ! $agency instanceof ShouldSendSurveillanceReports)
        ->toArray(),
);

dataset(
    'intelligence_agencies_that_do_not_accept_reports',
    $agencies
        ->reject(fn (IntelligenceAgency $agency): bool => $agency instanceof AcceptsSurveillanceReports)
        ->toArray(),
);
