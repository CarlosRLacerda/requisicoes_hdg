<?php

namespace App\Http\Controllers;

use App\Services\RequestService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $requestsService;
    public function __construct()
    {
        $this->requestsService = new RequestService();
    }

    public function index(Request $request)
    {
        $data = [];

        $currentPage = $request->input('page1', 1);
        $current2Page = $request->input('page2', 1);
        
        $user = Auth::user();

        if($user->hasRole(['admin', 'almo'])) {
            $data['requestsStats'] = $this->requestsService->getRequestsStatsByYear(now()->year);
            $data['requestsToday'] = $this->requestsService->getRequestsByDays(days: 1, currentPage:  $currentPage);
            $data['requestsLast7Days'] =  $this->requestsService->getRequestsByDays(days: 7, currentPage:  $current2Page);
        }

        if($user->hasRole(['default'])) {
            $search = $request->input('search');
            $data['myRequests'] = $this->requestsService->getRequestsByUser($user, currentPage: $currentPage, search: $search);
        }

        return view('dashboard', $data);
    }
}
