<?php

namespace App\View\Components;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class AppLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public ?string $metaTitle = null, public ?string $metaDescription = null)
    {
        //
    }

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
        return view('components.app-layout', compact('categories'));
    }
}
