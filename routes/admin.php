<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\Administrator\ContactController;
use App\Http\Controllers\Admin\Administrator\CustomerController;
use App\Http\Controllers\Admin\Administrator\EmailLogController;
use App\Http\Controllers\Admin\Administrator\PermissionController;
use App\Http\Controllers\Admin\Administrator\RolesController;
use App\Http\Controllers\Admin\Administrator\UserCrudController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ContactFormController;
use App\Http\Controllers\Admin\ContactPageSettingsController;
use App\Http\Controllers\Admin\ContactSubjectController;
use App\Http\Controllers\Admin\ApiChangelogController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogTypeController;
use App\Http\Controllers\Admin\CarouselWidgetController;
use App\Http\Controllers\Admin\ChangelogController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseVideoController;
use App\Http\Controllers\Admin\DocPageController;
use App\Http\Controllers\Admin\DocSectionController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ExternalCodeController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\HomepageContentController;
use App\Http\Controllers\Admin\HomepageFaqController;
use App\Http\Controllers\Admin\LegalController;
use App\Http\Controllers\Admin\LiveSessionController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PresenterController;
use App\Http\Controllers\Admin\PricingBoosterController;
use App\Http\Controllers\Admin\PricingFeatureController;
use App\Http\Controllers\Admin\PricingPlanController;
use App\Http\Controllers\Admin\SessionRegistrationController;
use App\Http\Controllers\Admin\SolutionController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Admin\StickyMenuItemController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FooterLinkController;
use App\Http\Controllers\Admin\GeneralSettingsController;
use App\Http\Controllers\Admin\HeroBackgroundSettingsController;
use App\Http\Controllers\Admin\LoginSettingController;
use App\Http\Controllers\Admin\MailSettingController;
use App\Http\Controllers\Admin\Marketing\CaseStudyController;
use App\Http\Controllers\Admin\Marketing\ContentPlanController;
use App\Http\Controllers\Admin\Marketing\ContentTypeController;
use App\Http\Controllers\Admin\Marketing\HelpArticleController;
use App\Http\Controllers\Admin\Marketing\IntentBriefController;
use App\Http\Controllers\Admin\Marketing\MarketingDashboardController;
use App\Http\Controllers\Admin\Marketing\MarketingEventController;
use App\Http\Controllers\Admin\Marketing\MarketingPersonaController;
use App\Http\Controllers\Admin\Marketing\MarketingTestimonialController;
use App\Http\Controllers\Admin\Marketing\ProductFeatureController;
use App\Http\Controllers\Admin\MediaLibraryController;
use App\Http\Controllers\Admin\MegaMenuController;
use App\Http\Controllers\Admin\Settings\AISettingsController;
use App\Http\Controllers\Admin\Settings\ImageOptimizerController;
use App\Http\Controllers\Admin\Settings\RobotsTxtController;
use App\Http\Controllers\Admin\SocialMediaPlatformController;
use App\Http\Controllers\Admin\SocialSettingController;
use App\Http\Controllers\Admin\ThemeSettingController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\TwoFactorController as AdminTwoFactorController;
use App\Http\Controllers\Admin\VacancyModule\JobApplicationController;
use App\Http\Controllers\Admin\VacancyModule\VacancyController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Livewire\Admin\ThemeManager;
use App\Models\SocialMediaPlatform;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    // Guest routes (no auth required)
    Route::get('login', function () {
        return view('admin.auth.login');
    })->name('login');

    Route::get('forgot-password', function () {
        return view('admin.auth.forgot-password');
    })->name('forgot-password');

    // Authentication POST routes
    Route::post('login', [LoginController::class, 'login'])->name('login.post');

    // Two-Factor Authentication Routes (guest accessible)
    Route::get('two-factor-challenge', [TwoFactorController::class, 'show'])->name('two-factor.challenge');
    Route::post('two-factor-challenge', [TwoFactorController::class, 'verify'])->name('two-factor.verify');
    Route::get('two-factor-recovery', [TwoFactorController::class, 'showRecovery'])->name('two-factor.recovery');
    Route::post('two-factor-recovery', [TwoFactorController::class, 'verifyRecovery'])->name('two-factor.recovery.verify');

    Route::get('reset-password/{token}', function (string $token) {
        return view('admin.auth.reset-password', ['request' => request()]);
    })->name('password.reset');

    // Auth routes (auth required)
    Route::get('verify-email', function () {
        return view('admin.auth.verify-email');
    })->name('verification.notice');

    Route::post('email/verification-notification', [EmailVerificationController::class, 'resend'])->name('verification.send');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.store');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::post('confirm-password', [ConfirmPasswordController::class, 'confirm'])->name('password.confirm');

    Route::group(['middleware' => 'admin'], function () {

        // Profile Management
        Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('user/password', [PasswordController::class, 'update'])->name('password.update');
        Route::put('user/profile-information', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('user/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/search', [DashboardController::class, 'search'])->name('admin.search');

        // Analytics routes
        Route::group(['prefix' => 'analytics', 'as' => 'analytics.'], function () {
            Route::get('/', [AnalyticsController::class, 'index'])->name('index');
            Route::get('/url/{encodedUrl}', [AnalyticsController::class, 'urlDetails'])->name('url-details')->where('encodedUrl', '.*');
        });

        // Activity Log routes
        Route::prefix('activity-log')->name('activity-log.')->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])->name('index');
            Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
            Route::post('/clean', [ActivityLogController::class, 'clean'])->name('clean');
        });

        // Content Management
        // Pages
        Route::resource('page', PageController::class);
        Route::post('page/{page}/toggle-active', [PageController::class, 'toggleActive'])->name('page.toggle-active');

        // Legal Pages
        Route::resource('legal', LegalController::class);
        Route::post('legal/{legal}/toggle-active', [LegalController::class, 'toggleActive'])->name('legal.toggle-active');
        Route::post('legal/{legal}/faq', [LegalController::class, 'storeFaq'])->name('legal.store-faq');
        Route::put('legal/{legal}/faq/{faqId}', [LegalController::class, 'updateFaq'])->name('legal.update-faq');
        Route::delete('legal/{legal}/faq/{faqId}', [LegalController::class, 'destroyFaq'])->name('legal.destroy-faq');
        Route::post('legal/{legal}/faq/{faqId}/toggle-active', [LegalController::class, 'toggleFaqActive'])->name('legal.toggle-faq-active');

        // Legal Page Versions
        Route::get('legal/{legal}/versions', [LegalController::class, 'versions'])->name('legal.versions');
        Route::get('legal/{legal}/versions/{versionNumber}', [LegalController::class, 'showVersion'])->name('legal.version.show');
        Route::post('legal/{legal}/versions/{versionNumber}/restore', [LegalController::class, 'restoreVersion'])->name('legal.version.restore');
        Route::post('legal/{legal}/versions/create', [LegalController::class, 'createVersion'])->name('legal.version.create');

        // Static Pages
        Route::resource('static-page', StaticPageController::class);
        Route::post('static-page/{staticPage}/toggle-active', [StaticPageController::class, 'toggleActive'])->name('static-page.toggle-active');
        Route::post('static-page/{staticPage}/faq', [StaticPageController::class, 'storeFaq'])->name('static-page.store-faq');
        Route::put('static-page/{staticPage}/faq/{faqId}', [StaticPageController::class, 'updateFaq'])->name('static-page.update-faq');
        Route::delete('static-page/{staticPage}/faq/{faqId}', [StaticPageController::class, 'destroyFaq'])->name('static-page.destroy-faq');
        Route::post('static-page/{staticPage}/faq/{faqId}/toggle-active', [StaticPageController::class, 'toggleFaqActive'])->name('static-page.toggle-faq-active');

        // Blog Categories
        Route::resource('blog-category', BlogCategoryController::class);
        Route::post('blog-category/{blogCategory}/toggle-active', [BlogCategoryController::class, 'toggleActive'])->name('blog-category.toggle-active');

        // Blog Types
        Route::resource('blog-type', BlogTypeController::class);

        // Organizations
        Route::get('organization/import-sample', [OrganizationController::class, 'importSample'])->name('organization.import-sample');
        Route::get('organization/{organization}/json', [OrganizationController::class, 'getJson'])->name('organization.json');
        Route::post('organization/import', [OrganizationController::class, 'import'])->name('organization.import');
        Route::resource('organization', OrganizationController::class);

        // Blogs
        Route::resource('blog', BlogController::class);
        Route::post('blog/{blog}/toggle-active', [BlogController::class, 'toggleActive'])->name('blog.toggle-active');
        Route::post('blog/{blog}/toggle-featured', [BlogController::class, 'toggleFeatured'])->name('blog.toggle-featured');
        Route::post('blog/{blog}/social-media-post', [BlogController::class, 'createSocialMediaPost'])->name('blog.social-media-post');
        Route::get('blog/{blog}/social-media-posts', [BlogController::class, 'socialMediaPosts'])->name('blog.social-media-posts');
        Route::post('blog/{blog}/analyze-seo', [BlogController::class, 'analyzeSEO'])->name('blog.analyze-seo');
        Route::post('blog/generate-with-ai', [BlogController::class, 'generateWithAI'])->name('blog.generate-with-ai');

        // Social Media Platforms API
        Route::get('social-media-platforms', function () {
            return SocialMediaPlatform::active()->ordered()->get();
        });

        // Solutions
        Route::resource('solution', SolutionController::class);
        Route::post('solution/{solution}/toggle-active', [SolutionController::class, 'toggleActive'])->name('solution.toggle-active');

        // Modules
        Route::resource('module', ModuleController::class);
        Route::post('modules/update-order', [ModuleController::class, 'updateOrder'])->name('modules.update-order');

        // Features
        Route::resource('feature', FeatureController::class);
        Route::post('features/update-order', [FeatureController::class, 'updateOrder'])->name('features.update-order');

        // Changelog
        Route::resource('changelog', ChangelogController::class);
        Route::post('changelogs/update-order', [ChangelogController::class, 'updateOrder'])->name('changelogs.update-order');

        // Documentation
        Route::resource('doc-sections', DocSectionController::class)->parameters(['doc-sections' => 'docSection']);
        Route::post('doc-sections/{docSection}/toggle-active', [DocSectionController::class, 'toggleActive'])->name('doc-sections.toggle-active');
        Route::resource('doc-pages', DocPageController::class)->parameters(['doc-pages' => 'docPage']);
        Route::post('doc-pages/{docPage}/toggle-active', [DocPageController::class, 'toggleActive'])->name('doc-pages.toggle-active');

        // API Changelog
        Route::get('api-changelog', [ApiChangelogController::class, 'index'])->name('api-changelog.index');
        Route::get('api-changelog/{changelog}', [ApiChangelogController::class, 'show'])->name('api-changelog.show');

        // Homepage content (hero, feature cards, about OPMS, etc. – single edit page)
        Route::get('homepage', [HomepageContentController::class, 'edit'])->name('homepage.edit');
        Route::put('homepage', [HomepageContentController::class, 'update'])->name('homepage.update');

        // Homepage FAQs
        Route::resource('faq-module', HomepageFaqController::class)->parameters(['faq-module' => 'faq']);
        Route::post('faq-modules/update-order', [HomepageFaqController::class, 'updateOrder'])->name('faq-modules.update-order');

        // External Codes
        Route::resource('external-code', ExternalCodeController::class);
        Route::post('external-codes/update-order', [ExternalCodeController::class, 'updateOrder'])->name('external-codes.update-order');

        // Carousel Widgets
        Route::resource('carousel-widgets', CarouselWidgetController::class);
        Route::post('carousel-widgets/{carousel_widget}/toggle-active', [CarouselWidgetController::class, 'toggleActive'])->name('carousel-widgets.toggle-active');

        // Vacancy Management
        Route::prefix('vacancies')->name('vacancies.')->group(function () {
            Route::get('/', [VacancyController::class, 'index'])->name('index');
            Route::get('/create', [VacancyController::class, 'create'])->name('create');
            Route::post('/', [VacancyController::class, 'store'])->name('store');
            Route::get('/{vacancy:id}', [VacancyController::class, 'show'])->name('show');
            Route::get('/{vacancy:id}/edit', [VacancyController::class, 'edit'])->name('edit');
            Route::put('/{vacancy:id}', [VacancyController::class, 'update'])->name('update');
            Route::post('/{vacancy:id}/update-status', [VacancyController::class, 'updateStatus'])->name('update-status');
            Route::delete('/{vacancy:id}', [VacancyController::class, 'destroy'])->name('destroy');

        });

        // Job Application Management
        Route::prefix('job-applications')->name('job-applications.')->group(function () {
            Route::get('/', [JobApplicationController::class, 'index'])->name('index');
            Route::get('/create', [JobApplicationController::class, 'create'])->name('create');
            Route::post('/', [JobApplicationController::class, 'store'])->name('store');
            Route::get('/{jobApplication:id}', [JobApplicationController::class, 'show'])->name('show');
            Route::get('/{jobApplication:id}/edit', [JobApplicationController::class, 'edit'])->name('edit');
            Route::put('/{jobApplication:id}', [JobApplicationController::class, 'update'])->name('update');
            Route::delete('/{jobApplication:id}', [JobApplicationController::class, 'destroy'])->name('destroy');
            Route::post('/{jobApplication:id}/toggle-processed', [JobApplicationController::class, 'toggleProcessed'])->name('toggle-processed');
        });

        // Marketing Automation
        Route::prefix('marketing')->name('marketing.')->group(function () {
            // Marketing Personas
            Route::resource('persona', MarketingPersonaController::class);

            // Content Types
            Route::resource('content-type', ContentTypeController::class);

            // Marketing Testimonials
            Route::resource('testimonial', MarketingTestimonialController::class);
            Route::post('testimonial/{testimonial}/toggle-featured', [MarketingTestimonialController::class, 'toggleFeatured'])->name('testimonial.toggle-featured');

            // Product Features
            Route::resource('product-feature', ProductFeatureController::class);

            // Help Articles
            Route::resource('help-article', HelpArticleController::class);
            Route::post('help-article/{helpArticle}/toggle-featured', [HelpArticleController::class, 'toggleFeatured'])->name('help-article.toggle-featured');

            // Case Studies
            Route::resource('case-study', CaseStudyController::class);
            Route::post('case-study/{caseStudy}/toggle-featured', [CaseStudyController::class, 'toggleFeatured'])->name('case-study.toggle-featured');

            // Marketing Events
            Route::resource('marketing-event', MarketingEventController::class);
            Route::post('marketing-event/{marketingEvent}/toggle-featured', [MarketingEventController::class, 'toggleFeatured'])->name('marketing-event.toggle-featured');

            // Marketing Dashboard
            Route::get('dashboard', [MarketingDashboardController::class, 'index'])->name('dashboard');

            // Intent Briefs
            Route::resource('intent-briefs', IntentBriefController::class);
            Route::post('intent-briefs/{intentBrief}/generate-plan', [IntentBriefController::class, 'generatePlan'])->name('intent-briefs.generate-plan');

            // Content Plans
            Route::resource('content-plans', ContentPlanController::class)->except(['create', 'store', 'edit', 'update']);
            Route::post('content-plans/{contentPlan}/approve', [ContentPlanController::class, 'approve'])->name('content-plans.approve');
            Route::post('content-plans/{contentPlan}/generate', [ContentPlanController::class, 'generate'])->name('content-plans.generate');
            Route::put('content-plans/{contentPlan}/autopilot-mode', [ContentPlanController::class, 'updateAutopilotMode'])->name('content-plans.update-autopilot-mode');
            Route::put('content-plans/{contentPlan}/items/{item}', [ContentPlanController::class, 'updateItem'])->name('content-plans.update-item');
        });

        // Events
        Route::resource('event', EventController::class);

        // Comments
        Route::resource('comment', CommentController::class)->only(['index', 'show']);
        Route::post('comment/{comment}/toggle-approve', [CommentController::class, 'toggleApprove'])->name('comment.toggle-approve');
        Route::post('event/{event}/toggle-active', [EventController::class, 'toggleActive'])->name('event.toggle-active');

        // Pricing Plans
        Route::resource('pricing-plan', PricingPlanController::class);
        Route::post('pricing-plans/update-order', [PricingPlanController::class, 'updateOrder'])->name('pricing-plans.update-order');

        // Pricing Boosters
        Route::resource('pricing-booster', PricingBoosterController::class);
        Route::post('pricing-boosters/update-order', [PricingBoosterController::class, 'updateOrder'])->name('pricing-boosters.update-order');

        // Pricing Features
        Route::resource('pricing-feature', PricingFeatureController::class);
        Route::post('pricing-features/update-order', [PricingFeatureController::class, 'updateOrder'])->name('pricing-features.update-order');

        // Course - Categories (video categories)
        Route::get('course-category/{course_category}/json', [CourseCategoryController::class, 'getJson'])->name('course-category.json');
        Route::post('course-category/{course_category}/toggle-active', [CourseCategoryController::class, 'toggleActive'])->name('course-category.toggle-active');
        Route::resource('course-category', CourseCategoryController::class);

        // Course - Chapters (full CRUD + JSON API for video form dropdown)
        Route::get('courses/by-category', [CourseController::class, 'getByCategory'])->name('courses.by-category');
        Route::resource('course', CourseController::class);

        // Course - Videos
        Route::post('course-video/{course_video}/toggle-active', [CourseVideoController::class, 'toggleActive'])->name('course-video.toggle-active');
        Route::resource('course-video', CourseVideoController::class);

        // Academy - Live Sessions
        Route::resource('live-session', LiveSessionController::class);
        Route::post('live-sessions/update-order', [LiveSessionController::class, 'updateOrder'])->name('live-sessions.update-order');
        Route::post('live-session/{liveSession}/toggle-status', [LiveSessionController::class, 'toggleStatus'])->name('live-session.toggle-status');
        Route::post('live-session/{liveSession}/update-session-status', [LiveSessionController::class, 'updateSessionStatus'])->name('live-session.update-session-status');

        // Academy - Presenters
        Route::resource('presenter', PresenterController::class);
        Route::post('presenters/update-order', [PresenterController::class, 'updateOrder'])->name('presenters.update-order');
        Route::post('presenter/{presenter}/toggle-status', [PresenterController::class, 'toggleStatus'])->name('presenter.toggle-status');
        Route::delete('presenter/{presenter}/remove-avatar', [PresenterController::class, 'removeAvatar'])->name('presenter.remove-avatar');

        // Academy - Session Registrations
        Route::resource('session-registration', SessionRegistrationController::class);
        Route::post('session-registration/{sessionRegistration}/mark-attended', [SessionRegistrationController::class, 'markAttended'])->name('session-registration.mark-attended');
        Route::post('session-registration/{sessionRegistration}/mark-no-show', [SessionRegistrationController::class, 'markNoShow'])->name('session-registration.mark-no-show');
        Route::post('session-registration/{sessionRegistration}/cancel', [SessionRegistrationController::class, 'cancel'])->name('session-registration.cancel');
        Route::post('session-registrations/export', [SessionRegistrationController::class, 'export'])->name('session-registrations.export');

        // Sticky Menu Items
        Route::resource('sticky-menu-item', StickyMenuItemController::class)->except(['show']);
        Route::post('sticky-menu-items/update-order', [StickyMenuItemController::class, 'updateOrder'])->name('sticky-menu-items.update-order');
        Route::post('sticky-menu-item/{stickyMenuItem}/toggle-status', [StickyMenuItemController::class, 'toggleStatus'])->name('sticky-menu-item.toggle-status');

        // Administrator Management
        Route::prefix('administrator')->name('administrator.')->group(function () {
            Route::delete('customers/bulk-delete', [CustomerController::class, 'bulkDelete'])
                ->name('customers.bulk_delete');

            Route::post('customers/{customer}/active_account', [CustomerController::class, 'activeAccount'])
                ->name('customers.active_account');

            Route::resource('customers', CustomerController::class);
            Route::resource('users', UserCrudController::class);
            Route::get('users/search-non-admins', [UserCrudController::class, 'searchNonAdmins'])->name('users.search-non-admins');
            Route::post('users/assign-admin-role', [UserCrudController::class, 'assignAdminRole'])->name('users.assign-admin-role');
            Route::resource('permissions', PermissionController::class);
            Route::resource('roles', RolesController::class);

            // Contacts
            Route::resource('contacts', ContactController::class);
            Route::post('contacts/{contact}/toggle-active', [ContactController::class, 'toggleActive'])->name('contacts.toggle-active');

            // Contact Forms
            Route::resource('contact-forms', ContactFormController::class)->only(['index', 'show', 'update', 'destroy']);
            Route::post('contact-forms/{contactForm}/reply', [ContactFormController::class, 'reply'])->name('contact-forms.reply');

            // Contact Subjects (Onderwerp – dropdown options for contact form)
            Route::resource('contact-subjects', ContactSubjectController::class)->parameters(['contact-subjects' => 'contactSubject']);

            // Email Logs
            Route::resource('email-logs', EmailLogController::class)->only(['index', 'show', 'destroy']);
            Route::post('email-logs/bulk-destroy', [EmailLogController::class, 'bulkDestroy'])->name('email-logs.bulk-destroy');

        });

        // Social Settings routes
        Route::group(['prefix' => 'social-settings', 'as' => 'social-settings.'], function () {
            Route::get('/', [SocialSettingController::class, 'index'])->name('index');
            Route::get('/create', [SocialSettingController::class, 'create'])->name('create');
            Route::post('/', [SocialSettingController::class, 'store'])->name('store');
            Route::get('/{socialSetting}/edit', [SocialSettingController::class, 'edit'])->name('edit');
            Route::put('/{socialSetting}', [SocialSettingController::class, 'update'])->name('update');
            Route::delete('/{socialSetting}', [SocialSettingController::class, 'destroy'])->name('destroy');
            Route::post('/clear-cache', [SocialSettingController::class, 'clearCache'])->name('clear-cache');
        });

        // Mail Settings
        Route::get('/mail', [MailSettingController::class, 'index'])->name('mail.index');
        Route::put('/mail', [MailSettingController::class, 'update'])->name('mail.update');
        Route::post('/mail/test', [MailSettingController::class, 'testMail'])->name('mail.test');

        // Translation Management
        Route::group(['prefix' => 'translations', 'as' => 'translations.'], function () {
            Route::get('/', [TranslationController::class, 'index'])->name('index');
            Route::get('/create', [TranslationController::class, 'create'])->name('create');
            Route::post('/', [TranslationController::class, 'store'])->name('store');
            Route::get('/{translation}/edit', [TranslationController::class, 'edit'])->name('edit');
            Route::put('/{translation}', [TranslationController::class, 'update'])->name('update');
            Route::delete('/{translation}', [TranslationController::class, 'destroy'])->name('destroy');
            Route::post('/import', [TranslationController::class, 'import'])->name('import');
            Route::post('/bulk-import', [TranslationController::class, 'import'])->name('bulk-import');
            Route::post('/run-import-command', [TranslationController::class, 'runImportCommand'])->name('run-import-command');
            Route::get('/export', [TranslationController::class, 'export'])->name('export');
            Route::post('/clear-cache', [TranslationController::class, 'clearCache'])->name('clear-cache');
            Route::post('/load-cache', [TranslationController::class, 'loadCache'])->name('load-cache');
            Route::post('/bulk-delete', [TranslationController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/{translation}/toggle-active', [TranslationController::class, 'toggleActive'])->name('toggle-active');
        });

        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
            Route::group(['prefix' => 'general', 'as' => 'general.'], function () {
                Route::get('/', [GeneralSettingsController::class, 'index'])->name('index');
                Route::put('/', [GeneralSettingsController::class, 'update'])->name('update');
            });

            // Contact Page (front contact page content)
            Route::group(['prefix' => 'contact-page', 'as' => 'contact.'], function () {
                Route::get('/', [ContactPageSettingsController::class, 'index'])->name('index');
                Route::put('/', [ContactPageSettingsController::class, 'update'])->name('update');
            });

            // AI Settings
            Route::group(['prefix' => 'ai', 'as' => 'ai.'], function () {
                Route::get('/', [AISettingsController::class, 'index'])->name('index');
                Route::put('/', [AISettingsController::class, 'update'])->name('update');
                Route::post('/test-connection', [AISettingsController::class, 'testConnection'])->name('test-connection');
            });

            // Theme Management
            Route::get('/admintheme', ThemeManager::class)->name('admintheme');

            // Theme Settings
            Route::group(['prefix' => 'theme', 'as' => 'theme.'], function () {
                Route::get('/', [ThemeSettingController::class, 'index'])->name('index');
                Route::put('/', [ThemeSettingController::class, 'update'])->name('update');
            });

            // Login Settings
            Route::group(['prefix' => 'login', 'as' => 'login.'], function () {
                Route::get('/', [LoginSettingController::class, 'index'])->name('index');
                Route::put('/', [LoginSettingController::class, 'update'])->name('update');
            });

            // Hero / Header section backgrounds
            Route::group(['prefix' => 'hero-backgrounds', 'as' => 'hero-backgrounds.'], function () {
                Route::get('/', [HeroBackgroundSettingsController::class, 'index'])->name('index');
                Route::put('/', [HeroBackgroundSettingsController::class, 'update'])->name('update');
            });

            // Cookie Settings
            Route::group(['prefix' => 'cookie', 'as' => 'cookie.'], function () {
                Route::get('/', [\App\Http\Controllers\Admin\CookieSettingsController::class, 'index'])->name('index');
                Route::put('/', [\App\Http\Controllers\Admin\CookieSettingsController::class, 'update'])->name('update');
            });

            // Footer Links
            Route::group(['prefix' => 'footer-links', 'as' => 'footer-links.'], function () {
                Route::put('order', [FooterLinkController::class, 'updateOrder'])->name('order');
                Route::post('update-cta-settings', [FooterLinkController::class, 'updateCtaSettings'])->name('update-cta-settings');
                Route::patch('{footerLink}/toggle', [FooterLinkController::class, 'toggleActive'])->name('toggle');
                Route::resource('', FooterLinkController::class)->except(['show'])->parameters(['' => 'footer_link']);
            });

            // Mega Menu
            Route::group(['prefix' => 'mega-menu', 'as' => 'mega-menu.'], function () {
                Route::get('get-module-items', [MegaMenuController::class, 'getModuleItems'])->name('get-module-items');
                Route::post('update-order', [MegaMenuController::class, 'updateOrder'])->name('update-order');
                Route::post('update-all-settings', [MegaMenuController::class, 'updateAllSettings'])->name('update-all-settings');
                Route::post('update-header-cta-settings', [MegaMenuController::class, 'updateHeaderCtaSettings'])->name('update-header-cta-settings');
                Route::post('{megaMenu}/add-sub-item', [MegaMenuController::class, 'addSubItem'])->name('add-sub-item');
                Route::get('{megaMenu}/sub-item/{subItem}/edit', [MegaMenuController::class, 'editSubItem'])->name('edit-sub-item');
                Route::put('{megaMenu}/sub-item/{subItem}', [MegaMenuController::class, 'updateSubItem'])->name('update-sub-item');
                Route::delete('{megaMenu}/sub-item/{subItem}', [MegaMenuController::class, 'removeSubItem'])->name('remove-sub-item');
                Route::resource('', MegaMenuController::class)->except(['show'])->parameters(['' => 'megaMenu']);
            });

            // Social Media Platforms
            Route::group(['prefix' => 'social-media-platforms', 'as' => 'social-media-platforms.'], function () {
                Route::post('{socialMediaPlatform}/toggle-active', [SocialMediaPlatformController::class, 'toggleActive'])->name('toggle-active');
                Route::resource('', SocialMediaPlatformController::class)->parameters(['' => 'socialMediaPlatform']);
            });

            // Robots.txt
            Route::group(['prefix' => 'robots-txt', 'as' => 'robots-txt.'], function () {
                Route::get('/', [RobotsTxtController::class, 'index'])->name('index');
                Route::put('/', [RobotsTxtController::class, 'update'])->name('update');
                Route::post('/reset', [RobotsTxtController::class, 'reset'])->name('reset');
                Route::post('/clear-cache', [RobotsTxtController::class, 'clearCache'])->name('clear-cache');
            });
        });

        // Two-Factor Authentication Management
        Route::group(['prefix' => 'security', 'as' => 'security.'], function () {
            Route::group(['prefix' => 'two-factor', 'as' => 'two-factor.'], function () {
                Route::get('/', [AdminTwoFactorController::class, 'index'])->name('index');
                Route::post('/enable', [AdminTwoFactorController::class, 'enable'])->name('enable');
                Route::post('/confirm', [AdminTwoFactorController::class, 'confirm'])->name('confirm');
                Route::post('/disable', [AdminTwoFactorController::class, 'disable'])->name('disable');
                Route::post('/recovery-codes', [AdminTwoFactorController::class, 'generateRecoveryCodes'])->name('recovery-codes.generate');
                Route::post('/recovery-codes/show', [AdminTwoFactorController::class, 'showRecoveryCodes'])->name('recovery-codes.show');
                Route::post('/test', [AdminTwoFactorController::class, 'test'])->name('test');
            });
        });

        // Media Library (storage file manager)
        Route::group(['prefix' => 'media-library', 'as' => 'media-library.'], function () {
            Route::get('/', [MediaLibraryController::class, 'index'])->name('index');
            Route::get('/download', [MediaLibraryController::class, 'download'])->name('download');
            Route::get('/preview', [MediaLibraryController::class, 'preview'])->name('preview');
            Route::delete('/', [MediaLibraryController::class, 'destroy'])->name('destroy');
            Route::post('/resize', [MediaLibraryController::class, 'resize'])->name('resize');
        });

        // Image Optimizer Routes
        Route::group(['prefix' => 'image-optimizer', 'as' => 'image-optimizer.'], function () {
            Route::get('/', [ImageOptimizerController::class, 'index'])->name('index');
            Route::get('/stream', [ImageOptimizerController::class, 'stream'])->name('stream');
        });

        // Catch-all route for any undefined admin/* routes
        Route::fallback(function () {
            return redirect()->route('admin.index');
        });
    });

});
