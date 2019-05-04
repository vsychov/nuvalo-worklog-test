<?php

declare(strict_types=1);

namespace App\Model;

class EmployeeWorkhoursReport implements \JsonSerializable
{
    /**
     * @var string emploee name
     */
    private $employeeFirstName;

    /**
     * @var string
     */
    private $employeeLastName;

    /**
     * @var \DateTime, middle of the month
     */
    private $month;

    /**
     * @var float
     */
    private $hours;

    public function getEmployeeFirstName(): string
    {
        return $this->employeeFirstName;
    }

    public function setEmployeeFirstName(string $employeeFirstName): self
    {
        $this->employeeFirstName = $employeeFirstName;

        return $this;
    }

    public function getMonth(): \DateTime
    {
        return $this->month;
    }

    public function setMonth(\DateTime $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getHours(): float
    {
        return $this->hours;
    }

    public function setHours(float $hours): self
    {
        $this->hours = $hours;

        return $this;
    }

    public function getEmployeeLastName(): string
    {
        return $this->employeeLastName;
    }

    public function setEmployeeLastName(string $employeeLastName): self
    {
        $this->employeeLastName = $employeeLastName;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'employee' => sprintf('%s %s', $this->employeeFirstName, $this->employeeLastName),
            'month' => $this->month->getTimestamp(),
            'hours' => $this->hours,
        ];
    }
}