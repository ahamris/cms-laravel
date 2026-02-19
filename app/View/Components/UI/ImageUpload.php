<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImageUpload extends Component
{
    public string $id;
    public string $name;
    public ?string $currentImage;
    public string $currentImageAlt;
    public string $label;
    public ?string $helpText;
    public bool $required;
    public string $accept;
    public int $maxSize;
    public string $size;
    public array $sizeClasses;

    public function __construct(
        string $id = 'image',
        string $name = 'image',
        ?string $currentImage = null,
        string $currentImageAlt = 'image',
        string $label = 'Image',
        ?string $helpText = 'Upload image (max 8MB)',
        bool $required = false,
        string $accept = 'image/*',
        int $maxSize = 8192,
        string $size = 'small'
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->currentImage = $currentImage;
        $this->currentImageAlt = $currentImageAlt;
        $this->label = $label;
        $this->helpText = $helpText;
        $this->required = $required;
        $this->accept = $accept;
        $this->maxSize = $maxSize;
        $this->size = $size;

        $this->sizeClasses = [
            'small' => [
                'preview' => 'w-24 h-24',
                'current' => 'w-20 h-20',
                'icon' => 'w-10 h-10',
                'iconSize' => 'text-lg',
                'padding' => 'p-4',
                'text' => 'text-xs',
            ],
            'medium' => [
                'preview' => 'w-40 h-40',
                'current' => 'w-32 h-32',
                'icon' => 'w-12 h-12',
                'iconSize' => 'text-xl',
                'padding' => 'p-6',
                'text' => 'text-sm',
            ],
            'large' => [
                'preview' => 'w-64 h-64',
                'current' => 'w-48 h-48',
                'icon' => 'w-16 h-16',
                'iconSize' => 'text-2xl',
                'padding' => 'p-8',
                'text' => 'text-base',
            ],
        ];
    }

    public function getSizeConfig(): array
    {
        return $this->sizeClasses[$this->size] ?? $this->sizeClasses['small'];
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.image-upload');
    }
}
