<?php

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
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
        $full = $this->storageRoot().DIRECTORY_SEPARATOR.$path;
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
        $after = Str::after($fullPathReal, $root.DIRECTORY_SEPARATOR);

        return ltrim(str_replace('\\', '/', $after), '/');
    }

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (! Gate::allows('media_access')) {
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

        $q = trim((string) $request->query('q', ''));
        $type = (string) $request->query('type', 'all'); // all|images|documents|svg
        $sort = (string) $request->query('sort', 'modified_desc'); // modified_desc|modified_asc|name_asc|name_desc

        $allowedImageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $allowedDocumentExts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar'];

        $folders = [];
        $files = [];

        foreach (scandir($currentPath) as $name) {
            if ($name === '.' || $name === '..' || str_starts_with($name, '.')) {
                continue;
            }
            $full = $currentPath.DIRECTORY_SEPARATOR.$name;
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
                if ($q === '' || str_contains(strtolower($item['name']), strtolower($q))) {
                    $folders[] = $item;
                }
            } else {
                if ($q !== '' && ! str_contains(strtolower($item['name']), strtolower($q))) {
                    continue;
                }

                $ext = strtolower((string) ($item['extension'] ?? ''));
                $passesType = match ($type) {
                    'all' => true,
                    'svg' => $ext === 'svg',
                    'images' => in_array($ext, $allowedImageExts, true),
                    'documents' => in_array($ext, $allowedDocumentExts, true),
                    default => true,
                };

                if ($passesType) {
                    $files[] = $item;
                }
            }
        }

        // When filtering by type, keep the experience focused: hide folder entries.
        if ($type !== 'all') {
            $folders = [];
        }

        // Sorting
        usort($folders, function ($a, $b) use ($sort) {
            $nameA = strtolower((string) $a['name']);
            $nameB = strtolower((string) $b['name']);

            return match ($sort) {
                'name_desc' => strcasecmp($nameB, $nameA),
                'name_asc', 'modified_desc', 'modified_asc' => strcasecmp($nameA, $nameB),
                default => strcasecmp($nameA, $nameB),
            };
        });

        usort($files, function ($a, $b) use ($sort) {
            $nameA = strtolower((string) $a['name']);
            $nameB = strtolower((string) $b['name']);
            $modifiedA = (int) ($a['modified'] ?? 0);
            $modifiedB = (int) ($b['modified'] ?? 0);

            return match ($sort) {
                'name_asc' => strcasecmp($nameA, $nameB),
                'name_desc' => strcasecmp($nameB, $nameA),
                'modified_asc' => $modifiedA <=> $modifiedB,
                default => $modifiedB <=> $modifiedA,
            };
        });

        // Attach Media records (title/alt/url etc) when available.
        $paths = array_values(array_filter(array_map(fn ($f) => $f['relative_path'] ?? null, $files), fn ($p) => is_string($p) && $p !== ''));
        $mediaByPath = [];
        if (! empty($paths)) {
            $mediaByPath = Media::query()
                ->where('disk', 'public')
                ->whereIn('path', $paths)
                ->get()
                ->keyBy('path')
                ->all();
        }

        $files = array_map(function ($item) use ($mediaByPath) {
            $path = (string) ($item['relative_path'] ?? '');
            /** @var Media|null $media */
            $media = $path !== '' ? ($mediaByPath[$path] ?? null) : null;
            $item['media'] = $media ? [
                'id' => $media->id,
                'title' => (string) ($media->title ?? ''),
                'alt_text' => (string) ($media->alt_text ?? ''),
                'mime_type' => (string) ($media->mime_type ?? ''),
                'size' => (int) ($media->size ?? 0),
                'width' => $media->width,
                'height' => $media->height,
                'url' => (string) $media->url,
            ] : null;

            // Always provide a public URL (even if no Media record yet).
            // Using asset('storage/...') keeps IDE static analysis happy.
            $item['public_url'] = $path !== '' ? (string) asset('storage/'.ltrim($path, '/')) : '';

            return $item;
        }, $files);

        $breadcrumbs = $this->breadcrumbs($currentPath);

        return view('admin.media-library.index', [
            'folders' => $folders,
            'files' => $files,
            'currentRelativePath' => $this->relativePath($currentPath),
            'currentPathParam' => base64_encode($this->relativePath($currentPath)),
            'breadcrumbs' => $breadcrumbs,
            'storageRoot' => $this->storageRoot(),
            'query' => $q,
            'typeFilter' => $type,
            'sort' => $sort,
        ]);
    }

    /**
     * Create (or return) a Media record for an existing file path.
     */
    public function syncMedia(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $decoded = base64_decode((string) $request->input('path'), true);
        $fullPath = $decoded !== false ? $this->resolvePath($decoded) : null;

        if ($fullPath === null || ! is_file($fullPath)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        $rel = $this->relativePath($fullPath);
        if ($rel === '') {
            return response()->json(['message' => 'Invalid path.'], 422);
        }

        $existing = Media::query()->where('disk', 'public')->where('path', $rel)->first();
        if ($existing) {
            return response()->json(['data' => $existing]);
        }

        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
        $size = file_exists($fullPath) ? (int) filesize($fullPath) : 0;
        $width = null;
        $height = null;
        if (str_starts_with($mime, 'image/')) {
            $dim = @getimagesize($fullPath);
            if ($dim) {
                [$width, $height] = $dim;
            }
        }

        $folder = str_contains($rel, '/') ? Str::beforeLast($rel, '/') : null;
        $filename = basename($rel);

        $media = Media::create([
            'filename' => $filename,
            'original_filename' => $filename,
            'path' => $rel,
            'disk' => 'public',
            'mime_type' => $mime,
            'size' => $size,
            'width' => $width,
            'height' => $height,
            'folder' => $folder,
            'uploaded_by' => Auth::id(),
        ]);

        return response()->json(['data' => $media], 201);
    }

    /**
     * Bulk delete multiple paths (base64 encoded relative paths from the view).
     */
    public function bulkDestroy(Request $request)
    {
        $paths = $request->input('paths', []);
        if (! is_array($paths)) {
            $paths = [];
        }

        $deleted = 0;
        foreach ($paths as $pathParam) {
            if (! is_string($pathParam) || $pathParam === '') {
                continue;
            }

            $decoded = base64_decode($pathParam, true);
            $fullPath = $decoded !== false ? $this->resolvePath($decoded) : null;

            if ($fullPath === null) {
                continue;
            }

            if ($fullPath === $this->storageRoot()) {
                continue; // never delete root
            }

            if (is_dir($fullPath)) {
                if (File::deleteDirectory($fullPath)) {
                    $deleted++;
                }

                continue;
            }

            if (is_file($fullPath)) {
                if (@unlink($fullPath)) {
                    $deleted++;
                }
            }
        }

        return back()->with('success', 'Deleted '.$deleted.' item(s).');
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
                $acc .= ($acc === '' ? '' : DIRECTORY_SEPARATOR).$part;
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
     * Upload file(s) and create Media records.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'file|max:51200',
            'folder' => 'nullable|string|max:200',
            'redirect_path' => 'nullable|string',
        ]);

        $folder = $request->input('folder', 'uploads');
        $results = [];

        foreach ($request->file('files') as $file) {
            $filename = time().'_'.uniqid('', true).'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs($folder, $filename, 'public');

            $width = null;
            $height = null;
            if (str_starts_with($file->getMimeType(), 'image/')) {
                $dimensions = @getimagesize($file->getRealPath());
                if ($dimensions) {
                    [$width, $height] = $dimensions;
                }
            }

            $media = Media::create([
                'filename' => $filename,
                'original_filename' => $file->getClientOriginalName(),
                'path' => $path,
                'disk' => 'public',
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'width' => $width,
                'height' => $height,
                'folder' => $folder,
                'uploaded_by' => Auth::id(),
            ]);

            $results[] = [
                'id' => $media->id,
                'url' => $media->url,
                'filename' => $media->original_filename,
            ];
        }

        $redirectPathParam = (string) $request->input('redirect_path', '');
        if ($request->expectsJson()) {
            return response()->json(['data' => $results], 201);
        }

        $redirectParams = [];
        if ($redirectPathParam !== '') {
            $redirectParams['path'] = $redirectPathParam;
        }

        return redirect()
            ->route('admin.media-library.index', $redirectParams)
            ->with('success', 'Uploaded '.count($results).' file(s).');
    }

    /**
     * Update media metadata.
     */
    public function updateMedia(Request $request, int $id): JsonResponse
    {
        $media = Media::findOrFail($id);

        $validated = $request->validate([
            'alt_text' => 'nullable|string|max:300',
            'title' => 'nullable|string|max:300',
            'folder' => 'nullable|string|max:200',
        ]);

        $media->update($validated);

        return response()->json(['data' => $media]);
    }

    /**
     * Delete media record and file.
     */
    public function destroyMedia(int $id): JsonResponse
    {
        $media = Media::findOrFail($id);

        Storage::disk($media->disk)->delete($media->path);
        $media->delete();

        return response()->json(['message' => 'Media deleted.']);
    }

    /**
     * List virtual folders.
     */
    public function folders(): JsonResponse
    {
        $folders = Media::select('folder')
            ->distinct()
            ->whereNotNull('folder')
            ->orderBy('folder')
            ->pluck('folder');

        return response()->json(['data' => $folders]);
    }

    /**
     * Move files between folders.
     */
    public function moveMedia(Request $request): JsonResponse
    {
        $request->validate([
            'media_ids' => 'required|array',
            'media_ids.*' => 'exists:media,id',
            'folder' => 'required|string|max:200',
        ]);

        Media::whereIn('id', $request->input('media_ids'))
            ->update(['folder' => $request->input('folder')]);

        return response()->json(['message' => 'Files moved.']);
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
