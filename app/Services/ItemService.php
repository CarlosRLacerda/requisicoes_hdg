<?php

namespace App\Services;

use App\Models\Item;
use Exception;
use Illuminate\Support\Arr;

class ItemService
{
    public function listItems()
    {
        return ['disponiveis' => $this->getAvailableItems(), 'indisponiveis' => $this->getUnavailableItems()];
    }

    public function listPaginatedItems(int $perPage = 15, int $currentPage = 1, ?string $search = null, ?string $status = null)
    {
        $availableQuery = Item::where('qtd', '>', 0)->orderByDesc('id');
        $unavailableQuery = Item::where('qtd', '=', 0)->orderByDesc('id');

        if ($search) {
            $availableQuery->where(function ($query) use ($search) {
                $query->where('cod', 'like', "%$search%")
                    ->orWhere('item', 'like', "%$search%");
            });

            $unavailableQuery->where(function ($query) use ($search) {
                $query->where('cod', 'like', "%$search%")
                    ->orWhere('item', 'like', "%$search%");
            });
        }

        $availableItems = $availableQuery->paginate($perPage, ['*'], 'page', $currentPage);
        $unavailableItems = $unavailableQuery->paginate($perPage, ['*'], 'page', $currentPage);
        
        $data = [
            'available_items' => $availableItems,
            'unavailable_items' => $unavailableItems,
        ];

        if($status === 'disponivel') {
            $data['items'] = collect($availableItems->items())
                ->sortByDesc('id')
                ->values();
            
            $data['pagination'] = [
                'total' => $availableItems->total(),
                'perPage' => $perPage,
                'currentPage' => $currentPage,
                'lastPage' => $availableItems->lastPage(),
            ];
        } elseif($status === 'indisponivel') {
            $data['items'] = collect($unavailableItems->items())
                ->sortByDesc('id')
                ->values();
            
            $data['pagination'] = [
                    'total' => $unavailableItems->total(),
                    'perPage' => $perPage,
                    'currentPage' => $currentPage,
                    'lastPage' => $unavailableItems->lastPage(),
                ];
        } else {
            $data['items'] = collect($availableItems->items())
                ->merge($unavailableItems->items())
                ->sortByDesc('id')
                ->values();

            $data['pagination'] = [
                'total' => max($availableItems->total(), $unavailableItems->total()),
                'perPage' => $perPage,
                'currentPage' => $currentPage,
                'lastPage' => max($availableItems->lastPage(), $unavailableItems->lastPage()),
            ];
        }

        return $data;
    }

    public function listAvailablePaginatedItems(int $perPage = 15, int $currentPage = 1, ?string $search = null)
    {
        $itemsQuery = Item::query()->orderByDesc('qtd');

        if ($search) {
            $itemsQuery->where(function ($query) use ($search) {
                $query->where('cod', 'like', "%$search%")
                    ->orWhere('item', 'like', "%$search%");
            });

        }

        $itemsQuery = $itemsQuery->paginate($perPage, ['*'], 'page', $currentPage);

        $mergedItems = collect($itemsQuery->items())
            ->sortByDesc('qtd')
            ->values();

        return [
            'items' => $mergedItems,
            'pagination' => [
                'total' => $itemsQuery->total(),
                'perPage' => $perPage,
                'currentPage' => $currentPage,
                'lastPage' => $itemsQuery->lastPage(),
            ],
        ];
    }

    public function registerItem(array $data) 
    {
        try {
            $this->canRegisterItem($data['cod'], $data['qtd']);
            
            Item::create($data);

        } catch(Exception $e) {
            return redirect()->route('itens.index')->with('error', $e->getMessage());
        }
    }

    public function updateItem(Item $item, array $data) 
    {
        try {
            $cod = $this->field($data, 'cod');
            $qtd = $this->field($data, 'qtd');
            
            $this->canRegisterItem($cod , $qtd);
            
            $item->cod = $cod ?: $item->cod;
            $item->qtd = !is_null($qtd) ?  $qtd : $item->qtd;
            $item->item = $this->field($data, 'item') ?: $item->item;
            $item->unidade = $this->field($data, 'unidade') ?: $item->unidade;

            $item->save();
        } catch(Exception $e) {
            return redirect()->route('itens.index')->with('error', $e->getMessage());
        }
    }

    public function deleteItem(Item $item)
    {
        $item->delete();
    }

    public function countItens()
    {
        return [
            'availableItemsCount' => Item::where('qtd', '>', 0)->count(),
            'unavailableItemsCount' => Item::where('qtd', '=', 0)->count()
        ];
    }

    private function getAvailableItems()
    {
        return Item::where('qtd', '>', 0)->paginate(15);
    }

    private function getUnavailableItems()
    {
        return Item::where('qtd', '=', 0)->paginate(15);
    }
    private function canRegisterItem(?string $cod = null, ?int $qtd = null)
    {
        $itemExists = Item::whereCod($cod)->exists();
        
        if($itemExists) {
            throw new \Exception("O item informado já está cadastrado na base de dados!");
        }

        if($qtd < 0) {
            throw new \Exception("A quantidade informada é inválida!");
        }
    }

    private function field(array $data, string $key)
    {
        return array_key_exists($key, $data) && !is_null($data[$key]) ? $data[$key] : null;
    }
}