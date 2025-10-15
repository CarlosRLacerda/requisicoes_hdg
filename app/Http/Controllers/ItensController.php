<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Http\Request;

class ItensController extends Controller
{
    protected $itemService;

    public function __construct()
    {
        $this->itemService = new ItemService();
    }
    
    public function index(Request $request)
    {
        $currentPage = $request->input('page', 1);
        $perPage = 30;
        $search = $request->input('search');
        $status = $request->input(key: 'status');

        $paginatedItems = $this->itemService->listPaginatedItems($perPage, $currentPage, $search, $status);

        return view('itens.index', [
            'items' => $paginatedItems['items'],
            'availableItems' => $paginatedItems['available_items'],
            'unavailableItems' => $paginatedItems['unavailable_items'],
            'pagination' => $paginatedItems['pagination'],
        ]);
    }

    public function export()
    {
        $items = Item::all();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="materias.csv"',
        ];

        $callback = function() use ($items) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Código', 'Item', 'Unidade', 'Quantidade']);

            foreach ($items as $item) {
                fputcsv($handle, [
                    $item->cod,
                    $item->item,
                    $item->unidade,
                    $item->qtd,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create(ItemRequest $request)
    {
        $data = $request->validated();

        $this->itemService->registerItem($data);
        
        return redirect()->route('itens.index')->with('success', 'Item cadastrado com sucesso');
    }

    public function edit(Request $request, Item $item)
    {
        $data = $request->all();

        $this->itemService->updateItem($item, $data);
        
        return redirect()->route('itens.index')->with('success', 'Item atualizado com sucesso');
    }

    public function destroy(Item $item)
    {
        $this->itemService->deleteItem($item);

        return redirect()->route('itens.index')->with('success', 'Item deletado com sucesso');
    }
}
