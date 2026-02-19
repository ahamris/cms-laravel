<?php

namespace App\Services;

use App\Models\TailwindPlus;

class TailwindPlusComponentService
{
    /**
     * Get all components structure from database.
     *
     * @return array<string, array> Category-based component structure
     */
    public function getComponentsStructureFromDatabase(): array
    {
        $components = TailwindPlus::active()
            ->orderBy('category')
            ->orderBy('component_group')
            ->orderBy('component_name')
            ->get();

        $structure = [];

        foreach ($components as $component) {
            $category = $this->normalizeCategoryName($component->category);
            $section = $this->normalizeSectionName($component->component_group);

            // Initialize category if not exists
            if (! isset($structure[$category])) {
                $structure[$category] = [];
            }

            // Initialize section if not exists
            if (! isset($structure[$category][$section])) {
                $structure[$category][$section] = [
                    'name' => $this->formatSectionName($component->component_group),
                    'components' => [],
                ];
            }

            // Build component path: category/component_group/component_name (kebab-case)
            $path = $this->buildComponentPath($category, $component->component_group, $component->component_name);

            // Add component
            $structure[$category][$section]['components'][] = [
                'id' => $component->id,
                'name' => $this->formatComponentName($component->component_name),
                'path' => $path,
                'raw_name' => $component->component_name,
            ];
        }

        return $structure;
    }

    /**
     * Get component HTML from database.
     */
    public function getComponentHtmlFromDatabase(int $componentId): ?string
    {
        $component = TailwindPlus::find($componentId);

        if (! $component || ! $component->code) {
            return null;
        }

        return $component->code;
    }

    /**
     * Get component by path.
     */
    public function getComponentByPath(string $path): ?TailwindPlus
    {
        // Parse path: category/component_group/component_name
        $parts = explode('/', $path);

        if (count($parts) < 3) {
            return null;
        }

        $category = $this->denormalizeCategoryName($parts[0]);
        $componentGroup = $this->denormalizeSectionName($parts[1]);
        $componentNameKebab = $parts[2]; // Keep the kebab-case version for matching

        // Try exact match first with denormalized name
        $componentName = $this->denormalizeComponentName($componentNameKebab);
        $component = TailwindPlus::active()
            ->where('category', $category)
            ->where('component_group', $componentGroup)
            ->where('component_name', $componentName)
            ->first();

        // If not found, try case-insensitive match with normalized comparison
        // This handles cases where database has "Three-column with background images"
        // but path is "three-column-with-background-images"
        if (! $component) {
            $normalizedKebab = strtolower($componentNameKebab);
            $components = TailwindPlus::active()
                ->where('category', $category)
                ->where('component_group', $componentGroup)
                ->get();

            foreach ($components as $comp) {
                // Normalize database name to kebab-case for comparison
                $dbNormalized = strtolower(str_replace(' ', '-', $comp->component_name));
                if ($dbNormalized === $normalizedKebab) {
                    return $comp;
                }
            }
        }

        return $component;
    }

    /**
     * Normalize category name to match magic-builder format (lowercase, kebab-case).
     */
    private function normalizeCategoryName(string $category): string
    {
        $category = strtolower($category);

        // Map common category names
        $mapping = [
            'marketing' => 'marketing',
            'application ui' => 'application-ui',
            'application-ui' => 'application-ui',
            'ecommerce' => 'ecommerce',
        ];

        return $mapping[$category] ?? str_replace(' ', '-', $category);
    }

    /**
     * Denormalize category name back to database format.
     */
    private function denormalizeCategoryName(string $category): string
    {
        $mapping = [
            'marketing' => 'Marketing',
            'application-ui' => 'Application UI',
            'ecommerce' => 'Ecommerce',
        ];

        return $mapping[$category] ?? ucwords(str_replace('-', ' ', $category));
    }

    /**
     * Normalize section name to kebab-case.
     */
    private function normalizeSectionName(string $section): string
    {
        return strtolower(str_replace(' ', '-', $section));
    }

    /**
     * Denormalize section name back to database format.
     */
    private function denormalizeSectionName(string $section): string
    {
        return ucwords(str_replace('-', ' ', $section));
    }

