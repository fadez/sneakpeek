<?php

declare(strict_types=1);

use App\Jobs\SendSurveillanceReportJob;
use App\Models\SentSurveillanceReport;
use App\Services\Snitching\IntelligenceAgencies\IntelligenceAgency;

it('sends the report to agency that accepts reports (:dataset)', function (IntelligenceAgency $agency) {
    $report = $agency->makeSurveillanceReport('Top secret content.');

    new SendSurveillanceReportJob($report)->handle();

    expect($report->agency->hasSentSurveillanceReport($report))->toBeTrue();
})->with('intelligence_agencies_that_accept_reports');

it('does not send the report again to the agency if it has already been sent (:dataset)', function (IntelligenceAgency $agency) {
    $report = $agency->makeSurveillanceReport('Top secret content.');

    expect($agency->hasSentSurveillanceReport($report))->toBeFalse();

    new SendSurveillanceReportJob($report)->handle();

    expect($agency->hasSentSurveillanceReport($report))->toBeTrue();

    new SendSurveillanceReportJob($report)->handle();

    expect(
        SentSurveillanceReport::query()
            ->where('agency_id', $report->agency->id())
            ->where('case_number', $report->caseNumber->value)
            ->count(),
    )->toBe(1);
})->with('intelligence_agencies_that_accept_reports');

it('does not send the report to agency that does not accept it (:dataset)', function (IntelligenceAgency $agency) {
    $report = $agency->makeSurveillanceReport('Top secret content.');

    new SendSurveillanceReportJob($report)->handle();

    expect($agency->hasSentSurveillanceReport($report))->toBeFalse();
})->with('intelligence_agencies_that_do_not_accept_reports');

it('uses the report case number as unique identifier for :dataset', function (IntelligenceAgency $agency) {
    $report = $agency->makeSurveillanceReport('Top secret content.');

    expect(new SendSurveillanceReportJob($report)->uniqueId())->toBe($report->caseNumber->value);
})->with('intelligence_agencies_that_accept_reports');
