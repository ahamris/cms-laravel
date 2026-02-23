<?php

namespace Database\Seeders;

use App\Models\DocPage;
use App\Models\DocSection;
use App\Models\DocVersion;
use Illuminate\Database\Seeder;

class DocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates 2 doc versions with sections and 5–6 documentation pages in total.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        if (DocVersion::count() > 0) {
            return;
        }

        $versions = [
            [
                'version' => '1.0',
                'name' => 'Documentation v1.0',
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 0,
            ],
            [
                'version' => '2.0',
                'name' => 'Documentation v2.0',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 1,
            ],
        ];

        foreach ($versions as $versionData) {
            $version = DocVersion::create($versionData);

            // Version 1.0: 3 pages (Intro, Installation, Authentication). Version 2.0: 3 pages (Intro, Authentication, Pagination). Total 6.
            $sections = [
                [
                    'title' => 'Getting started',
                    'slug' => 'getting-started',
                    'description' => 'Introduction and setup guide.',
                    'sort_order' => 0,
                    'pages' => $version->version === '1.0'
                        ? [
                            ['title' => 'Introduction', 'content' => $this->introContent(), 'sort_order' => 0],
                            ['title' => 'Installation', 'content' => $this->installationContent(), 'sort_order' => 1],
                        ]
                        : [
                            ['title' => 'Introduction', 'content' => $this->introContent(), 'sort_order' => 0],
                        ],
                ],
                [
                    'title' => 'API Reference',
                    'slug' => 'api-reference',
                    'description' => 'API endpoints and usage.',
                    'sort_order' => 1,
                    'pages' => $version->version === '1.0'
                        ? [
                            ['title' => 'Authentication', 'content' => $this->authContent(), 'sort_order' => 0],
                        ]
                        : [
                            ['title' => 'Authentication', 'content' => $this->authContent(), 'sort_order' => 0],
                            ['title' => 'Pagination', 'content' => $this->paginationContent(), 'sort_order' => 1],
                        ],
                ],
            ];

            foreach ($sections as $sectionData) {
                $pages = $sectionData['pages'];
                unset($sectionData['pages']);

                $section = DocSection::create(array_merge($sectionData, [
                    'doc_version_id' => $version->id,
                    'is_active' => true,
                ]));

                foreach ($pages as $pageData) {
                    DocPage::create([
                        'doc_section_id' => $section->id,
                        'title' => $pageData['title'],
                        'slug' => \Illuminate\Support\Str::slug($pageData['title']),
                        'content' => $pageData['content'],
                        'meta_title' => $pageData['title'] . ' | ' . $version->name,
                        'meta_description' => strip_tags(\Illuminate\Support\Str::limit($pageData['content'], 160)),
                        'sort_order' => $pageData['sort_order'],
                        'is_active' => true,
                    ]);
                }
            }
        }
    }

    private function introContent(): string
    {
        return <<<'HTML'
<h1>Introduction</h1>
<p>This documentation covers the Headless CMS API and how to integrate it with your frontend application.</p>
<h2>Key concepts</h2>
<ul>
<li><strong>Content API</strong> — All content endpoints are public and restricted by allowed origins (<code>FRONTEND_ALLOWED_ORIGINS</code>).</li>
<li><strong>JSON</strong> — Responses are JSON with UTF-8 encoding.</li>
<li><strong>Base URL</strong> — Use your CMS base URL, e.g. <code>https://your-cms.test</code>. Content lives under <code>/api/</code>.</li>
</ul>
<p>For a full list of endpoints, see the <a href="/api/docs">API docs index</a> or the Frontend API documentation.</p>
HTML;
    }

    private function installationContent(): string
    {
        return <<<'HTML'
<h1>Installation</h1>
<p>No SDK installation is required. Use any HTTP client to call the API.</p>
<h2>Example (JavaScript)</h2>
<pre><code>const response = await fetch('https://your-cms.test/api/pages?per_page=10');
const { data, meta } = await response.json();</code></pre>
<h2>Example (cURL)</h2>
<pre><code>curl -H "Accept: application/json" https://your-cms.test/api/pages/over-ons</code></pre>
<h2>CORS</h2>
<p>Ensure your frontend origin is listed in <code>FRONTEND_ALLOWED_ORIGINS</code> in the CMS <code>.env</code>. Otherwise requests will receive <code>403 Forbidden</code>.</p>
HTML;
    }

    private function authContent(): string
    {
        return <<<'HTML'
<h1>Authentication</h1>
<p>Content endpoints (pages, blog, legal, static, docs, modules, features, solutions, sitemap, vacancies, settings, menus, homepage) do <strong>not</strong> require authentication.</p>
<p>Access is controlled by the <code>Origin</code> and <code>Referer</code> headers. Configure <code>FRONTEND_ALLOWED_ORIGINS</code> in the CMS environment.</p>
<p>Analytics and form submission endpoints are rate-limited but also public.</p>
HTML;
    }

    private function paginationContent(): string
    {
        return <<<'HTML'
<h1>Pagination</h1>
<p>List endpoints support standard Laravel-style pagination.</p>
<h2>Query parameters</h2>
<ul>
<li><code>page</code> — Page number (default: 1).</li>
<li><code>per_page</code> — Items per page (default and limits vary by endpoint; typically 1–100).</li>
</ul>
<h2>Response</h2>
<p>The response includes a <code>data</code> array and a <code>meta</code> object with <code>current_page</code>, <code>last_page</code>, <code>per_page</code>, <code>total</code>, <code>from</code>, and <code>to</code>.</p>
<pre><code>{
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 10,
    "total": 25,
    "from": 1,
    "to": 10
  }
}</code></pre>
HTML;
    }

    private function rateLimitContent(): string
    {
        return <<<'HTML'
<h1>Rate limiting</h1>
<p>Some endpoints are rate-limited to prevent abuse:</p>
<ul>
<li><strong>Forms</strong> — Contact form, vacancy apply, blog comments, live session registration use the <code>throttle:forms</code> middleware.</li>
<li><strong>Analytics</strong> — Track, batch-track, guest-activity, and performance use <code>throttle:api</code>.</li>
</ul>
<p>When rate limit is exceeded, the API returns <code>429 Too Many Requests</code>. Retry after the period indicated in the response headers.</p>
HTML;
    }
}
