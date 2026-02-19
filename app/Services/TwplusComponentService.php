<?php

namespace App\Services;

class TwplusComponentService
{
    /**
     * Get all components structure from twplus directory.
     *
     * @return array<string, array> Category-based component structure
     */
    public function getComponentsStructure(): array
    {
        $basePath = resource_path('views/components/twplus');
        $structure = [];

        $categories = ['marketing', 'application-ui', 'ecommerce'];

        foreach ($categories as $category) {
            $categoryPath = $basePath.'/'.$category;
            if (is_dir($categoryPath)) {
                $structure[$category] = $this->scanDirectory($categoryPath, $category);
            }
        }

        return $structure;
    }

    /**
     * Scan directory recursively to find all blade components.
     */
    private function scanDirectory(string $path, string $category): array
    {
        $sections = [];
        $dirs = glob($path.'/*', GLOB_ONLYDIR);

        foreach ($dirs as $dir) {
            $sectionName = basename($dir);

            // First, try to find files directly in this directory
            $files = glob($dir.'/*.blade.php');

            // If no files found, check subdirectories (for nested structure like sections/heroes/)
            if (empty($files)) {
                $subDirs = glob($dir.'/*', GLOB_ONLYDIR);
                foreach ($subDirs as $subDir) {
                    $subSectionName = basename($subDir);
                    $subFiles = glob($subDir.'/*.blade.php');

                    if (! empty($subFiles)) {
                        $components = [];
                        foreach ($subFiles as $file) {
                            $componentName = str_replace('.blade.php', '', basename($file));
                            // Use dot notation for view path: category.section.subsection.component
                            $viewPath = "components.twplus.{$category}.{$sectionName}.{$subSectionName}.{$componentName}";

                            $components[] = [
                                'name' => $this->formatComponentName($componentName),
                                'path' => $viewPath,
                                'file' => $file,
                                'raw_name' => $componentName,
                            ];
                        }

                        if (! empty($components)) {
                            // Use slash for section key, but dot for view path
                            $fullSectionName = $sectionName.'/'.$subSectionName;
                            $sections[$fullSectionName] = [
                                'name' => $this->formatSectionName($sectionName).' > '.$this->formatSectionName($subSectionName),
                                'components' => $components,
                            ];
                        }
                    }
                }
            } else {
                // Files found directly in section directory
                $components = [];
                foreach ($files as $file) {
                    $componentName = str_replace('.blade.php', '', basename($file));
                    $viewPath = "components.twplus.{$category}.{$sectionName}.{$componentName}";

                    $components[] = [
                        'name' => $this->formatComponentName($componentName),
                        'path' => $viewPath,
                        'file' => $file,
                        'raw_name' => $componentName,
                    ];
                }

                if (! empty($components)) {
                    $sections[$sectionName] = [
                        'name' => $this->formatSectionName($sectionName),
                        'components' => $components,
                    ];
                }
            }
        }

        return $sections;
    }

    /**
     * Format component name for display.
     */
    private function formatComponentName(string $name): string
    {
        return ucwords(str_replace(['_', '-'], ' ', $name));
    }

    /**
     * Format section name for display.
     */
    private function formatSectionName(string $name): string
    {
        return ucwords(str_replace(['_', '-'], ' ', $name));
    }

    /**
     * Get component HTML by rendering the Blade view.
     */
    public function getComponentHtml(string $viewPath): ?string
    {
        try {
            if (! view()->exists($viewPath)) {
                return null;
            }

            return view($viewPath)->render();
        } catch (\Exception $e) {
            return null;
        }
    }
}
