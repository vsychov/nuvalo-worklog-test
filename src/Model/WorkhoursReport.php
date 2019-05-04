<?php

declare(strict_types=1);

namespace App\Model;

class WorkhoursReport implements \JsonSerializable
{
    /**
     * @var CompanyWorkhoursReport[]
     */
    private $companyReport;

    /**
     * @var EmployeeWorkhoursReport[]
     */
    private $employeeReport;

    public function getCompanyReport(): array
    {
        return $this->companyReport;
    }

    public function setCompanyReport(array $companyReport): self
    {
        $this->companyReport = $companyReport;

        return $this;
    }

    public function getEmployeeReport(): array
    {
        return $this->employeeReport;
    }

    public function setEmployeeReport(array $employeeReport): self
    {
        $this->employeeReport = $employeeReport;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'companyReport' => $this->companyReport,
            'employeeReport' => $this->employeeReport,
        ];
    }
}