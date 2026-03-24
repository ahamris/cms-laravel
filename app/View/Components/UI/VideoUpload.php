<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class VideoUpload extends Component
{
    public string $id;

    public string $name;

    public ?string $currentVideoUrl;

    public string $label;

    public ?string $helpText;

    public bool $required;

    public string $accept;

    public int $maxSize;

    public string $removeInputName;

    public function __construct(
        string $id = 'video_upload',
        string $name = 'video_file',
        ?string $currentVideoUrl = null,
        string $label = 'Video',
        ?string $helpText = null,
        bool $required = false,
        string $accept = 'video/mp4,video/webm,video/ogg,video/quicktime',
        int $maxSize = 51200,
        string $removeInputName = 'remove_video_file',
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->currentVideoUrl = $currentVideoUrl;
        $this->label = $label;
        $this->helpText = $helpText ?? 'MP4, WebM, or OGG — max '.round($maxSize / 1024, 0).' MB';
        $this->required = $required;
        $this->accept = $accept;
        $this->maxSize = $maxSize;
        $this->removeInputName = $removeInputName;
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.video-upload');
    }
}
