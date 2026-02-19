<?php

namespace App\View\Components\Front;

use App\Models\Blog;
use App\Models\CarouselWidget as CarouselWidgetModel;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CarouselWidget extends Component
{
    public ?CarouselWidgetModel $carouselWidget;

    public $items;

    /**
     * Create a new component instance.
     */
    public function __construct(string $identifier)
    {
        $this->carouselWidget = CarouselWidgetModel::getByIdentifier($identifier);
        $this->items = collect();

        if ($this->carouselWidget && $this->carouselWidget->data_source === 'blog') {
            $limit = (int) $this->carouselWidget->total_items ?: 6;
            if ($this->carouselWidget->blog_category_id) {
                $this->items = Blog::getCachedCarouselBlogsByCategory(
                    $this->carouselWidget->blog_category_id,
                    $limit
                );
            } else {
                $this->items = Blog::getCachedCarouselBlogs($limit);
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.front.carousel-widget');
    }
}
