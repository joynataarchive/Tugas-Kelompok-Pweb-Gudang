<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportFilterRequest;
use App\Models\StockMutation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman laporan dengan form filter bulan/tahun dan preview tabel.
     */
    public function index(ReportFilterRequest $request)
    {
        $month = $request->selectedMonth();
        $year  = $request->selectedYear();

        $mutations = StockMutation::with(['product', 'user'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->latest()
            ->get();

        $summary = [
            'total_in'           => $mutations->where('type', 'in')->sum('quantity'),
            'total_out'          => $mutations->where('type', 'out')->sum('quantity'),
            'total_transactions' => $mutations->count(),
        ];

        // Daftar tahun untuk dropdown (5 tahun ke belakang s.d. tahun ini)
        $years = range(now()->year, now()->year - 4);

        return view('reports.index', compact('mutations', 'summary', 'month', 'year', 'years'));
    }

    /**
     * Ekspor laporan sebagai PDF dan stream ke browser.
     * DIBATASI hanya untuk role Super Admin (lihat routes/web.php).
     */
    public function exportPdf(ReportFilterRequest $request)
    {
        $month = $request->selectedMonth();
        $year  = $request->selectedYear();

        $mutations = StockMutation::with(['product', 'user'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->latest()
            ->get();

        $summary = [
            'total_in'           => $mutations->where('type', 'in')->sum('quantity'),
            'total_out'          => $mutations->where('type', 'out')->sum('quantity'),
            'total_transactions' => $mutations->count(),
        ];

        $periodLabel = Carbon::createFromDate($year, $month, 1)
            ->translatedFormat('F Y');

        $pdf = Pdf::loadView('pdf.report', compact('mutations', 'summary', 'periodLabel', 'month', 'year'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream("Laporan_Mutasi_{$year}_{$month}.pdf");
    }
}
