<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;

class BlogContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create realistic Dutch Users/Authors if none exist
        $author = User::firstOrCreate([
            'email' => 'author@opencontext.nl',
        ], [
            'name' => 'Willem',
            'last_name' => 'de Schrijver',
            'password' => bcrypt('password'),
        ]);

        // Assign admin role if it exists (using string check to avoid error if Role model issue)
        try {
            if (!$author->hasRole('admin')) {
                $author->assignRole('admin');
            }
        } catch (\Exception $e) {
            // Role might not exist, ignore for seeding content
        }

        $commenterUser = User::firstOrCreate([
            'email' => 'lezer@opencontext.nl',
        ], [
            'name' => 'Trouwe',
            'last_name' => 'Lezer',
            'password' => bcrypt('password'),
        ]);

        // 2. Create 5 Realistic Blog Categories
        $categories = [
            ['name' => 'Technologie & Innovatie', 'color' => '#3b82f6'],
            ['name' => 'Ondernemen', 'color' => '#10b981'],
            ['name' => 'Marketing Strategieën', 'color' => '#f59e0b'],
            ['name' => 'Persoonlijke Ontwikkeling', 'color' => '#8b5cf6'],
            ['name' => 'Duurzaamheid', 'color' => '#ec4899'],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $c = BlogCategory::firstOrCreate(
                ['name' => $cat['name']],
                ['is_active' => true, 'color' => $cat['color']]
            );
            $categoryIds[] = $c->id;
        }

        // 3. Create 10 High-Quality Realistic Blog Posts in Dutch
        $blogPosts = [
            [
                'title' => 'De Toekomst van AI in het Nederlandse Bedrijfsleven',
                'short_body' => 'Kunstmatige intelligentie (AI) verandert de manier waarop we werken. Wat betekent dit voor het MKB in Nederland?',
                'long_body' => '<p>Kunstmatige intelligentie is niet langer toekomstmuziek. Van geautomatiseerde klantenservice tot voorspellend onderhoud in fabrieken, AI is overal. Maar hoe kunnen Nederlandse MKB-bedrijven hiervan profiteren zonder enorme investeringen?</p>
                <h3>De impact op efficiëntie</h3>
                <p>Veel repetitieve taken kunnen worden overgenomen door slimme algoritmes. Dit betekent niet dat banen verdwijnen, maar dat ze veranderen. Werknemers kunnen zich focussen op creativiteit en strategie.</p>
                <h3>Uitdagingen en ethiek</h3>
                <p>Natuurlijk zijn er ook zorgen. Hoe zit het met privacy? En wat als een algoritme bevooroordeeld is? Het is cruciaal dat bedrijven transparant zijn over hun gebruik van AI.</p>
                <p>Concluderend kunnen we stellen dat de integratie van AI onvermijdelijk is. De vraag is niet of je meedoet, maar hoe snel.</p>',
            ],
            [
                'title' => '5 Tips voor Effectief Leiderschap op Afstand',
                'short_body' => 'Hybride werken is de norm geworden. Hoe stuur je een team aan dat je niet dagelijks ziet?',
                'long_body' => '<p>Sinds de pandemie is de kantoortuin niet meer de enige plek waar gewerkt wordt. Thuiswerken biedt flexibiliteit, maar vraagt ook om een andere stijl van leidinggeven.</p>
                <ul>
                    <li><strong>Vertrouwen boven controle:</strong> Micro-management werkt niet op afstand. Geef je team de ruimte.</li>
                    <li><strong>Duidelijke communicatie:</strong> Zorg voor heldere afspraken over bereikbaarheid en deadlines.</li>
                    <li><strong>Online teambuilding:</strong> Vergeet het sociale aspect niet. Plan virtuele koffiemomentjes in.</li>
                </ul>
                <p>Leiderschap gaat uiteindelijk over mensen, ongeacht waar ze zich bevinden.</p>',
            ],
            [
                'title' => 'Duurzaam Ondernemen: Meer dan een Trend',
                'short_body' => 'Waarom groene keuzes niet alleen goed zijn voor de planeet, maar ook voor je winstgevendheid.',
                'long_body' => '<p>Klanten kiezen steeds vaker voor bedrijven die hun verantwoordelijkheid nemen. Duurzaamheid is geen marketingtruc meer, het is een bestaansvoorwaarde.</p>
                <h3>Circulaire economie</h3>
                <p>In plaats van "maken, gebruiken, weggooien", moeten we toe naar systemen waarin grondstoffen worden hergebruikt. Dit bespaart kosten en vermindert de ecologische voetafdruk.</p>
                <p>Begin klein: kijk naar je energieverbruik, je leveranciers en je afvalstromen. Elke stap telt.</p>',
            ],
            [
                'title' => 'De Kracht van Storytelling in Marketing',
                'short_body' => 'Mensen kopen geen producten, ze kopen verhalen. Hoe vertel jij jouw bedrijfsverhaal?',
                'long_body' => '<p>In een wereld vol ruis is een goed verhaal de manier om op te vallen. Storytelling raakt mensen emotioneel, en emotie drijft aankoopbeslissingen.</p>
                <p>Denk aan merken als Nike of Apple. Ze verkopen niet alleen schoenen of telefoons; ze verkopen een levensstijl, een ambitie.</p>
                <h3>Jouw verhaal vinden</h3>
                <p>Wat is de "waarom" achter je bedrijf? Waar kom je vandaan en waar wil je naartoe? Wees authentiek en eerlijk, dat waarderen klanten het meest.</p>',
            ],
            [
                'title' => 'Investeren in Personeel: Opleiding en Groei',
                'short_body' => 'Waarom het opleidingsbudget het belangrijkste potje op je begroting is.',
                'long_body' => '<p>Goede mensen zijn schaars. Als je ze eenmaal binnen hebt, wil je ze behouden. Het bieden van groeimogelijkheden is daarvoor essentieel.</p>
                <p>Stilstand is achteruitgang. De wereld verandert snel, dus je medewerkers moeten meebewegen. Investeer in cursussen, workshops en coaching.</p>
                <p>Een medewerker die groeit, laat je bedrijf groeien.</p>',
            ],
            [
                'title' => 'SEO Trends voor 2026: Wat Werkt Nog?',
                'short_body' => 'Zoekmachineoptimalisatie verandert continu. Blijf je concurrentie voor met deze inzichten.',
                'long_body' => '<p>Met de opkomst van AI-zoekmachines verandert het SEO-landschap drastisch. Keywords zijn minder belangrijk, intentie en kwaliteit des te meer.</p>
                <h3>Zero-click searches</h3>
                <p>Steeds vaker krijgen gebruikers direct antwoord in de zoekresultaten. Hoe zorg je dat jouw merk toch zichtbaar blijft?</p>
                <p>Focus op expertise, autoriteit en betrouwbaarheid (E-E-A-T). Google beloont inhoud die echt waarde toevoegt.</p>',
            ],
            [
                'title' => 'De Psychologie van Kleur in Design',
                'short_body' => 'Hoe kleuren het onderbewuste van je klanten beïnvloeden.',
                'long_body' => '<p>Rood staat voor passie en urgentie, blauw voor vertrouwen en rust. Maar wist je dat cultuur ook een grote rol speelt in kleurbeleving?</p>
                <p>Bij het ontwerpen van een website of logo is kleurkeuze cruciaal. Het kan het verschil maken tussen een bezoeker die direct wegklikt en iemand die klant wordt.</p>
                <p>Test verschillende kleurenschema\'s en kijk wat resoneert met jouw doelgroep.</p>',
            ],
            [
                'title' => 'Blockchain: Voorbij de Hype van Crypto',
                'short_body' => 'De technologie achter Bitcoin heeft veel meer toepassingen dan alleen digitaal geld.',
                'long_body' => '<p>Blockchain is in essentie een digitaal logboek dat niet gewijzigd kan worden. Dit biedt enorme kansen voor supply chains, contracten en identiteitsbeheer.</p>
                <p>Stel je voor dat je precies kunt traceren waar je koffiebonen vandaan komen, zonder kans op fraude. Of dat je een huis koopt zonder tussenkomst van een notaris.</p>
                <p>De technologie is complex, maar de mogelijkheden zijn eindeloos.</p>',
            ],
            [
                'title' => 'Burn-out Preventie op de Werkvloer',
                'short_body' => 'Hoe herken je de signalen op tijd en creëer je een gezonde werkcultuur?',
                'long_body' => '<p>Het aantal werknemers met burn-out klachten stijgt. Dit kost bedrijven niet alleen geld, maar ook waardevolle kennis.</p>
                <h3>Signalen herkennen</h3>
                <p>Cynisme, vermoeidheid en verminderde effectiviteit zijn rode vlaggen. Als werkgever moet je alert zijn en het gesprek aangaan.</p>
                <p>Zorg voor een cultuur waarin kwetsbaarheid mag. Het is oké om even niet oké te zijn.</p>',
            ],
            [
                'title' => 'De Opkomst van E-Sports',
                'short_body' => 'Gaming is big business. Wat kunnen marketeers hiervan leren?',
                'long_body' => '<p>Stadions vol gillende fans die kijken naar mensen die videogames spelen. Voor sommigen onbegrijpelijk, voor miljoenen de normaalste zaak van de wereld.</p>
                <p>E-Sports biedt een unieke manier om een jonge doelgroep te bereiken die nauwelijks nog televisie kijkt. Sponsoring en partnerships in deze sector groeien explosief.</p>
                <p>Het gaat om community building en engagement. Iets waar elk bedrijf van kan leren.</p>',
            ],
        ];

        // 4. Create Comments Pool (Realistic Dutch comments)
        $commentsPool = [
            "Geweldig artikel! Dit zet me echt aan het denken over onze huidige strategie.",
            "Ik ben het niet helemaal eens met het punt over efficiëntie, maar verder een sterke analyse.",
            "Bedankt voor het delen, erg waardevol.",
            "Heb je hier ook bronnen voor? Ik zou me er graag verder in verdiepen.",
            "Dit is precies waar ik vorige week met mijn team over sprak. Zeer herkenbaar.",
            "Kort en krachtig, precies wat ik nodig had.",
            "Ik kijk uit naar het vervolg op dit stuk!",
            "Interessante visie, al denk ik dat de praktijk vaak weerbarstiger is.",
            "Zou je hier een case study van kunnen delen?",
            "Top content zoals altijd, ga zo door!",
            "Eindelijk iemand die het durft te zeggen. Helemaal mee eens.",
            "Ik heb dit artikel direct doorgestuurd naar mijn manager.",
            "Wat een frisse blik op dit onderwerp.",
            "Ik mis wel een stukje over de kostenimplementatie, misschien iets voor een volgende keer?",
            "Zeer inspirerend verhaal.",
        ];

        $namesPool = ['Sanne Janssen', 'Mark de Vries', 'Lisa Bakker', 'Tom Visser', 'Emma Smit', 'Daan Meijer', 'Sophie van der Berg', 'Lucas Bos'];

        foreach ($blogPosts as $index => $postData) {
            $blog = Blog::create([
                'blog_category_id' => $categoryIds[$index % count($categoryIds)],
                'author_id' => $author->id,
                'title' => $postData['title'],
                'short_body' => $postData['short_body'],
                'long_body' => $postData['long_body'],
                'is_active' => true,
                'is_featured' => $index < 3, // First 3 featured
                'image' => 'placeholder.jpg', // Assuming a placeholder handling via trait or similar
                'seo_score' => rand(50, 100),
            ]);

            // Create 8-10 comments for each blog
            $numComments = rand(8, 10);
            for ($i = 0; $i < $numComments; $i++) {
                $isGuest = rand(0, 1);
                $body = $commentsPool[array_rand($commentsPool)];

                // Add some variation to body to avoid unique constraint if any (though unlikely on body)
                if (rand(0, 1)) {
                    $body .= " " . ["Groetjes!", "Fijne dag!", "Gr,", "Met vriendelijke groet,"][rand(0, 3)];
                }

                $comment = $blog->comments()->create([
                    'user_id' => $isGuest ? null : $commenterUser->id,
                    'guest_name' => $isGuest ? $namesPool[array_rand($namesPool)] : null,
                    'guest_email' => $isGuest ? strtolower(str_replace(' ', '.', $namesPool[array_rand($namesPool)])) . '@example.com' : null,
                    'body' => $body,
                    'is_approved' => true,
                    'likes' => rand(0, 50),
                    'dislikes' => rand(0, 5),
                    'created_at' => Carbon::now()->subHours(rand(1, 100)),
                ]);

                // Randomly add a reply to some comments
                if (rand(0, 3) === 0) { // 25% chance of reply
                    $blog->comments()->create([
                        'parent_id' => $comment->id,
                        'user_id' => $author->id, // Author replies
                        'body' => "Bedankt voor je reactie! Goed punt.",
                        'is_approved' => true,
                        'likes' => rand(0, 10),
                        'created_at' => Carbon::now()->subHours(rand(1, 50)),
                    ]);
                }
            }
        }
    }
}
