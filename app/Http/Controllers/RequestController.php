<?php

namespace App\Http\Controllers;

use App\Enums\NeedEnum;
use App\Enums\SetorEnum;
use App\Http\Requests\RequestsFilterRequest;
use App\Http\Requests\SolicitarItemRequest;
use App\Models\Item;
use App\Services\RequestService;
use Illuminate\Http\Request;
use App\Models\Request as ItemRequest;
use App\Services\ItemService;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    protected $requestService;
    protected $itemService;

    public function __construct()
    {
        $this->requestService = new RequestService();
        $this->itemService = new ItemService();
    }

    public function index(RequestsFilterRequest $request)
    {
        $currentPage = $request->input('page', 1);
        $perPage = 20;
        $search = $request->input('search');
        $status = $request->input(key: 'status');
        
        $paginatedRequests = $this->requestService->listPaginatedRequests($perPage, $currentPage, $search, $status);

        return view('requests.index', $paginatedRequests);
    }

     public function export()
        {
            $requests = ItemRequest::all();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="requisicoes.csv"',
            ];

            $callback = function() use ($requests) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Código', 'Usuário', 'Item', 'Setor', 'Quantidade', 'Status']);

                foreach ($requests as $request) {
                    fputcsv($handle, [
                        $request->item->cod,
                        $request->user?->name,
                        $request->item->item ,
                        $request->setor,
                        $request->qtd,
                        $request->status,
                    ]);
                }
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

    public function indexSolicitar(Request $request) 
    {
        $currentPage = $request->input('page', 1);
        $perPage = 30;
        $search = $request->input('search');

        $paginatedItems = $this->itemService->listAvailablePaginatedItems($perPage, $currentPage, $search);

        return view('requests.solicitar', [
            'items' => $paginatedItems['items'],
            'pagination' => $paginatedItems['pagination'],
            'setores' => SetorEnum::cases()
        ]);
    }

    public function store(SolicitarItemRequest $request, Item $item)
    {
        $data = $request->validated();
     
        $this->requestService->registerRequest($data, $item, Auth::user());
     
        return redirect()->route('request.solicitar')->with('success', "Material solicitado com sucesso");
    }

    public function avaliar(RequestsFilterRequest $request, ItemRequest $req)
    {
        try {
            $data = $request->validated();

            $this->requestService->avaliarRequest($req, $data['status']);

            return redirect()->route('request.index')->with('success', "Requisição avaliada com sucesso");
        } catch(\Exception $e) {
            return redirect()->route('request.index')->with('error', "Erro interno ao avaliar requisição!");
        }
    }
}
