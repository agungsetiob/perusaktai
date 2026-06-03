<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Services\ComplaintReportService;

class ComplaintReportController extends Controller
{
    public function __invoke(
        Request $request,
        ComplaintReportService $service
    ) {
        $data = $service->generate(
            $request->start_date,
            $request->end_date
        );

        $pdf = Pdf::loadView(
            'pdf.complaints-report',
            $data
        );

        return $pdf->stream(
            'laporan-pengaduan.pdf'
        );
    }
}