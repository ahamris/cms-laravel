<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaLibraryController extends AdminBaseController
{
    /** Root of the file manager: only storage/app/public is accessible. */
    private function storageRoot(): string
    {
        return rtrim(storage_path('app/public'), DIRECTORY_SEPARATOR);
    }

    /** Canonical storage root (realpath) so path comparison works on Windows. */
    private function storageRootReal(): string
    {
        $root = realpath($this->storageRoot());

        return $root !== false ? $root : $this->storageRoot();
    }

    /**
     * Resolve and validate path: must be under storage root.
     */
    private function resolvePath(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return $this->storageRootReal();
        }

        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        $path = ltrim($path, DIRECTORY_SEPARATOR);
        $full = $this->storageRoot() . DIRECTORY_SEPARATOR . $path;
        $real = realpath($full);

        if ($real === false || ! Str::startsWith($real, $this->storageRootReal())) {
            return null;
        }

        return $real;
    }

    /**
     * Relative path from storage root for display/links.
     */
    private function relativePath(string $fullPath): string
    {
        $root = $this->storageRootReal();
        $fullPathReal = realpath($fullPath) ?: $fullPath;
        if ($fullPathReal === $root) {
            return '';
        }
        $after = Str::after($fullPathReal, $root . DIRECTORY_SEPARATOR);

        return ltrim(str_replace('\\', '/', $after), '/');
    }

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (! \Illuminate\Support\Facades\Gate::allows('media_access')) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Browse storage: list folders and files.
     */
    public function index(Request $request)
    {
        $pathParam = $request->query('path');
        $decoded = $pathParam !== null && $pathParam !== '' ? base64_decode($pathParam, true) : '';
        $currentPath = $this->resolvePath($decoded === false ? null : $decoded);

        if ($currentPath === null) {
            return redirect()->route('admin.media-library.index')->with('error', 'Invalid path.');
        }

        if (! is_dir($currentPath)) {
            return redirect()->route('admin.media-library.index')->with('error', 'Path is not a directory.');
        }

        $folders = [];
        $files = [];

        foreach (scandir($currentPath) as $name) {
            if ($name === '.' || $name === '..' || str_starts_with($name, '.')) {
                continue;
            }
            $full = $currentPath . DIRECTORY_SEPARATOR . $name;
            $rel = $this->relativePath($full);
            $isDir = is_dir($full);
            $item = [
                'name' => $name,
                'relative_path' => $rel,
                'path_param' => base64_encode($rel),
                'is_dir' => $isDir,
                'size' => $isDir ? null : (file_exists($full) ? filesize($full) : 0),
                'modified' => filemtime($full),
                'extension' => $isDir ? null : strtolower(pathinfo($name, PATHINFO_EXTENSION)),
            ];
            if ($isDir) {
                $folders[] = $item;
            } else {
                $files[] = $item;
            }
        }

        usort($folders, fn ($a, $b) => strcasecmp($a['name'], $b['name']));
        usort($files, fn ($a, $b) => strcasecmp($a['name'], $b['name']));

        $breadcrumbs = $this->breadcrumbs($currentPath);

        return view('admin.media-library.index', [
            'folders' => $folders,
            'files' => $files,
            'currentRelativePath' => $this->relativePath($currentPath),
            'currentPathParam' => base64_encode($this->relativePath($currentPath)),
            'breadcrumbs' => $breadcrumbs,
            'storageRoot' => $this->storageRoot(),
        ]);
    }

    private function breadcrumbs(string $currentPath): array
    {
        $root = $this->storageRootReal();
        $crumbs = [['name' => 'Public', 'path' => '', 'path_param' => '']];

        if ($currentPath !== $root) {
            $rel = $this->relativePath($currentPath);
            $parts = explode(DIRECTORY_SEPARATOR, str_replace('/', DIRECTORY_SEPARATOR, $rel));
            $acc = '';
            foreach ($parts as $i => $part) {
                $acc .= ($acc === '' ? '' : DIRECTORY_SEPARATOR) . $part;
                $crumbs[] = [
                    'name' => $part,
                    'path' => $acc,
                    'path_param' => base64_encode($acc),
                ];
            }
        }

        return $crumbs;
    }

    /**
     * Download a file.
     */
    public function download(Request $request)
    {
        $pathParam = $request->query('path');
        $decoded = $pathParam ? base64_decode($pathParam, true) : null;
        $fullPath = $decoded !== false ? $this->resolvePath($decoded) : null;

        if ($fullPath === null || ! is_file($fullPath)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        $filename = basename($fullPath);

        return response()->streamDownload(function () use ($fullPath) {
            $stream = fopen($fullPath, 'r');
            if ($stream) {
                fpassthru($stream);
                fclose($stream);
            }
        }, $filename, [
            'Content-Type' => mime_content_type($fullPath) ?: 'application/octet-stream',
        ]);
    }

    /**
     * Delete a file or directory (recursive).
     */
    public function destroy(Request $request)
    {
        $pathParam = $request->input('path');
        $decoded = $pathParam ? base64_decode($pathParam, true) : null;
        $fullPath = $decoded !== false ? $this->resolvePath($decoded) : null;

        if ($fullPath === null) {
            return back()->with('error', 'Invalid path.');
        }

        if ($fullPath === $this->storageRoot()) {
            return back()->with('error', 'Cannot delete storage root.');
        }

        if (is_dir($fullPath)) {
            if (! File::deleteDirectory($fullPath)) {
                return back()->with('error', 'Could not delete folder.');
            }
            return back()->with('success', 'Folder deleted.');
        }

        if (is_file($fullPath)) {
            if (! unlink($fullPath)) {
                return back()->with('error', 'Could not delete file.');
            }
            return back()->with('success', 'File deleted.');
        }

        return back()->with('error', 'Path not found.');
    }

    /**
     * Resize image and optionally save (replace or new name).
     */
    public function resize(Request $request)
    {
        $pathParam = $request->input('path');
        $decoded = $pathParam ? base64_decode($pathParam, true) : null;
        $fullPath = $decoded !== false ? $this->resolvePath($decoded) : null;

        if ($fullPath === null || ! is_file($fullPath)) {
            return back()->with('error', 'File not found.');
        }

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (! in_array($ext, $imageExts, true)) {
            return back()->with('error', 'File is not a supported image.');
        }

        $maxWidth = (int) $request->input('width');
        $maxHeight = (int) $request->input('height');
        if ($maxWidth < 1 && $maxHeight < 1) {
            return back()->with('error', 'Specify at least width or height.');
        }
        $maxWidth = $maxWidth < 1 ? PHP_INT_MAX : $maxWidth;
        $maxHeight = $maxHeight < 1 ? PHP_INT_MAX : $maxHeight;

        $image = $this->loadImage($fullPath, $ext);
        if ($image === null) {
            return back()->with('error', 'Could not load image.');
        }

        $origW = imagesx($image);
        $origH = imagesy($image);
        $width = $origW;
        $height = $origH;
        if ($origW > $maxWidth || $origH > $maxHeight) {
            $ratio = min($maxWidth / $origW, $maxHeight / $origH);
            $width = (int) round($origW * $ratio);
            $height = (int) round($origH * $ratio);
            $width = max(1, $width);
            $height = max(1, $height);
        } else {
            imagedestroy($image);
            return back()->with('info', 'Image is already smaller than or equal to the requested size.');
        }

        $thumb = imagecreatetruecolor($width, $height);
        if ($thumb === false) {
            imagedestroy($image);
            return back()->with('error', 'Could not create resized image.');
        }

        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $width, $height, $origW, $origH);
        imagedestroy($image);

        $saved = $this->saveImage($thumb, $fullPath, $ext);
        imagedestroy($thumb);

        if (! $saved) {
            return back()->with('error', 'Could not save resized image.');
        }

        return back()->with('success', 'Image resized successfully.');
    }

    private function loadImage(string $path, string $ext): ?\GdImage
    {
        return match ($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path) ?: null,
            'png' => @imagecreatefrompng($path) ?: null,
            'gif' => @imagecreatefromgif($path) ?: null,
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null,
            default => null,
        };
    }

    private function saveImage(\GdImage $image, string $path, string $ext): bool
    {
        return match ($ext) {
            'jpg', 'jpeg' => imagejpeg($image, $path, 90),
            'png' => imagepng($image, $path, 8),
            'gif' => imagegif($image, $path),
            'webp' => function_exists('imagewebp') ? imagewebp($image, $path, 90) : false,
            default => false,
        };
    }

    /**
     * Preview URL for use in iframe or img (serve file for preview).
     */
    public function preview(Request $request): StreamedResponse
    {
        $pathParam = $request->query('path');
        $decoded = $pathParam ? base64_decode($pathParam, true) : null;
        $fullPath = $decoded !== false ? $this->resolvePath($decoded) : null;

        if ($fullPath === null || ! is_file($fullPath)) {
            abort(404);
        }

        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
        $filename = basename($fullPath);

        return response()->streamDownload(function () use ($fullPath) {
            $h = fopen($fullPath, 'r');
            if ($h) {
                fpassthru($h);
                fclose($h);
            }
        }, $filename, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline',
        ]);
    }
}
