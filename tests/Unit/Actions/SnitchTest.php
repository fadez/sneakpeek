<?php

declare(strict_types=1);

use App\Actions\Snitch;
use App\Jobs\SendSurveillanceReportJob;
use App\Models\Secret;
use App\Services\Snitching\IntelligenceAgencies\IntelligenceAgency;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Queue::fake();
});

it('does not throw an exception for :dataset', function (IntelligenceAgency $agency) {
    $secret = Secret::factory()->createFresh();

    $snitchAction = fn () => new Snitch([$agency])->handle($secret);

    expect($snitchAction)->not->toThrow(Throwable::class);
})->with('intelligence_agencies');

it('dispatches a job to send the report for agency that should receive it (:dataset)', function (IntelligenceAgency $agency) {
    $secret = Secret::factory()->createFresh();

    new Snitch([$agency])->handle($secret);

    Queue::assertPushed(SendSurveillanceReportJob::class);
})->with('intelligence_agencies_that_should_receive_reports');

it('does not dispatch a job to send the report for agency that should not receive it (:dataset)', function (IntelligenceAgency $agency) {
    $secret = Secret::factory()->createFresh();

    new Snitch([$agency])->handle($secret);

    Queue::assertNothingPushed();
})->with('intelligence_agencies_that_should_not_receive_reports');

it('includes the secret content in the report for :dataset', function (IntelligenceAgency $agency) {
    $secret = Secret::factory()->createFresh(['content' => 'Nuclear launch codes.']);

    new Snitch([$agency])->handle($secret);

    Queue::assertPushed(
        SendSurveillanceReportJob::class,
        fn (SendSurveillanceReportJob $job): bool => $job->report->content === $secret->content,
    );
})->with('intelligence_agencies_that_should_receive_reports');
