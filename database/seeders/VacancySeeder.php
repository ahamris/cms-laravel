<?php

namespace Database\Seeders;

use App\Models\VacancyModule\Vacancy;
use Illuminate\Database\Seeder;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates default vacancy roles.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        if (Vacancy::count() > 0) {
            return;
        }

        $vacancies = [
            [
                'title' => 'Middle Fullstack PHP/React Developer',
                'location' => 'Remote',
                'short_code' => 'BE',
                'type' => 'full-time',
                'hours_per_week' => '40',
                'experience_level' => 'middle',
                'department' => 'Engineering',
                'category' => 'Development',
                'description' => 'We are looking for a Middle Fullstack Developer to join our product team. You will work on both backend (PHP/Laravel) and frontend (React) parts of our applications, collaborating with product and design to deliver reliable and user-friendly features.',
                'requirements' => "• 2+ years of experience with PHP and Laravel\n• Solid experience with React and modern JavaScript/TypeScript\n• Understanding of REST APIs and single-page applications\n• Familiarity with Git, SQL, and basic DevOps (CI/CD)\n• Good communication in English",
                'responsibilities' => "• Develop and maintain backend APIs and frontend React applications\n• Participate in code reviews and technical design\n• Write tests and documentation\n• Collaborate with designers and product owners",
                'salary_range' => 'Competitive',
                'closing_date' => now()->addMonths(2),
            ],
            [
                'title' => 'Backend Developer Middle',
                'location' => 'Remote',
                'short_code' => 'BE',
                'type' => 'full-time',
                'hours_per_week' => '40',
                'experience_level' => 'middle',
                'department' => 'Engineering',
                'category' => 'Backend',
                'description' => 'We need a Middle Backend Developer to help build and scale our services. You will work mainly with PHP, Laravel, and databases, and integrate with internal and external systems.',
                'requirements' => "• 2+ years of backend development (PHP preferred)\n• Experience with Laravel or similar frameworks\n• Strong SQL and database design skills\n• Knowledge of queues, caching, and API design\n• English (written and spoken)",
                'responsibilities' => "• Design and implement backend features and APIs\n• Optimize performance and reliability\n• Collaborate with frontend and DevOps teams\n• Document and test your work",
                'salary_range' => 'Competitive',
                'closing_date' => now()->addMonths(2),
            ],
            [
                'title' => 'Senior Backend Developer PHP',
                'location' => 'Remote',
                'short_code' => 'BE',
                'type' => 'full-time',
                'hours_per_week' => '40',
                'experience_level' => 'senior',
                'department' => 'Engineering',
                'category' => 'Backend',
                'description' => 'We are hiring a Senior Backend Developer to lead technical decisions and mentor others. You will own critical services, improve architecture, and help the team grow.',
                'requirements' => "• 5+ years of backend development with PHP\n• Deep experience with Laravel and ecosystem\n• Strong knowledge of databases, scaling, and security\n• Experience with event-driven design, queues, and APIs\n• Leadership and mentoring skills",
                'responsibilities' => "• Lead design and implementation of backend systems\n• Review code and set technical standards\n• Mentor mid-level developers\n• Collaborate with product and infrastructure teams",
                'salary_range' => 'Competitive',
                'closing_date' => now()->addMonths(3),
            ],
            [
                'title' => 'Middle UX/UI Developer',
                'location' => 'Remote',
                'short_code' => 'FE',
                'type' => 'full-time',
                'hours_per_week' => '40',
                'experience_level' => 'middle',
                'department' => 'Product & Design',
                'category' => 'Design',
                'description' => 'We are looking for a Middle UX/UI Developer to turn designs into accessible, responsive interfaces and contribute to our design system. You will work closely with designers and frontend developers.',
                'requirements' => "• 2+ years of experience in UI/front-end development\n• Strong HTML, CSS, and JavaScript/TypeScript skills\n• Experience with design tools (Figma, Sketch, or similar)\n• Understanding of accessibility (a11y) and responsive design\n• Eye for detail and user experience",
                'responsibilities' => "• Implement UI from mockups and design system\n• Build reusable components and maintain style guides\n• Ensure cross-browser and responsive behaviour\n• Collaborate with UX designers and developers",
                'salary_range' => 'Competitive',
                'closing_date' => now()->addMonths(2),
            ],
            [
                'title' => 'DevOps Engineer (Kubernetes, Docker, GitLab CI)',
                'location' => 'Remote',
                'short_code' => 'DO',
                'type' => 'full-time',
                'hours_per_week' => '40',
                'experience_level' => 'middle',
                'department' => 'Infrastructure',
                'category' => 'DevOps',
                'description' => 'We need a DevOps Engineer to own our Kubernetes clusters, container pipelines, and CI/CD. You will work with Docker, GitLab Pipelines, and cloud infrastructure to keep our platform reliable and fast.',
                'requirements' => "• Hands-on experience with Kubernetes (deployments, services, ingress, Helm)\n• Strong Docker and container best practices\n• GitLab CI/CD: pipelines, runners, and automation\n• CI/CD concepts: build, test, deploy, and rollback\n• Linux, scripting (Bash/Python), and basic networking\n• Experience with cloud providers (AWS, GCP, or Azure) is a plus",
                'responsibilities' => "• Maintain and evolve Kubernetes clusters and workloads\n• Design and improve GitLab pipelines (CI/CD)\n• Automate provisioning, monitoring, and backups\n• Support development teams with tooling and runbooks",
                'salary_range' => 'Competitive',
                'closing_date' => now()->addMonths(3),
            ],
        ];

        foreach ($vacancies as $data) {
            $slug = \Illuminate\Support\Str::slug($data['title']);
            Vacancy::updateOrCreate(
                ['slug' => $slug],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
