<?php

declare(strict_types=1);

namespace App\Model;

class CompanyWorkhoursReport implements \JsonSerializable
{
    /**
     * @var string
     */
    private $companyName;

    /**
     * @var float
     */
    private $hours;

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

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

    public function jsonSerialize()
    {
        return [
            'companyName' => $this->companyName,
            'hours' => $this->hours,
        ];
    }
}
