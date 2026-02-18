<?php

namespace App\Livewire\Admin\Concerns;

use Illuminate\Support\Facades\Route;

trait LoadsMenuOptions
{
    protected function loadRouteOptions(): array
    {
        return collect(Route::getRoutes())
            ->filter(function ($route) {
                $name = $route->getName();
                // Only include admin routes that end with .index or .create
                return $name 
                    && str_starts_with($name, 'admin.')
                    && (str_ends_with($name, '.index') || str_ends_with($name, '.create'));
            })
            ->map(function ($route) {
                $name = $route->getName();
                $uri = $route->uri();
                
                // Create a better display name
                // e.g., "admin.users.index" -> "Users (List)"
                // e.g., "admin.users.create" -> "Users (Create)"
                $parts = explode('.', $name);
                $resourceName = $parts[1] ?? '';
                $action = $parts[2] ?? '';
                
                // Convert snake_case to Title Case
                $displayName = str($resourceName)
                    ->replace('_', ' ')
                    ->title()
                    ->toString();
                
                $actionLabel = $action === 'index' ? 'List' : 'Create';
                $label = "{$displayName} ({$actionLabel})";
                
                return [
                    'name' => $name,
                    'uri' => $uri,
                    'methods' => implode('|', $route->methods()),
                    'label' => $label,
                ];
            })
            ->unique('name') // Remove duplicate route names
            ->sortBy('name')
            ->values()
            ->all();
    }

    protected function loadModelOptions(): array
    {
        $models = [];
        $modelsPath = app_path('Models');

        if (! is_dir($modelsPath)) {
            return $models;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($modelsPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $relativePath = str_replace(
                    [$modelsPath, '.php'],
                    ['', ''],
                    $file->getPathname()
                );

                $relativePath = str_replace(['/', '\\'], '\\', $relativePath);
                $relativePath = trim($relativePath, '\\');

                $className = 'App\\Models\\'.$relativePath;

                if (class_exists($className) && is_subclass_of($className, \Illuminate\Database\Eloquent\Model::class)) {
                    try {
                        $reflection = new \ReflectionClass($className);
                        if (! $reflection->isAbstract() && ! $reflection->isInterface()) {
                            $models[] = [
                                'class' => $className,
                                'name' => class_basename($className),
                            ];
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }

        return collect($models)
            ->unique('class') // Remove duplicate model classes
            ->sortBy('name')
            ->values()
            ->all();
    }

    public function getAvailableIconsProperty(): array
    {
        return [
            'house' => 'Home',
            'gauge' => 'Dashboard',
            'users' => 'Users',
            'user-shield' => 'Roles',
            'box' => 'Products',
            'tags' => 'Tags',
            'layer-group' => 'Categories',
            'file-lines' => 'Pages',
            'blog' => 'Blog',
            'comments' => 'Comments',
            'envelope' => 'Messages',
            'gear' => 'Settings',
            'palette' => 'Appearance',
            'image' => 'Media',
            'chart-line' => 'Analytics',
            'shield-halved' => 'Security',
            'bell' => 'Notifications',
            'circle-question' => 'Help',
            'right-from-bracket' => 'Logout',
            'bars' => 'Menu',
            'link' => 'Link',
            'folder' => 'Folder',
            'calendar' => 'Calendar',
            'clock' => 'Clock',
            'check' => 'Check',
            'xmark' => 'X Mark',
            'trash' => 'Trash',
            'pen' => 'Pen',
            'plus' => 'Plus',
            'magnifying-glass' => 'Search',
            'filter' => 'Filter',
            'sort' => 'Sort',
            'arrow-right' => 'Arrow Right',
            'chevron-down' => 'Chevron Down',
            'globe' => 'Globe',
            'location-dot' => 'Location',
            'phone' => 'Phone',
            'credit-card' => 'Credit Card',
            'cart-shopping' => 'Cart',
            'truck' => 'Truck',
            'shop' => 'Shop',
        ];
    }
}
