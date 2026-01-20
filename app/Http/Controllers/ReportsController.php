<?php

namespace App\Http\Controllers;

use App\Services\ReportingService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportsController extends Controller
{
    protected $reportingService;

    public function __construct(ReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    /**
     * Display the Reporting Hub.
     */
    public function index()
    {
        $quickStats = $this->reportingService->getGlobalQuickStats();
        return view('reports.index', compact('quickStats'));
    }

    /**
     * Display a specific report.
     */
    public function show(string $slug, Request $request)
    {
        $data = [];
        $title = "Report";

        switch ($slug) {
            case 'inventory':
                $data = $this->reportingService->getStockSummary();
                $title = "Stock Summary Report";
                break;
            case 'finance':
                $startDate = $request->input('start_date');
                $endDate = $request->input('end_date');
                $data = $this->reportingService->getFinanceSummary($startDate, $endDate);
                $title = "Financial Performance Report";
                break;
            case 'logistics':
                $data = $this->reportingService->getLogisticsPerformance();
                $title = "Logistics Performance Report";
                break;
            case 'suppliers':
                $data = $this->reportingService->getSupplierAnalytics();
                $title = "Supplier Analytics Report";
                break;
            case 'analytics':
                $data = $this->reportingService->getFinancialAnalytics();
                $title = "Advanced Financial Analytics";
                break;
            default:
                abort(404, "Report type not found.");
        }

        if ($slug === 'analytics') {
            return view('reports.finance_analytics', compact('slug', 'data', 'title'));
        }

        return view('reports.show', compact('slug', 'data', 'title'));
    }

    /**
     * Export report to CSV/Excel (CSV for now).
     */
    public function export(string $slug, Request $request)
    {
        $data = [];
        $filename = "report_{$slug}_" . date('Ymd_His') . ".csv";

        switch ($slug) {
            case 'inventory':
                $data = $this->reportingService->getStockSummary()->toArray();
                break;
            case 'suppliers':
                $data = $this->reportingService->getSupplierAnalytics()->toArray();
                break;
            default:
                return redirect()->back()->with('error', 'Export not supported for this report type.');
        }

        $response = new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');

            if (!empty($data)) {
                // Headers
                fputcsv($handle, array_keys($data[0]));

                // Rows
                foreach ($data as $row) {
                    fputcsv($handle, $row);
                }
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
