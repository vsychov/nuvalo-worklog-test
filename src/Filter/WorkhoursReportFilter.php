<?php

declare(strict_types=1);

namespace App\Filter;

use App\Model\EmployeeWorkhoursReport;

class WorkhoursReportFilter
{
    /**
     * @param EmployeeWorkhoursReport[] $reportElements
     * @return EmployeeWorkhoursReport[]
     */
    public function filterByFirstAndLastName(?string $firstName, ?string $lastName, array $reportElements): array
    {
        foreach ($reportElements as $key => $element) {
            if (!($element instanceof EmployeeWorkhoursReport)) {
                throw new \RuntimeException('Invalid input to filter');
            }

            if ((null !== $firstName && $firstName !== '') &&
                stripos($element->getEmployeeFirstName(), $firstName) === false
            ) {
                unset($reportElements[$key]);
                continue;
            }

            if ((null !== $lastName && $lastName !== '') &&
                stripos($element->getEmployeeLastName(), $lastName) === false
            ) {
                unset($reportElements[$key]);
                continue;
            }
        }

        return array_values($reportElements);
    }
}