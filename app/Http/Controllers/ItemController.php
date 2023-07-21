<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Validation\Rule;
use App\Models\ShoppingList;

class ItemController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        //            'list_id' => [
//                'required',
//                Rule::exists('items')->where(function($query){
//                    $query->where('list_id', request()->list_id)
//                        ->where('id', request()->item_id);
//                }),
//            ],
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'quantity' => 'numeric',
                'instructions' => 'string|nullable'
            ]);

        } catch (\Exception $exception) {
//            dd($exception);
        }

        $owningList = ShoppingList::findOrFail($request->list_id);

        $item = Item::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'instructions' => $request->instructions,
            'shopping_list_id' => $owningList->id
        ]);

        $item->shoppingList()->associate($owningList)->save();
        $owningList->updated_at = now();
        $owningList->save();
        return redirect()->route('lists.show', ['list' => $owningList])->with('message', 'Item added successfully.');
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
