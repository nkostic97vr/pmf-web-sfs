<?php

namespace App\Http\Controllers\Board\Publicus;

use App\Board;

class CategoriesController
{
    public function show($board_address, $category_slug)
    {
        $board = Board::where('address', $board_address)->firstOrFail();
        $category = $board->categories()->where('slug', $category_slug)->firstOrFail();

        return view('public.category')
            ->with('category', $category)
            ->with('current_board', $board)
            ->with('is_admin', $board->is_admin());
    }
}
