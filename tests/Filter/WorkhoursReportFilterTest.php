<?php

declare(strict_types=1);

namespace App\Tests\Filter;

use App\Filter\WorkhoursReportFilter;
use App\Model\EmployeeWorkhoursReport;
use PHPUnit\Framework\TestCase;

class WorkhoursReportFilterTest extends TestCase
{
    /**
     * @var WorkhoursReportFilter
     */
    private $workhoursReportFilter;

    public function setUp()
    {
        $this->workhoursReportFilter = new WorkhoursReportFilter();
    }

    /**
     * @dataProvider filterByFirstAndLastNameProvider
     */
    public function testFilterByFirstAndLastName($firstName, $lastName, $dataSet, $expectedResult)
    {
        $filterResult = $this->workhoursReportFilter->filterByFirstAndLastName($firstName, $lastName, $dataSet);
        $this->assertEquals($filterResult, $expectedResult);
    }

    public function filterByFirstAndLastNameProvider()
    {
        return [
            [
                'Test',
                'lastName',
                $this->createTestSet([['Test', 'Test'], ['Test1', 'Test2'], ['Te3st1', 'Test'], ['44Test', '123Test']]),
                []
            ],
            [
                'Test',
                null,
                $this->createTestSet([['Test', 'Test'], ['Test1', 'Test2'], ['Te3st1', 'Test'], ['44Test', '123Test']]),
                $this->createTestSet([['Test', 'Test'], ['Test1', 'Test2'], ['44Test', '123Test']]),
            ],
            [
                'Test',
                '123Test',
                $this->createTestSet([['Test', 'Test'], ['Test1', 'Test2'], ['Te3st1', 'Test'], ['44Test', '123Test']]),
                $this->createTestSet([['44Test', '123Test']]),
            ],
            [
                null,
                'Te3st1',
                $this->createTestSet([['Test', 'Test'], ['Test1', 'Test2'], ['Te3st1', 'Test'], ['44Test', '123Test']]),
                $this->createTestSet([]),
            ],
        ];
    }

    public function testUnknownData()
    {
        $this->expectException(\RuntimeException::class);
        $this->workhoursReportFilter->filterByFirstAndLastName(null, null, ['some' => 'info']);
    }

    private function createTestSet($data)
    {
        $result = [];
        foreach ($data as $item) {
            $result[] = (new EmployeeWorkhoursReport())->setEmployeeFirstName($item[0])->setEmployeeLastName($item[1]);
        }

        return $result;
    }
}