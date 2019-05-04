<?php

declare(strict_types=1);

namespace App\Controller;

use App\Cache\WorkhoursApiCache;
use App\Filter\WorkhoursReportFilter;
use App\Report\WorkhoursAggregatedReport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    public function index()
    {
        return $this->render('index.html.twig', []);
    }

    public function reportData(
        Request $request,
        WorkhoursAggregatedReport $workhoursReportApi,
        WorkhoursReportFilter $filter,
        WorkhoursApiCache $cache
    ) {
        $start = \DateTime::createFromFormat('U', (string)$request->get('startDate'));
        $end = \DateTime::createFromFormat('U', (string)$request->get('endDate'));
        $firstName = (string)$request->get('firstName');
        $lastName = (string)$request->get('lastName');

        $cacheKey = sprintf('report_%d_%d', $start->getTimestamp(), $end->getTimestamp());
        $result = $cache->get($cacheKey, function () use ($workhoursReportApi, $start, $end) {
            return $workhoursReportApi->getReportForPeriod($start, $end);
        });

        $result->setEmployeeReport($filter->filterByFirstAndLastName($firstName, $lastName,
            $result->getEmployeeReport()));

        return $this->json([
            'data' => $result,
        ]);
    }
}