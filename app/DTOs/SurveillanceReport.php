<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Services\Snitching\IntelligenceAgencies\IntelligenceAgency;
use App\ValueObjects\SurveillanceReportCaseNumber;
use Illuminate\Contracts\Support\Arrayable;
use Stringable;

/**
 * @implements Arrayable<string, string>
 */
final readonly class SurveillanceReport implements Arrayable, Stringable
{
    /**
     * The subject line for the surveillance report.
     */
    public string $subject;

    /**
     * Create a new DTO instance.
     */
    public function __construct(
        public IntelligenceAgency $agency,
        public SurveillanceReportCaseNumber $caseNumber,
        public string $content,
    ) {
        $this->subject = "Case number: {$this->caseNumber->value}";
    }

    /**
     * Get the array representation of the surveillance report.
     *
     * @return array{agency_id: string, case_number: string, content: string, subject: string}
     */
    public function toArray(): array
    {
        return [
            'agency_id' => $this->agency->id(),
            'case_number' => $this->caseNumber->value,
            'subject' => $this->subject,
            'content' => $this->content,
        ];
    }

    /**
     * Get the string representation of the surveillance report.
     */
    public function __toString(): string
    {
        return "{$this->subject}\n\n{$this->content}";
    }
}
