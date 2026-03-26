<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ThemeSettingController extends AdminBaseController
{
    /**
     * Display the theme settings page.
     */
    public function index()
    {
        return view('admin.settings.theme.index');
    }

    /**
     * Update theme settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'theme_color_primary' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'theme_color_secondary' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'theme_color_natural' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'theme_font_sans' => 'required|string|max:255',
            'theme_font_outfit' => 'required|string|max:255',
            'theme_font_size_h1' => 'required|integer|min:1|max:200',
            'theme_font_size_h2' => 'required|integer|min:1|max:200',
            'theme_font_size_h3' => 'required|integer|min:1|max:200',
            'theme_font_size_h4' => 'required|integer|min:1|max:200',
            'theme_font_size_h5' => 'required|integer|min:1|max:200',
            'theme_font_size_h6' => 'required|integer|min:1|max:200',
            'theme_font_size_p' => 'required|integer|min:1|max:200',
        ], [
            'theme_color_primary.regex' => 'Primary color must be a valid hex color code (e.g., #081245)',
            'theme_color_secondary.regex' => 'Secondary color must be a valid hex color code (e.g., #0073e6)',
            'theme_color_natural.regex' => 'Natural color must be a valid hex color code (e.g., #dfd4d4)',
            'theme_font_size_h1.integer' => 'Font size must be a whole number',
            'theme_font_size_h2.integer' => 'Font size must be a whole number',
            'theme_font_size_h3.integer' => 'Font size must be a whole number',
            'theme_font_size_h4.integer' => 'Font size must be a whole number',
            'theme_font_size_h5.integer' => 'Font size must be a whole number',
            'theme_font_size_h6.integer' => 'Font size must be a whole number',
            'theme_font_size_p.integer' => 'Font size must be a whole number',
        ]);

        try {
            // Update theme settings
            $this->updateSetting('theme_color_primary', $request->theme_color_primary);
            $this->updateSetting('theme_color_secondary', $request->theme_color_secondary);
            $this->updateSetting('theme_color_natural', $request->theme_color_natural);
            $this->updateSetting('theme_font_sans', $request->theme_font_sans);
            $this->updateSetting('theme_font_outfit', $request->theme_font_outfit);
            $this->updateSetting('theme_font_size_h1', $request->theme_font_size_h1.'rem');
            $this->updateSetting('theme_font_size_h2', $request->theme_font_size_h2.'rem');
            $this->updateSetting('theme_font_size_h3', $request->theme_font_size_h3.'rem');
            $this->updateSetting('theme_font_size_h4', $request->theme_font_size_h4.'rem');
            $this->updateSetting('theme_font_size_h5', $request->theme_font_size_h5.'rem');
            $this->updateSetting('theme_font_size_h6', $request->theme_font_size_h6.'rem');
            $this->updateSetting('theme_font_size_p', $request->theme_font_size_p.'rem');

            // Clear relevant caches
            $this->clearCaches();

            // Log activity
            $this->logSettingsUpdate('Theme Settings');

            return redirect()->back()->with('status', 'theme-updated');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'An error occurred while updating theme settings: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Update a single setting.
     */
    private function updateSetting(string $key, $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $key === 'theme_font_sans' || $key === 'theme_font_outfit' ? 'text' : (str_starts_with($key, 'theme_font_size_') ? 'text' : 'color'),
                'group' => 'theme',
                'display_name' => $this->getDisplayName($key),
                'description' => $this->getDescription($key),
                'order' => $this->getOrder($key),
            ]
        );
    }

    /**
     * Get display name based on key.
     */
    private function getDisplayName(string $key): string
    {
        $names = [
            'theme_color_primary' => 'Primary Color',
            'theme_color_secondary' => 'Secondary Color',
            'theme_color_natural' => 'Natural Color',
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
     * Get description based on key.
     */
    private function getDescription(string $key): ?string
    {
        $descriptions = [
            'theme_color_primary' => 'The primary brand color used throughout the site',
            'theme_color_secondary' => 'The secondary accent color',
            'theme_color_natural' => 'The neutral/natural color for backgrounds and borders',
            'theme_font_sans' => 'The primary sans-serif font family',
            'theme_font_outfit' => 'The secondary font family for headings',
            'theme_font_size_h1' => 'Font size for H1 headings (e.g., 2.25rem)',
            'theme_font_size_h2' => 'Font size for H2 headings (e.g., 1.875rem)',
            'theme_font_size_h3' => 'Font size for H3 headings (e.g., 1.5rem)',
            'theme_font_size_h4' => 'Font size for H4 headings (e.g., 1.25rem)',
            'theme_font_size_h5' => 'Font size for H5 headings (e.g., 1.125rem)',
            'theme_font_size_h6' => 'Font size for H6 headings (e.g., 1rem)',
            'theme_font_size_p' => 'Font size for paragraphs (e.g., 1rem)',
        ];

        return $descriptions[$key] ?? null;
    }

    /**
     * Get order based on key.
     */
    private function getOrder(string $key): int
    {
        $orders = [
            'theme_color_primary' => 1,
            'theme_color_secondary' => 2,
            'theme_color_natural' => 3,
            'theme_font_sans' => 4,
            'theme_font_outfit' => 5,
            'theme_font_size_h1' => 6,
            'theme_font_size_h2' => 7,
            'theme_font_size_h3' => 8,
            'theme_font_size_h4' => 9,
            'theme_font_size_h5' => 10,
            'theme_font_size_h6' => 11,
            'theme_font_size_p' => 12,
        ];

        return $orders[$key] ?? 0;
    }

    /**
     * Clear relevant caches after updating theme settings.
     */
    private function clearCaches(): void
    {
        Setting::forgetAggregateCache();

        $settingKeys = [
            'theme_color_primary',
            'theme_color_secondary',
            'theme_color_natural',
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
     * Get comprehensive Google Fonts list (~200 fonts).
     */
    private function getGoogleFonts(): array
    {
        return [
            'ABeeZee' => 'ABeeZee',
            'Abel' => 'Abel',
            'Abril+Fatface' => 'Abril Fatface',
            'Aclonica' => 'Aclonica',
            'Acme' => 'Acme',
            'Actor' => 'Actor',
            'Adamina' => 'Adamina',
            'Advent+Pro' => 'Advent Pro',
            'Aguafina+Script' => 'Aguafina Script',
            'Akronim' => 'Akronim',
            'Aladin' => 'Aladin',
            'Aldrich' => 'Aldrich',
            'Alef' => 'Alef',
            'Alegreya' => 'Alegreya',
            'Alegreya+Sans' => 'Alegreya Sans',
            'Alex+Brush' => 'Alex Brush',
            'Alfa+Slab+One' => 'Alfa Slab One',
            'Alice' => 'Alice',
            'Alike' => 'Alike',
            'Allan' => 'Allan',
            'Allerta' => 'Allerta',
            'Allura' => 'Allura',
            'Almendra' => 'Almendra',
            'Amaranth' => 'Amaranth',
            'Amatic+SC' => 'Amatic SC',
            'Amethysta' => 'Amethysta',
            'Amiri' => 'Amiri',
            'Anaheim' => 'Anaheim',
            'Andada' => 'Andada',
            'Andika' => 'Andika',
            'Angkor' => 'Angkor',
            'Annie+Use+Your+Telescope' => 'Annie Use Your Telescope',
            'Anonymous+Pro' => 'Anonymous Pro',
            'Antic' => 'Antic',
            'Anton' => 'Anton',
            'Arapey' => 'Arapey',
            'Arbutus' => 'Arbutus',
            'Architects+Daughter' => 'Architects Daughter',
            'Archivo' => 'Archivo',
            'Archivo+Black' => 'Archivo Black',
            'Archivo+Narrow' => 'Archivo Narrow',
            'Arimo' => 'Arimo',
            'Arizonia' => 'Arizonia',
            'Armata' => 'Armata',
            'Arsenal' => 'Arsenal',
            'Artifika' => 'Artifika',
            'Arvo' => 'Arvo',
            'Asap' => 'Asap',
            'Asap+Condensed' => 'Asap Condensed',
            'Assistant' => 'Assistant',
            'Astloch' => 'Astloch',
            'Asul' => 'Asul',
            'Atomic+Age' => 'Atomic Age',
            'Aubrey' => 'Aubrey',
            'Audiowide' => 'Audiowide',
            'Average' => 'Average',
            'Averia+Libre' => 'Averia Libre',
            'Averia+Sans+Libre' => 'Averia Sans Libre',
            'Averia+Serif+Libre' => 'Averia Serif Libre',
            'Bad+Script' => 'Bad Script',
            'Balthazar' => 'Balthazar',
            'Bangers' => 'Bangers',
            'Basic' => 'Basic',
            'Battambang' => 'Battambang',
            'Baumans' => 'Baumans',
            'Bebas+Neue' => 'Bebas Neue',
            'Belgrano' => 'Belgrano',
            'Belleza' => 'Belleza',
            'BenchNine' => 'BenchNine',
            'Bentham' => 'Bentham',
            'Berkshire+Swash' => 'Berkshire Swash',
            'Bevan' => 'Bevan',
            'Bigelow+Rules' => 'Bigelow Rules',
            'Bigshot+One' => 'Bigshot One',
            'Bitter' => 'Bitter',
            'Black+Ops+One' => 'Black Ops One',
            'Bokor' => 'Bokor',
            'Bonbon' => 'Bonbon',
            'Boogaloo' => 'Boogaloo',
            'Bowlby+One' => 'Bowlby One',
            'Bowlby+One+SC' => 'Bowlby One SC',
            'Brawler' => 'Brawler',
            'Bree+Serif' => 'Bree Serif',
            'Bubblegum+Sans' => 'Bubblegum Sans',
            'Bubbler+One' => 'Bubbler One',
            'Buda' => 'Buda',
            'Buenard' => 'Buenard',
            'Butcherman' => 'Butcherman',
            'Butterfly+Kids' => 'Butterfly Kids',
            'Cabin' => 'Cabin',
            'Cabin+Condensed' => 'Cabin Condensed',
            'Cabin+Sketch' => 'Cabin Sketch',
            'Caesar+Dressing' => 'Caesar Dressing',
            'Cagliostro' => 'Cagliostro',
            'Cairo' => 'Cairo',
            'Calligraffitti' => 'Calligraffitti',
            'Cambay' => 'Cambay',
            'Cambo' => 'Cambo',
            'Candal' => 'Candal',
            'Cantarell' => 'Cantarell',
            'Cantata+One' => 'Cantata One',
            'Cantora+One' => 'Cantora One',
            'Capriola' => 'Capriola',
            'Cardo' => 'Cardo',
            'Carme' => 'Carme',
            'Carrois+Gothic' => 'Carrois Gothic',
            'Carrois+Gothic+SC' => 'Carrois Gothic SC',
            'Carter+One' => 'Carter One',
            'Catamaran' => 'Catamaran',
            'Caudex' => 'Caudex',
            'Caveat' => 'Caveat',
            'Cedarville+Cursive' => 'Cedarville Cursive',
            'Ceviche+One' => 'Ceviche One',
            'Changa' => 'Changa',
            'Changa+One' => 'Changa One',
            'Chango' => 'Chango',
            'Chau+Philomene+One' => 'Chau Philomene One',
            'Chela+One' => 'Chela One',
            'Chelsea+Market' => 'Chelsea Market',
            'Chenla' => 'Chenla',
            'Cherry+Cream+Soda' => 'Cherry Cream Soda',
            'Cherry+Swash' => 'Cherry Swash',
            'Chewy' => 'Chewy',
            'Chicle' => 'Chicle',
            'Chivo' => 'Chivo',
            'Cinzel' => 'Cinzel',
            'Cinzel+Decorative' => 'Cinzel Decorative',
            'Clicker+Script' => 'Clicker Script',
            'Coda' => 'Coda',
            'Coda+Caption' => 'Coda Caption',
            'Codystar' => 'Codystar',
            'Combo' => 'Combo',
            'Comfortaa' => 'Comfortaa',
            'Coming+Soon' => 'Coming Soon',
            'Concert+One' => 'Concert One',
            'Condiment' => 'Condiment',
            'Content' => 'Content',
            'Contrail+One' => 'Contrail One',
            'Convergence' => 'Convergence',
            'Cookie' => 'Cookie',
            'Copse' => 'Copse',
            'Corben' => 'Corben',
            'Cormorant' => 'Cormorant',
            'Cormorant+Garamond' => 'Cormorant Garamond',
            'Courgette' => 'Courgette',
            'Cousine' => 'Cousine',
            'Coustard' => 'Coustard',
            'Covered+By+Your+Grace' => 'Covered By Your Grace',
            'Crafty+Girls' => 'Crafty Girls',
            'Creepster' => 'Creepster',
            'Crete+Round' => 'Crete Round',
            'Crimson+Text' => 'Crimson Text',
            'Croissant+One' => 'Croissant One',
            'Crushed' => 'Crushed',
            'Cuprum' => 'Cuprum',
            'Cutive' => 'Cutive',
            'Cutive+Mono' => 'Cutive Mono',
            'Damion' => 'Damion',
            'Dancing+Script' => 'Dancing Script',
            'Dangrek' => 'Dangrek',
            'Dawning+of+a+New+Day' => 'Dawning of a New Day',
            'Days+One' => 'Days One',
            'Dekko' => 'Dekko',
            'Delius' => 'Delius',
            'Delius+Swash+Caps' => 'Delius Swash Caps',
            'Delius+Unicase' => 'Delius Unicase',
            'Della+Respira' => 'Della Respira',
            'Denk+One' => 'Denk One',
            'Devonshire' => 'Devonshire',
            'DM+Sans' => 'DM Sans',
            'Didact+Gothic' => 'Didact Gothic',
            'Diplomata' => 'Diplomata',
            'Diplomata+SC' => 'Diplomata SC',
            'Domine' => 'Domine',
            'Donegal+One' => 'Donegal One',
            'Doppio+One' => 'Doppio One',
            'Dorsa' => 'Dorsa',
            'Dosis' => 'Dosis',
            'Dr+Sugiyama' => 'Dr Sugiyama',
            'Droid+Sans' => 'Droid Sans',
            'Droid+Sans+Mono' => 'Droid Sans Mono',
            'Droid+Serif' => 'Droid Serif',
            'Duru+Sans' => 'Duru Sans',
            'Dynalight' => 'Dynalight',
            'EB+Garamond' => 'EB Garamond',
            'Eagle+Lake' => 'Eagle Lake',
            'Eater' => 'Eater',
            'Economica' => 'Economica',
            'Eczar' => 'Eczar',
            'Electrolize' => 'Electrolize',
            'Elsie' => 'Elsie',
            'Elsie+Swash+Caps' => 'Elsie Swash Caps',
            'Emblema+One' => 'Emblema One',
            'Emilys+Candy' => 'Emilys Candy',
            'Encode+Sans' => 'Encode Sans',
            'Encode+Sans+Condensed' => 'Encode Sans Condensed',
            'Engagement' => 'Engagement',
            'Englebert' => 'Englebert',
            'Enriqueta' => 'Enriqueta',
            'Epilogue' => 'Epilogue',
            'Erica+One' => 'Erica One',
            'Esteban' => 'Esteban',
            'Euphoria+Script' => 'Euphoria Script',
            'Ewert' => 'Ewert',
            'Exo' => 'Exo',
            'Exo+2' => 'Exo 2',
            'Expletus+Sans' => 'Expletus Sans',
            'Fanwood+Text' => 'Fanwood Text',
            'Fascinate' => 'Fascinate',
            'Fascinate+Inline' => 'Fascinate Inline',
            'Faster+One' => 'Faster One',
            'Fasthand' => 'Fasthand',
            'Fauna+One' => 'Fauna One',
            'Federant' => 'Federant',
            'Federo' => 'Federo',
            'Felipa' => 'Felipa',
            'Fenix' => 'Fenix',
            'Finger+Paint' => 'Finger Paint',
            'Fira+Sans' => 'Fira Sans',
            'Fira+Sans+Condensed' => 'Fira Sans Condensed',
            'Fjalla+One' => 'Fjalla One',
            'Fjord+One' => 'Fjord One',
            'Flamenco' => 'Flamenco',
            'Flavors' => 'Flavors',
            'Fondamento' => 'Fondamento',
            'Fontdiner+Swanky' => 'Fontdiner Swanky',
            'Forum' => 'Forum',
            'Francois+One' => 'Francois One',
            'Frank+Ruhl+Libre' => 'Frank Ruhl Libre',
            'Freckle+Face' => 'Freckle Face',
            'Fredericka+the+Great' => 'Fredericka the Great',
            'Fredoka+One' => 'Fredoka One',
            'Freehand' => 'Freehand',
            'Fresca' => 'Fresca',
            'Frijole' => 'Frijole',
            'Fruktur' => 'Fruktur',
            'Fugaz+One' => 'Fugaz One',
            'Inter' => 'Inter',
            'Josefin+Sans' => 'Josefin Sans',
            'Josefin+Slab' => 'Josefin Slab',
            'Jost' => 'Jost',
            'Jura' => 'Jura',
            'Karla' => 'Karla',
            'Lato' => 'Lato',
            'Lexend' => 'Lexend',
            'Libre+Baskerville' => 'Libre Baskerville',
            'Libre+Franklin' => 'Libre Franklin',
            'Lobster' => 'Lobster',
            'Lora' => 'Lora',
            'Manrope' => 'Manrope',
            'Merriweather' => 'Merriweather',
            'Montserrat' => 'Montserrat',
            'Mukta' => 'Mukta',
            'Mulish' => 'Mulish',
            'Noto+Sans' => 'Noto Sans',
            'Noto+Serif' => 'Noto Serif',
            'Nunito' => 'Nunito',
            'Nunito+Sans' => 'Nunito Sans',
            'Open+Sans' => 'Open Sans',
            'Oswald' => 'Oswald',
            'Outfit' => 'Outfit',
            'Oxygen' => 'Oxygen',
            'Pacifico' => 'Pacifico',
            'Playfair+Display' => 'Playfair Display',
            'Plus+Jakarta+Sans' => 'Plus Jakarta Sans',
            'Poppins' => 'Poppins',
            'PT+Sans' => 'PT Sans',
            'PT+Serif' => 'PT Serif',
            'Quicksand' => 'Quicksand',
            'Raleway' => 'Raleway',
            'Red+Hat+Display' => 'Red Hat Display',
            'Roboto' => 'Roboto',
            'Roboto+Condensed' => 'Roboto Condensed',
            'Roboto+Mono' => 'Roboto Mono',
            'Roboto+Slab' => 'Roboto Slab',
            'Rubik' => 'Rubik',
            'Source+Code+Pro' => 'Source Code Pro',
            'Source+Sans+Pro' => 'Source Sans Pro',
            'Source+Serif+Pro' => 'Source Serif Pro',
            'Space+Grotesk' => 'Space Grotesk',
            'Space+Mono' => 'Space Mono',
            'Spectral' => 'Spectral',
            'Sora' => 'Sora',
            'Titillium+Web' => 'Titillium Web',
            'Ubuntu' => 'Ubuntu',
            'Ubuntu+Condensed' => 'Ubuntu Condensed',
            'Ubuntu+Mono' => 'Ubuntu Mono',
            'Varela+Round' => 'Varela Round',
            'Work+Sans' => 'Work Sans',
            'Yanone+Kaffeesatz' => 'Yanone Kaffeesatz',
            'Zilla+Slab' => 'Zilla Slab',
        ];
    }
}
