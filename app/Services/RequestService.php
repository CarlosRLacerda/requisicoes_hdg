<?php

namespace App\Services;

use App\Enums\StatusRequestEnum;
use App\Models\Item;
use App\Models\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class RequestService
{
    public function listPaginatedRequests(int $perPage = 15, int $currentPage = 1, ?string $search = null, ?string $status = null)
    {
        $query = $status ? $this->getRequestsByStatus($status) : Request::query()
            ->select('requests.*')
            ->join('items', 'items.id', '=', 'requests.item_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->orderByDesc('requests.id');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('items.cod', 'like', "%$search%")
                ->orWhere('items.item', 'like', "%$search%")
                ->orWhere('users.name', 'like', "%$search%");
            });
        }

        $paginatedRequests = $query->paginate($perPage, ['*'], 'page', $currentPage);

        $approvedCount = Request::where('status', StatusRequestEnum::APROVADA->value)->count();
        $refusedCount = Request::where('status', StatusRequestEnum::REPROVADA->value)->count();
        $pendingCount = Request::where('status', StatusRequestEnum::PENDENTE->value)->count();

        return [
            'requests' => $paginatedRequests->getCollection(),
            'approvedRequestsCount' => $approvedCount,
            'refusedRequestsCount' => $refusedCount,
            'pendingRequestsCount' => $pendingCount,
            'pagination' => [
                'total' => $paginatedRequests->total(),
                'perPage' => $perPage,
                'currentPage' => $currentPage,
                'lastPage' => $paginatedRequests->lastPage(),
            ],
        ];
    }

    public function countRequests()
    {

        $approvedCount = Request::where('status', StatusRequestEnum::APROVADA->value)->count();
        $refusedCount = Request::where('status', StatusRequestEnum::REPROVADA->value)->count();
        $pendingCount = Request::where('status', StatusRequestEnum::PENDENTE->value)->count();
        
        return [
            'approvedRequestsCount' => $approvedCount,
            'refusedRequestsCount' => $refusedCount,
            'pendingRequestsCount' => $pendingCount,
        ];
    }

    private function getRequestsByStatus($status)
    {
        $query = Request::query()->where('status', $status);
        return $query;
    }

    public function getRequestsByDays(int $days = 7, int $perPage = 5, int $currentPage = 1)
    {
        $date = now()->subDays($days);
        
        $requests = Request::where('created_at', '>=', $date)
            ->where('status', StatusRequestEnum::PENDENTE->value)
            ->orderByDesc('id')
            ->paginate($perPage, ['*'], 'page', $currentPage);
        
        return [
            'requests' => $requests->getCollection(),
            'pagination' => [
                'total' => $requests->total(),
                'perPage' => $perPage,
                'currentPage' => $currentPage,
                'lastPage' => $requests->lastPage(),
            ],
        ];
    }

    public function getRequestsByUser(User $user, int $perPage = 30, int $currentPage = 1, ?string $search = null)
    {
        $query = Request::query()
        ->select('requests.*')
        ->join('items', 'items.id', '=', 'requests.item_id')
        ->where('requests.user_id', $user->id);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('items.item', 'like', "%$search%")
                ->orWhere('items.cod', 'like', "%$search%");
            });
        }

        $requests = $query->orderByDesc('requests.id')
            ->paginate($perPage, ['*'], 'page', $currentPage);

        return [
            'requests' => $requests->getCollection(),
            'pagination' => [
                'total' => $requests->total(),
                'perPage' => $perPage,
                'currentPage' => $currentPage,
                'lastPage' => $requests->lastPage(),
            ],
        ];
    }

    public function getRequestsStatsByYear(int $year)
    {
        $stats = [];

        for ($month = 1; $month <= 12; $month++) {
            $acceptedCount = Request::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('status', StatusRequestEnum::APROVADA->value)
                ->count();

            $refusedCount = Request::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('status', StatusRequestEnum::REPROVADA->value)
                ->count();

            $stats[$month] = [
                'accepted' => $acceptedCount,
                'refused' => $refusedCount,
            ];
        }

        return $stats;
    }

    public function canRegisterRequest(array $data, Item $item)
    {
        if($data['qtd'] > 0 && $data['qtd'] > $item->qtd) {
            throw new \Exception("A quantidade solicitada excede o estoque disponível!");
        }
    }

    public function registerRequest(array $data, Item $item, User $user)
    {
        try {
            $this->canRegisterRequest($data, $item);
            
            Request::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'qtd' => $data['qtd'],
                'status' => StatusRequestEnum::PENDENTE->value,
                'setor' => $data['setor']
            ]);
            
            $item->qtd -= $data['qtd'];

            $item->save();
            
        } catch(Exception $e) {
            return redirect()->route('request.solicitar')->with('error', $e->getMessage());
        }
    }

    public function avaliarRequest(Request $req, string $status)
    {
        $req->status = $status;

        if($status === StatusRequestEnum::REPROVADA->value) {
            $req->item->qtd += $req->qtd;
            $req->item->save();
        }

        $req->save();
    }

    //clean code: eu não existo
}