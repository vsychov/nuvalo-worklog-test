<?php

declare(strict_types=1);

namespace App\Report;

use App\Api\WorkhoursReportApi;
use App\Model\CompanyWorkhoursReport;
use App\Model\EmployeeWorkhoursReport;
use App\Model\WorkhourLog;
use App\Model\WorkhoursReport;
use App\Parser\WorkhoursReportParser;

class WorkhoursAggregatedReport
{
    /**
     * @var WorkhoursReportParser
     */
    private $workhoursReportParser;

    /**
     * @var WorkhoursReportApi
     */
    private $workhoursReportApi;

    public function __construct(WorkhoursReportApi $workhoursReportApi, WorkhoursReportParser $workhoursReportParser)
    {
        $this->workhoursReportApi = $workhoursReportApi;
        $this->workhoursReportParser = $workhoursReportParser;
    }

    public function getReportForPeriod(\DateTimeInterface $from, \DateTimeInterface $to): WorkhoursReport
    {
        $stream = $this->workhoursReportApi->getReportStream($from, $to);
        $aggregatedByEmployeeData = [];
        $aggregatedByCompanyData = [];

        $this->workhoursReportParser->parseApiResponse($stream,
            function (WorkhourLog $log) use (&$aggregatedByEmployeeData, &$aggregatedByCompanyData) {
                $this->aggregateByMonthForEmployeeParserCallback($log, $aggregatedByEmployeeData);
                $this->aggregateByCompanyTotalHoursParserCallback($log, $aggregatedByCompanyData);
            }
        );

        $report = new WorkhoursReport();

        $this->hydrateReportByEmployeeInfo($report, $aggregatedByEmployeeData);
        $this->hydrateReportByCompanyInfo($report, $aggregatedByCompanyData);

        return $report;
    }

    private function hydrateReportByEmployeeInfo(WorkhoursReport $report, &$employeeData)
    {
        $employees = [];
        foreach ($employeeData as $key => $item) {
            [$year, $month] = explode('_', $key);
            $employees[] = (new EmployeeWorkhoursReport())
                ->setEmployeeFirstName($item['employee']->getFirstName())
                ->setEmployeeLastName($item['employee']->getLastName())
                ->setMonth(new \DateTime(sprintf('%s-%s-15', $year, $month)))
                ->setHours($item['hours'])
            ;
        }

        $report->setEmployeeReport($employees);
    }

    private function hydrateReportByCompanyInfo(WorkhoursReport $report, &$companyData)
    {
        $companies = [];
        foreach ($companyData as $id => $item) {
            $companies[] = (new CompanyWorkhoursReport())
                ->setCompanyName($item['company']->getName())
                ->setHours($item['hours'])
            ;
        }

        $report->setCompanyReport($companies);
    }

    private function aggregateByCompanyTotalHoursParserCallback(WorkhourLog $item, &$aggregatedData)
    {
        $hours = $this->simpleHoursSum($item->getStart(), $item->getEnd());
        $key = $item->getEmployee()->getCompanyId();

        if (!isset($aggregatedData[$key])) {
            $aggregatedData[$key]['hours'] = 0;
            $aggregatedData[$key]['company'] = $item->getEmployee()->getCompany();
        }

        $aggregatedData[$key]['hours'] += $hours;
    }

    private function aggregateByMonthForEmployeeParserCallback(WorkhourLog $item, &$aggregatedData)
    {
        $monthData = $this->splitHoursByMonths($item->getStart(), $item->getEnd());
        $employeeToCompanyId = sprintf('%s_%s', $item->getEmployeeId(), $item->getEmployee()->getCompanyId());

        foreach ($monthData as $month => $hours) {
            $key = sprintf('%s_%s', $month, $employeeToCompanyId);
            if (!isset($aggregatedData[$key])) {
                $aggregatedData[$key]['hours'] = 0;
                $aggregatedData[$key]['employee'] = $item->getEmployee();
            }

            $aggregatedData[$key]['hours'] += $hours;
        }
    }

    private function simpleHoursSum(\DateTimeInterface $start, \DateTimeInterface $end)
    {
        return ($end->getTimestamp() - $start->getTimestamp()) / 3600;
    }

    private function splitHoursByMonths(\DateTimeInterface $start, \DateTimeInterface $end)
    {
        $dateFormat = 'Y_m';

        if ($start->format($dateFormat) === $end->format($dateFormat)) {
            //simple calculate hours, almost for all cases
            return [$start->format($dateFormat) => $this->simpleHoursSum($start, $end)];
        }

        $result = [];
        //not optimal solution, but I don't want spent time in test
        //can be optimized by increse interval, but will have calculation error
        $period = new \DatePeriod($start, new \DateInterval('PT1S'), $end);
        foreach ($period as $date) {
            $key = $date->format($dateFormat);
            if (!isset($result[$key])) {
                $result[$key] = 0;
            }

            ++$result[$key];
        }

        //convert to hours
        foreach ($result as $key => $value) {
            $result[$key] /= 3600;
        }

        return $result;
    }
}