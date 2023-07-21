<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShoppingListController extends Controller
{
    public function index()
    {

    }

    /**
     * Display a management view for users
     * that created the list.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function manage() {

    }

    /**
     * Store a newly created ShoppingList in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);
        ShoppingList::create([
            'name' => $request->name,
            'user_id' => auth()->user()->id
        ]);
        return redirect()->route('lists')->with('message', '\'' . $request->name . '\' list created successfully.');
    }

    /**
     * Display the specified ShoppingList.
     *
     * @param  \App\Models\ShoppingList  $list
     * @return \Illuminate\Http\Response
     */
    public function show(ShoppingList $list)
    {
        $items = array_reverse($list->items->where('done', false)->toArray());
        $doneItems = array_reverse($list->items->where('done', true)->toArray());

        return Inertia::render(
            'List',
            [
                'list' => $list,
                'items' => $items,
                'doneItems' => $doneItems
            ]
        );
    }

}