    /**
     * Denormalize component name back to database format.
     */
    private function denormalizeComponentName(string $name): string
    {
        return ucwords(str_replace('-', ' ', $name));
    }

    /**
     * Build component path in format: category/component_group/component_name (kebab-case).
     */
    private function buildComponentPath(string $category, string $componentGroup, string $componentName): string
    {
        $normalizedGroup = $this->normalizeSectionName($componentGroup);
        $normalizedName = strtolower(str_replace(' ', '-', $componentName));

        return "{$category}/{$normalizedGroup}/{$normalizedName}";
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
     * Get header components for selection.
     *
     * @return array<int, array{id: int, name: string, path: string}>
     */
    public function getHeaderComponents(): array
    {
        $allowedNames = [
            'Constrained',
            'Full width',
            'With call-to-action',
            'With centered logo',
            'With full width flyout menu',
            'With icons in mobile menu',
            'With left-aligned nav',
            'With multiple flyout menus',
            'With right-aligned nav',
            'With stacked flyout menu',
        ];

        $components = TailwindPlus::active()
            ->where('category', 'Marketing')
            ->where(function ($query) {
                $query->where('component_group', 'LIKE', '%Elements%Header%')
                    ->orWhere('component_group', 'LIKE', '%Header%');
            })
            ->whereIn('component_name', $allowedNames)
            ->orderBy('component_group')
            ->orderBy('component_name')
            ->get();

        $result = [];
        foreach ($components as $component) {
            $category = $this->normalizeCategoryName($component->category);
            $path = $this->buildComponentPath($category, $component->component_group, $component->component_name);

            $result[] = [
                'id' => $component->id,
                'name' => $this->formatComponentName($component->component_name),
                'raw_name' => $component->component_name, // Raw name for comparison
                'path' => $path,
            ];
        }

        return $result;
    }

    /**
     * Get footer components for selection.
     *
     * @return array<int, array{id: int, name: string, path: string}>
     */
    public function getFooterComponents(): array
    {
        $allowedNames = [
            '4-column simple',
            '4-column with call-to-action',
            '4-column with company mission',
            '4-column with newsletter below',
            '4-column with newsletter',
            'Simple centered',
            'Simple with social links',
        ];

        $components = TailwindPlus::active()
            ->where('category', 'Marketing')
            ->where(function ($query) {
                $query->where('component_group', 'LIKE', '%Page Sections%Footer%')
                    ->orWhere('component_group', 'LIKE', '%Footer%');
            })
            ->whereIn('component_name', $allowedNames)
            ->orderBy('component_group')
            ->orderBy('component_name')
            ->get();

        $result = [];
        foreach ($components as $component) {
            $category = $this->normalizeCategoryName($component->category);
            $path = $this->buildComponentPath($category, $component->component_group, $component->component_name);

            $result[] = [
                'id' => $component->id,
                'name' => $this->formatComponentName($component->component_name),
                'raw_name' => $component->component_name, // Raw name for comparison
                'path' => $path,
            ];
        }

        return $result;
    }

    /**
     * Get flyout menu components for selection.
     *
     * @return array<int, array{id: int, name: string, raw_name: string, path: string}>
     */
    public function getFlyoutMenuComponents(): array
    {
        $allowedNames = [
            'Simple',
            'Simple with descriptions',
            'Two-column',
            'Full-width',
            'Full-width two-columns',
            'Stacked with footer actions',
        ];

        $components = TailwindPlus::active()
            ->where('category', 'Marketing')
            ->where(function ($query) {
                $query->where('component_group', 'LIKE', '%Elements%Flyout Menus%')
                    ->orWhere('component_group', 'LIKE', '%Flyout Menus%');
            })
            ->whereIn('component_name', $allowedNames)
            ->orderBy('component_group')
            ->orderBy('component_name')
            ->get();

        $result = [];
        foreach ($components as $component) {
            $category = $this->normalizeCategoryName($component->category);
            $path = $this->buildComponentPath($category, $component->component_group, $component->component_name);

            $result[] = [
                'id' => $component->id,
                'name' => $this->formatComponentName($component->component_name),
                'raw_name' => $component->component_name, // Raw name for comparison
                'path' => $path,
            ];
        }

        return $result;
    }
}
