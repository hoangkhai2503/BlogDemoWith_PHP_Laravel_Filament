<?php

namespace App\View\Components;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $categories = Category::query()
                    ->leftJoin('category_posts', 'categories.id', '=', 'category_posts.category_id')
                    ->select('categories.title', 'categories.slug', DB::raw('count(*) as total'))
                    ->groupBy([
                        'categories.title', 'categories.slug'
                    ])
                    ->orderByDesc('total')
                    ->limit(10)
                    ->get();
        return view('components.sidebar', compact('categories'));
    }
}