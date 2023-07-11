<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use Illuminate\Http\Request;

class ShoppingListController extends Controller
{
    public function index()
    {

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
        ]);
        return redirect()->route('lists')->with('message', '\'' . $request->name . '\' list created successfully.');
    }

}
