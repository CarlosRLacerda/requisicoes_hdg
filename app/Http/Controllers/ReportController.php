<?php

namespace App\Http\Controllers;

use App\Services\ItemService;
use App\Services\RequestService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $requestsService;
    protected $itensService;
    public function __construct()
    {
        $this->requestsService = new RequestService();
        $this->itensService = new ItemService();
    }
    
    public function export(Request $request)
    {
        $chartImage = $request->input('chart_image');

        $data = [
            'chartImage' => $chartImage,
            ...$this->requestsService->countRequests(),
            ...$this->itensService->countItens()
        ];
        
        $pdf = Pdf::loadView('report.export', $data);

        return $pdf->download();
    }
}
