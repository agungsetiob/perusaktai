<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RoomComplaintReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RoomComplaintReportController extends Controller
{
    public function __invoke(
        Request $request,
        RoomComplaintReportService $service
    ) {
        $data = $service->generate(
            $request->start_date,
            $request->end_date
        );

        $pdf = Pdf::loadView(
            'pdf.room-complaints-report',
            $data
        );

        return $pdf->stream(
            'laporan-pengaduan-per-ruangan.pdf'
        );
    }
}