<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ThemeSettings extends Component
{
    // Color Properties
    public string $colorPrimary = '#081245';

    public string $colorSecondary = '#0073e6';

    public string $colorNatural = '#dfd4d4';

    // Header & Footer Colors
    public string $footerBg = '#1a1a2e';

    public string $footerText = '#ffffff';

    public string $headerBg = '#ffffff';

    public string $headerText = '#1a1a2e';

    // Font Family Properties
    public string $fontSans = 'Inter';

    public string $fontOutfit = 'Outfit';

    // Font Search Properties
    public string $fontSearchSans = '';

    public string $fontSearchOutfit = '';

    // Font Size Properties (px values - user input)
    public ?int $fontSizeH1Px = 48;

    public ?int $fontSizeH2Px = 36;

    public ?int $fontSizeH3Px = 24;

    public ?int $fontSizeH4Px = 18;

    public ?int $fontSizeH5Px = 16;

    public ?int $fontSizeH6Px = 14;

    public ?int $fontSizePPx = 16;

    // UI State
    public bool $saved = false;

    public function mount(): void
    {
        // Load current color settings
        $this->colorPrimary = $this->getColorSetting('theme_color_primary', '#081245');
        $this->colorSecondary = $this->getColorSetting('theme_color_secondary', '#0073e6');
        $this->colorNatural = $this->getColorSetting('theme_color_natural', '#dfd4d4');

        $this->footerBg = $this->getColorSetting('theme_footer_bg', '#1a1a2e');
        $this->footerText = $this->getColorSetting('theme_footer_text', '#ffffff');
        $this->headerBg = $this->getColorSetting('theme_header_bg', '#ffffff');
        $this->headerText = $this->getColorSetting('theme_header_text', '#1a1a2e');

        // Load current font settings
        $this->fontSans = $this->getFontSetting('theme_font_sans', 'Inter');
        $this->fontOutfit = $this->getFontSetting('theme_font_outfit', 'Outfit');

        // Load font sizes and convert rem to px for display
        $this->fontSizeH1Px = $this->remToPx($this->getFontSizeSetting('theme_font_size_h1', '3rem'));
        $this->fontSizeH2Px = $this->remToPx($this->getFontSizeSetting('theme_font_size_h2', '2.25rem'));
        $this->fontSizeH3Px = $this->remToPx($this->getFontSizeSetting('theme_font_size_h3', '1.5rem'));
        $this->fontSizeH4Px = $this->remToPx($this->getFontSizeSetting('theme_font_size_h4', '1.125rem'));
        $this->fontSizeH5Px = $this->remToPx($this->getFontSizeSetting('theme_font_size_h5', '1rem'));
        $this->fontSizeH6Px = $this->remToPx($this->getFontSizeSetting('theme_font_size_h6', '0.875rem'));
        $this->fontSizePPx = $this->remToPx($this->getFontSizeSetting('theme_font_size_p', '1rem'));
    }

    public function updated($propertyName): void
    {
        // Validate font size values when they change
        if (str_starts_with($propertyName, 'fontSize')) {
            $this->ensureFontSizeValues();
        }

        // Load font when font selection changes
        if ($propertyName === 'fontSans' || $propertyName === 'fontOutfit') {
            $fontName = $propertyName === 'fontSans' ? $this->fontSans : $this->fontOutfit;
            $this->dispatch('font-changed', fontName: $fontName);
        }
    }

    public function resetToDefaults(): void
    {
        // Reset color settings to defaults
        $this->colorPrimary = '#081245';
        $this->colorSecondary = '#0073e6';
        $this->colorNatural = '#dfd4d4';

        $this->footerBg = '#1a1a2e';
        $this->footerText = '#ffffff';
        $this->headerBg = '#ffffff';
        $this->headerText = '#1a1a2e';

        // Reset font settings to defaults
        $this->fontSans = 'Inter';
        $this->fontOutfit = 'Outfit';

        // Reset font sizes to defaults
        $this->fontSizeH1Px = 48;
        $this->fontSizeH2Px = 36;
        $this->fontSizeH3Px = 24;
        $this->fontSizeH4Px = 18;
        $this->fontSizeH5Px = 16;
        $this->fontSizeH6Px = 14;
        $this->fontSizePPx = 16;
    }

    public function save(): void
    {
        // Validate all inputs
        $this->validate([
            'colorPrimary' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'colorSecondary' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'colorNatural' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'footerBg' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'footerText' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'headerBg' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'headerText' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'fontSans' => 'required|string|max:255',
            'fontOutfit' => 'required|string|max:255',
            'fontSizeH1Px' => 'required|integer|min:1|max:3200',
            'fontSizeH2Px' => 'required|integer|min:1|max:3200',
            'fontSizeH3Px' => 'required|integer|min:1|max:3200',
            'fontSizeH4Px' => 'required|integer|min:1|max:3200',
            'fontSizeH5Px' => 'required|integer|min:1|max:3200',
            'fontSizeH6Px' => 'required|integer|min:1|max:3200',
            'fontSizePPx' => 'required|integer|min:1|max:3200',
        ], [
            'colorPrimary.regex' => 'Primary color must be a valid hex color code (e.g., #081245)',
            'colorSecondary.regex' => 'Secondary color must be a valid hex color code (e.g., #0073e6)',
            'colorNatural.regex' => 'Natural color must be a valid hex color code (e.g., #dfd4d4)',
            'footerBg.regex' => 'Footer background must be a valid hex color (e.g., #1a1a2e)',
            'footerText.regex' => 'Footer text must be a valid hex color (e.g., #ffffff)',
            'headerBg.regex' => 'Header background must be a valid hex color (e.g., #ffffff)',
            'headerText.regex' => 'Header text must be a valid hex color (e.g., #1a1a2e)',
        ]);

        // Ensure all font size values are valid
        $this->ensureFontSizeValues();

        // Save color settings
        $this->updateSetting('theme_color_primary', $this->colorPrimary);
        $this->updateSetting('theme_color_secondary', $this->colorSecondary);
        $this->updateSetting('theme_color_natural', $this->colorNatural);

        $this->updateSetting('theme_footer_bg', $this->footerBg);
        $this->updateSetting('theme_footer_text', $this->footerText);
        $this->updateSetting('theme_header_bg', $this->headerBg);
        $this->updateSetting('theme_header_text', $this->headerText);

        // Save font families
        $this->updateSetting('theme_font_sans', $this->fontSans);
        $this->updateSetting('theme_font_outfit', $this->fontOutfit);

        // Save font sizes (convert px to rem)
        $this->updateSetting('theme_font_size_h1', $this->pxToRem($this->fontSizeH1Px));
        $this->updateSetting('theme_font_size_h2', $this->pxToRem($this->fontSizeH2Px));
        $this->updateSetting('theme_font_size_h3', $this->pxToRem($this->fontSizeH3Px));
        $this->updateSetting('theme_font_size_h4', $this->pxToRem($this->fontSizeH4Px));
        $this->updateSetting('theme_font_size_h5', $this->pxToRem($this->fontSizeH5Px));
        $this->updateSetting('theme_font_size_h6', $this->pxToRem($this->fontSizeH6Px));
        $this->updateSetting('theme_font_size_p', $this->pxToRem($this->fontSizePPx));

        // Clear cache
        $this->clearCaches();

        // Set saved state for visual feedback
        $this->saved = true;

        // Dispatch success event
        $this->dispatch('notify', type: 'success', message: 'Theme settings updated successfully!');
    }

    /**
     * Get computed rem value for a font size
     */
    public function getRemValue(?int $px): float
    {
        $px = $px ?? 16;

        return round($px / 16, 4);
    }

    /**
     * Ensure all font size values are set to valid numbers
     */
    private function ensureFontSizeValues(): void
    {
        $defaults = [
            'fontSizeH1Px' => 48,
            'fontSizeH2Px' => 36,
            'fontSizeH3Px' => 24,
            'fontSizeH4Px' => 18,
            'fontSizeH5Px' => 16,
            'fontSizeH6Px' => 14,
            'fontSizePPx' => 16,
        ];

        foreach ($defaults as $property => $default) {
            $value = $this->$property;
            if ($value === null || $value === '' || (is_string($value) && trim($value) === '')) {
                $this->$property = $default;
            } elseif (! is_numeric($value)) {
                $this->$property = $default;
            } elseif ((int) $value < 1) {
                $this->$property = 1;
            } elseif ((int) $value > 3200) {
                $this->$property = 3200;
            } else {
                $this->$property = (int) $value;
            }
        }
    }

    /**
     * Convert px to rem (1rem = 16px)
     */
    private function pxToRem(int $px): string
    {
        return round($px / 16, 4).'rem';
    }

    /**
     * Convert rem to px (1rem = 16px)
     */
    private function remToPx(string $remValue): int
    {
        $rem = (float) str_replace('rem', '', $remValue);

        return (int) round($rem * 16);
    }

    /**
     * Get color setting value
     */
    private function getColorSetting(string $key, string $default): string
    {
        return Setting::getValue($key, $default);
    }

    /**
     * Get font setting value
     */
    private function getFontSetting(string $key, string $default): string
    {
        $value = Setting::getValue($key, $default);

        return str_replace(['"', ', sans-serif'], '', $value);
    }

    /**
     * Get font size setting value
     */
    private function getFontSizeSetting(string $key, string $default): string
    {
        return Setting::getValue($key, $default);
    }

    /**
     * Update a setting
     */
    private function updateSetting(string $key, $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $this->getSettingType($key),
                'group' => 'theme',
                'display_name' => $this->getDisplayName($key),
                'description' => $this->getDescription($key),
                'order' => $this->getOrder($key),
            ]
        );
    }

    /**
     * Get setting type based on key
     */
    private function getSettingType(string $key): string
    {
        if (str_starts_with($key, 'theme_color_') || in_array($key, ['theme_footer_bg', 'theme_footer_text', 'theme_header_bg', 'theme_header_text'], true)) {
            return 'color';
        } elseif ($key === 'theme_font_sans' || $key === 'theme_font_outfit') {
            return 'text';
        } elseif (str_starts_with($key, 'theme_font_size_')) {
            return 'text';
        }

        return 'text';
    }

    /**
     * Get display name based on key
     */
    private function getDisplayName(string $key): string
    {
        $names = [
            'theme_color_primary' => 'Primary Color',
            'theme_color_secondary' => 'Secondary Color',
            'theme_color_natural' => 'Natural Color',
            'theme_footer_bg' => 'Footer Background',
            'theme_footer_text' => 'Footer Text',
            'theme_header_bg' => 'Header Background',
            'theme_header_text' => 'Header Text',
            'theme_font_sans' => 'Sans-serif Font',
            'theme_font_outfit' => 'Outfit Font',
            'theme_font_size_h1' => 'H1 Font Size',
            'theme_font_size_h2' => 'H2 Font Size',
            'theme_font_size_h3' => 'H3 Font Size',
            'theme_font_size_h4' => 'H4 Font Size',
            'theme_font_size_h5' => 'H5 Font Size',
            'theme_font_size_h6' => 'H6 Font Size',
            'theme_font_size_p' => 'Paragraph Font Size',
        ];

        return $names[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }

    /**
     * Get description based on key
     */
    private function getDescription(string $key): ?string
    {
        $descriptions = [
            'theme_color_primary' => 'The primary brand color used throughout the site',
            'theme_color_secondary' => 'The secondary accent color',
            'theme_color_natural' => 'The neutral/natural color for backgrounds and borders',
            'theme_footer_bg' => 'Background color for the footer area',
            'theme_footer_text' => 'Text color for the footer',
            'theme_header_bg' => 'Background color for the header area',
            'theme_header_text' => 'Text color for the header',
            'theme_font_sans' => 'The primary sans-serif font family',
            'theme_font_outfit' => 'The secondary font family for headings',
            'theme_font_size_h1' => 'Font size for H1 headings',
            'theme_font_size_h2' => 'Font size for H2 headings',
            'theme_font_size_h3' => 'Font size for H3 headings',
            'theme_font_size_h4' => 'Font size for H4 headings',
            'theme_font_size_h5' => 'Font size for H5 headings',
            'theme_font_size_h6' => 'Font size for H6 headings',
            'theme_font_size_p' => 'Font size for paragraphs',
        ];

        return $descriptions[$key] ?? null;
    }

    /**
     * Get order based on key
     */
    private function getOrder(string $key): int
    {
        $orders = [
            'theme_color_primary' => 1,
            'theme_color_secondary' => 2,
            'theme_color_natural' => 3,
            'theme_footer_bg' => 4,
            'theme_footer_text' => 5,
            'theme_header_bg' => 6,
            'theme_header_text' => 7,
            'theme_font_sans' => 8,
            'theme_font_outfit' => 9,
            'theme_font_size_h1' => 10,
            'theme_font_size_h2' => 11,
            'theme_font_size_h3' => 12,
            'theme_font_size_h4' => 13,
            'theme_font_size_h5' => 14,
            'theme_font_size_h6' => 15,
            'theme_font_size_p' => 16,
        ];

        return $orders[$key] ?? 0;
    }

    /**
     * Clear relevant caches
     */
    private function clearCaches(): void
    {
        Setting::forgetAggregateCache();
        $settingKeys = [
            'theme_color_primary',
            'theme_color_secondary',
            'theme_color_natural',
            'theme_footer_bg',
            'theme_footer_text',
            'theme_header_bg',
            'theme_header_text',
            'theme_font_sans',
            'theme_font_outfit',
            'theme_font_size_h1',
            'theme_font_size_h2',
            'theme_font_size_h3',
            'theme_font_size_h4',
            'theme_font_size_h5',
            'theme_font_size_h6',
            'theme_font_size_p',
        ];

        foreach ($settingKeys as $key) {
            Cache::forget("settings.{$key}");
        }
    }

    /**
     * Get Google Fonts list
     */
    private function getGoogleFonts(): array
    {
        return [
            'ABeeZee' => 'ABeeZee',
            'Abel' => 'Abel',
            'Abril Fatface' => 'Abril Fatface',
            'Aclonica' => 'Aclonica',
            'Acme' => 'Acme',
            'Actor' => 'Actor',
            'Adamina' => 'Adamina',
            'Advent Pro' => 'Advent Pro',
            'Aguafina Script' => 'Aguafina Script',
            'Akronim' => 'Akronim',
            'Aladin' => 'Aladin',
            'Aldrich' => 'Aldrich',
            'Alef' => 'Alef',
            'Alegreya' => 'Alegreya',
            'Alegreya Sans' => 'Alegreya Sans',
            'Alex Brush' => 'Alex Brush',
            'Alfa Slab One' => 'Alfa Slab One',
            'Alice' => 'Alice',
            'Alike' => 'Alike',
            'Allan' => 'Allan',
            'Allerta' => 'Allerta',
            'Allura' => 'Allura',
            'Almendra' => 'Almendra',
            'Amaranth' => 'Amaranth',
            'Amatic SC' => 'Amatic SC',
            'Amethysta' => 'Amethysta',
            'Amiri' => 'Amiri',
            'Anaheim' => 'Anaheim',
            'Andada' => 'Andada',
            'Andika' => 'Andika',
            'Angkor' => 'Angkor',
            'Annie Use Your Telescope' => 'Annie Use Your Telescope',
            'Anonymous Pro' => 'Anonymous Pro',
            'Antic' => 'Antic',
            'Anton' => 'Anton',
            'Arapey' => 'Arapey',
            'Arbutus' => 'Arbutus',
            'Architects Daughter' => 'Architects Daughter',
            'Archivo' => 'Archivo',
            'Archivo Black' => 'Archivo Black',
            'Archivo Narrow' => 'Archivo Narrow',
            'Arimo' => 'Arimo',
            'Arizonia' => 'Arizonia',
            'Armata' => 'Armata',
            'Arsenal' => 'Arsenal',
            'Artifika' => 'Artifika',
            'Arvo' => 'Arvo',
            'Asap' => 'Asap',
            'Asap Condensed' => 'Asap Condensed',
            'Assistant' => 'Assistant',
            'Astloch' => 'Astloch',
            'Asul' => 'Asul',
            'Atomic Age' => 'Atomic Age',
            'Aubrey' => 'Aubrey',
            'Audiowide' => 'Audiowide',
            'Average' => 'Average',
            'Averia Libre' => 'Averia Libre',
            'Averia Sans Libre' => 'Averia Sans Libre',
            'Averia Serif Libre' => 'Averia Serif Libre',
            'Bad Script' => 'Bad Script',
            'Balthazar' => 'Balthazar',
            'Bangers' => 'Bangers',
            'Basic' => 'Basic',
            'Battambang' => 'Battambang',
            'Baumans' => 'Baumans',
            'Bebas Neue' => 'Bebas Neue',
            'Belgrano' => 'Belgrano',
            'Belleza' => 'Belleza',
            'BenchNine' => 'BenchNine',
            'Bentham' => 'Bentham',
            'Berkshire Swash' => 'Berkshire Swash',
            'Bevan' => 'Bevan',
            'Bigelow Rules' => 'Bigelow Rules',
            'Bigshot One' => 'Bigshot One',
            'Bitter' => 'Bitter',
            'Black Ops One' => 'Black Ops One',
            'Bokor' => 'Bokor',
            'Bonbon' => 'Bonbon',
            'Boogaloo' => 'Boogaloo',
            'Bowlby One' => 'Bowlby One',
            'Bowlby One SC' => 'Bowlby One SC',
            'Brawler' => 'Brawler',
            'Bree Serif' => 'Bree Serif',
            'Bubblegum Sans' => 'Bubblegum Sans',
            'Bubbler One' => 'Bubbler One',
            'Buda' => 'Buda',
            'Buenard' => 'Buenard',
            'Butcherman' => 'Butcherman',
            'Butterfly Kids' => 'Butterfly Kids',
            'Cabin' => 'Cabin',
            'Cabin Condensed' => 'Cabin Condensed',
            'Cabin Sketch' => 'Cabin Sketch',
            'Caesar Dressing' => 'Caesar Dressing',
            'Cagliostro' => 'Cagliostro',
            'Cairo' => 'Cairo',
            'Calligraffitti' => 'Calligraffitti',
            'Cambay' => 'Cambay',
            'Cambo' => 'Cambo',
            'Candal' => 'Candal',
            'Cantarell' => 'Cantarell',
            'Cantata One' => 'Cantata One',
            'Cantora One' => 'Cantora One',
            'Capriola' => 'Capriola',
            'Cardo' => 'Cardo',
            'Carme' => 'Carme',
            'Carrois Gothic' => 'Carrois Gothic',
            'Carrois Gothic SC' => 'Carrois Gothic SC',
            'Carter One' => 'Carter One',
            'Catamaran' => 'Catamaran',
            'Caudex' => 'Caudex',
            'Caveat' => 'Caveat',
            'Cedarville Cursive' => 'Cedarville Cursive',
            'Ceviche One' => 'Ceviche One',
            'Changa' => 'Changa',
            'Changa One' => 'Changa One',
            'Chango' => 'Chango',
            'Chau Philomene One' => 'Chau Philomene One',
            'Chela One' => 'Chela One',
            'Chelsea Market' => 'Chelsea Market',
            'Chenla' => 'Chenla',
            'Cherry Cream Soda' => 'Cherry Cream Soda',
            'Cherry Swash' => 'Cherry Swash',
            'Chewy' => 'Chewy',
            'Chicle' => 'Chicle',
            'Chivo' => 'Chivo',
            'Cinzel' => 'Cinzel',
            'Cinzel Decorative' => 'Cinzel Decorative',
            'Clicker Script' => 'Clicker Script',
            'Coda' => 'Coda',
            'Coda Caption' => 'Coda Caption',
            'Codystar' => 'Codystar',
            'Combo' => 'Combo',
            'Comfortaa' => 'Comfortaa',
            'Coming Soon' => 'Coming Soon',
            'Concert One' => 'Concert One',
            'Condiment' => 'Condiment',
            'Content' => 'Content',
            'Contrail One' => 'Contrail One',
            'Convergence' => 'Convergence',
            'Cookie' => 'Cookie',
            'Copse' => 'Copse',
            'Corben' => 'Corben',
            'Cormorant' => 'Cormorant',
            'Cormorant Garamond' => 'Cormorant Garamond',
            'Courgette' => 'Courgette',
            'Cousine' => 'Cousine',
            'Coustard' => 'Coustard',
            'Covered By Your Grace' => 'Covered By Your Grace',
            'Crafty Girls' => 'Crafty Girls',
            'Creepster' => 'Creepster',
            'Crete Round' => 'Crete Round',
            'Crimson Text' => 'Crimson Text',
            'Croissant One' => 'Croissant One',
            'Crushed' => 'Crushed',
            'Cuprum' => 'Cuprum',
            'Cutive' => 'Cutive',
            'Cutive Mono' => 'Cutive Mono',
            'Damion' => 'Damion',
            'Dancing Script' => 'Dancing Script',
            'Dangrek' => 'Dangrek',
            'Dawning of a New Day' => 'Dawning of a New Day',
            'Days One' => 'Days One',
            'Dekko' => 'Dekko',
            'Delius' => 'Delius',
            'Delius Swash Caps' => 'Delius Swash Caps',
            'Delius Unicase' => 'Delius Unicase',
            'Della Respira' => 'Della Respira',
            'Denk One' => 'Denk One',
            'Devonshire' => 'Devonshire',
            'DM Sans' => 'DM Sans',
            'Didact Gothic' => 'Didact Gothic',
            'Diplomata' => 'Diplomata',
            'Diplomata SC' => 'Diplomata SC',
            'Domine' => 'Domine',
            'Donegal One' => 'Donegal One',
            'Doppio One' => 'Doppio One',
            'Dorsa' => 'Dorsa',
            'Dosis' => 'Dosis',
            'Dr Sugiyama' => 'Dr Sugiyama',
            'Droid Sans' => 'Droid Sans',
            'Droid Sans Mono' => 'Droid Sans Mono',
            'Droid Serif' => 'Droid Serif',
            'Duru Sans' => 'Duru Sans',
            'Dynalight' => 'Dynalight',
            'EB Garamond' => 'EB Garamond',
            'Eagle Lake' => 'Eagle Lake',
            'Eater' => 'Eater',
            'Economica' => 'Economica',
            'Eczar' => 'Eczar',
            'Electrolize' => 'Electrolize',
            'Elsie' => 'Elsie',
            'Elsie Swash Caps' => 'Elsie Swash Caps',
            'Emblema One' => 'Emblema One',
            'Emilys Candy' => 'Emilys Candy',
            'Encode Sans' => 'Encode Sans',
            'Encode Sans Condensed' => 'Encode Sans Condensed',
            'Engagement' => 'Engagement',
            'Englebert' => 'Englebert',
            'Enriqueta' => 'Enriqueta',
            'Epilogue' => 'Epilogue',
            'Erica One' => 'Erica One',
            'Esteban' => 'Esteban',
            'Euphoria Script' => 'Euphoria Script',
            'Ewert' => 'Ewert',
            'Exo' => 'Exo',
            'Exo 2' => 'Exo 2',
            'Expletus Sans' => 'Expletus Sans',
            'Fanwood Text' => 'Fanwood Text',
            'Fascinate' => 'Fascinate',
            'Fascinate Inline' => 'Fascinate Inline',
            'Faster One' => 'Faster One',
            'Fasthand' => 'Fasthand',
            'Fauna One' => 'Fauna One',
            'Federant' => 'Federant',
            'Federo' => 'Federo',
            'Felipa' => 'Felipa',
            'Fenix' => 'Fenix',
            'Finger Paint' => 'Finger Paint',
            'Fira Sans' => 'Fira Sans',
            'Fira Sans Condensed' => 'Fira Sans Condensed',
            'Fjalla One' => 'Fjalla One',
            'Fjord One' => 'Fjord One',
            'Flamenco' => 'Flamenco',
            'Flavors' => 'Flavors',
            'Fondamento' => 'Fondamento',
            'Fontdiner Swanky' => 'Fontdiner Swanky',
            'Forum' => 'Forum',
            'Francois One' => 'Francois One',
            'Frank Ruhl Libre' => 'Frank Ruhl Libre',
            'Freckle Face' => 'Freckle Face',
            'Fredericka the Great' => 'Fredericka the Great',
            'Fredoka One' => 'Fredoka One',
            'Freehand' => 'Freehand',
            'Fresca' => 'Fresca',
            'Frijole' => 'Frijole',
            'Fruktur' => 'Fruktur',
            'Fugaz One' => 'Fugaz One',
            'Inter' => 'Inter',
            'Josefin Sans' => 'Josefin Sans',
            'Josefin Slab' => 'Josefin Slab',
            'Jost' => 'Jost',
            'Jura' => 'Jura',
            'Karla' => 'Karla',
            'Lato' => 'Lato',
            'Lexend' => 'Lexend',
            'Libre Baskerville' => 'Libre Baskerville',
            'Libre Franklin' => 'Libre Franklin',
            'Lobster' => 'Lobster',
            'Lora' => 'Lora',
            'Manrope' => 'Manrope',
            'Merriweather' => 'Merriweather',
            'Montserrat' => 'Montserrat',
            'Mukta' => 'Mukta',
            'Mulish' => 'Mulish',
            'Noto Sans' => 'Noto Sans',
            'Noto Serif' => 'Noto Serif',
            'Nunito' => 'Nunito',
            'Nunito Sans' => 'Nunito Sans',
            'Open Sans' => 'Open Sans',
            'Oswald' => 'Oswald',
            'Outfit' => 'Outfit',
            'Oxygen' => 'Oxygen',
            'Pacifico' => 'Pacifico',
            'Playfair Display' => 'Playfair Display',
            'Plus Jakarta Sans' => 'Plus Jakarta Sans',
            'Poppins' => 'Poppins',
            'PT Sans' => 'PT Sans',
            'PT Serif' => 'PT Serif',
            'Quicksand' => 'Quicksand',
            'Raleway' => 'Raleway',
            'Red Hat Display' => 'Red Hat Display',
            'Roboto' => 'Roboto',
            'Roboto Condensed' => 'Roboto Condensed',
            'Roboto Mono' => 'Roboto Mono',
            'Roboto Slab' => 'Roboto Slab',
            'Rubik' => 'Rubik',
            'Source Code Pro' => 'Source Code Pro',
            'Source Sans Pro' => 'Source Sans Pro',
            'Source Serif Pro' => 'Source Serif Pro',
            'Space Grotesk' => 'Space Grotesk',
            'Space Mono' => 'Space Mono',
            'Spectral' => 'Spectral',
            'Sora' => 'Sora',
            'Titillium Web' => 'Titillium Web',
            'Ubuntu' => 'Ubuntu',
            'Ubuntu Condensed' => 'Ubuntu Condensed',
            'Ubuntu Mono' => 'Ubuntu Mono',
            'Varela Round' => 'Varela Round',
            'Work Sans' => 'Work Sans',
            'Yanone Kaffeesatz' => 'Yanone Kaffeesatz',
            'Zilla Slab' => 'Zilla Slab',
        ];
    }

    /**
     * Get filtered fonts based on search term
     */
    private function getFilteredFonts(string $searchTerm): array
    {
        $allFonts = $this->getGoogleFonts();

        if (empty($searchTerm)) {
            return $allFonts;
        }

        $searchLower = strtolower($searchTerm);
        $filtered = [];

        foreach ($allFonts as $value => $label) {
            if (str_contains(strtolower($label), $searchLower)) {
                $filtered[$value] = $label;
            }
        }

        return $filtered;
    }

    public function render()
    {
        $googleFonts = $this->getGoogleFonts();
        $filteredFontsSans = $this->getFilteredFonts($this->fontSearchSans);
        $filteredFontsOutfit = $this->getFilteredFonts($this->fontSearchOutfit);

        return view('livewire.admin.theme-settings', [
            'googleFonts' => $googleFonts,
            'filteredFontsSans' => $filteredFontsSans,
            'filteredFontsOutfit' => $filteredFontsOutfit,
        ]);
    }
}
