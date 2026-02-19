<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Variable;
use App\Traits\LogsActivity;
use App\Traits\PurifiesHtml;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class AdminBaseController extends BaseController
{
    use AuthorizesRequests, LogsActivity, PurifiesHtml, ValidatesRequests;

    public function __construct()
    {

        $this->middleware(Variable::ROLE_ADMIN);
    }

    /**
     * Upload an image to storage
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string Path to uploaded file
     */
    protected function uploadImage(UploadedFile $file, string $directory = 'images'): string
    {
        $filename = time() . '_' . uniqid('', true) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, 'public');
        
        return $path;
    }

    /**
     * Delete an image from storage
     *
     * @param string|null $path
     * @return bool
     */
    protected function deleteImage(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}
