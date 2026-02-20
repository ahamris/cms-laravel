<?php

namespace App\Providers;

use App\Helpers\Variable;
use App\Listeners\LogFailedEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use App\Listeners\LogSentEmail;
use App\Models\VacancyModule\JobApplication;
use App\Observers\JobApplicationObserver;
use App\Services\TranslationService;
use App\View\Components\Navigation\Breadcrumbs;
use App\View\Components\UI\Accordion;
use App\View\Components\UI\AccordionItem;
use App\View\Components\UI\Alert;
use App\View\Components\UI\Avatar;
use App\View\Components\UI\Badge;
use App\View\Components\UI\Button;
use App\View\Components\UI\Card;
use App\View\Components\UI\Checkbox;
use App\View\Components\UI\ColorPicker;
use App\View\Components\UI\DatePicker;
use App\View\Components\UI\Divider;
use App\View\Components\UI\Dropdown;
use App\View\Components\UI\IconPicker;
use App\View\Components\UI\ImageUpload;
use App\View\Components\UI\Input;
use App\View\Components\UI\Modal;
use App\View\Components\UI\Pagination;
use App\View\Components\UI\Radio;
use App\View\Components\UI\Select;
use App\View\Components\UI\TagInput;
use App\View\Components\UI\Textarea;
use App\View\Components\UI\Toast;
use App\View\Components\UI\Toggle;
use App\View\Components\UI\Tooltip;
use App\View\Composers\AIServiceStatusComposer;
use App\View\Composers\MegaMenuComposer;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        require_once app_path('Helpers/functions.php');

        $this->registerGates();

        // Define the 'api' rate limiter (required by throttle:api middleware)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?? client_ip() ?? 'unknown');
        });

        // Register Translation Service
        $this->app->singleton(TranslationService::class);

        // Register Observers
        JobApplication::observe(JobApplicationObserver::class);

        // Register Email Logging Listeners
        Event::listen(MessageSent::class, LogSentEmail::class);

        // Form Components
        Blade::component(Button::class, 'button');
        Blade::component(Button::class, 'ui.button');
        Blade::component(Input::class, 'input');
        Blade::component(Input::class, 'ui.input');
        Blade::component(Textarea::class, 'textarea');
        Blade::component(Textarea::class, 'ui.textarea');
        Blade::component(Select::class, 'select');
        Blade::component(Select::class, 'ui.select');
        Blade::component(DatePicker::class, 'datepicker');
        Blade::component(DatePicker::class, 'ui.datepicker');
        Blade::component(ColorPicker::class, 'colorpicker');
        Blade::component(ColorPicker::class, 'ui.colorpicker');
        Blade::component(Checkbox::class, 'checkbox');
        Blade::component(Checkbox::class, 'ui.checkbox');
        Blade::component(Toggle::class, 'toggle');
        Blade::component(Toggle::class, 'ui.toggle');
        Blade::component(Radio::class, 'radio');
        Blade::component(Radio::class, 'ui.radio');
        Blade::component(TagInput::class, 'tag-input');
        Blade::component(TagInput::class, 'ui.tag-input');
        Blade::component(Dropdown::class, 'ui.dropdown');
        Blade::component(IconPicker::class, 'icon-picker');
        Blade::component(IconPicker::class, 'ui.icon-picker');
        Blade::component(ImageUpload::class, 'image-upload');
        Blade::component(ImageUpload::class, 'ui.image-upload');

        // Layout Components
        Blade::component(Card::class, 'ui.card');
        Blade::component(Modal::class, 'ui.modal');
        Blade::component(Accordion::class, 'accordion');
        Blade::component(Accordion::class, 'ui.accordion');
        Blade::component(AccordionItem::class, 'accordion.item');
        Blade::component(AccordionItem::class, 'ui.accordion-item');
        Blade::component(Divider::class, 'divider');
        Blade::component(Divider::class, 'ui.divider');

        // Feedback Components
        Blade::component(Alert::class, 'alert');
        Blade::component(Alert::class, 'ui.alert');
        Blade::component(Badge::class, 'badge');
        Blade::component(Badge::class, 'ui.badge');
        Blade::component(Avatar::class, 'avatar');
        Blade::component(Avatar::class, 'ui.avatar');
        Blade::component(Tooltip::class, 'ui.tooltip');
        Blade::component(Toast::class, 'ui.toast');

        // Navigation Components
        Blade::component(Pagination::class, 'ui.pagination');
        Blade::component(Breadcrumbs::class, 'navigation.breadcrumbs');
    }

    /**
     * Register authorization gates for role-based and permission-based access.
     * - accessAdmin / accessUser: used by middleware, return 403 when role is not attached.
     * - Any other ability: checked via Variable::hasPermission() (e.g. user_edit, blog_create).
     * Use with: Gate::allows('blog_edit'), @can('blog_edit'), $this->authorize('blog_edit'),
     * or route middleware: ->middleware('can:blog_edit').
     */
    private function registerGates(): void
    {
        Gate::define('accessAdmin', function ($user) {
            return $user->hasRole(Variable::ROLE_ADMIN);
        });

        Gate::define('accessUser', function ($user) {
            return $user->hasRole(Variable::ROLE_USER);
        });

        // Permission-based gates: any ability in Variable::$fullPermissions (or Spatie)
        // is checked here, so @can('user_edit'), middleware('can:blog_edit'), etc. work.
        Gate::before(function ($user, string $ability) {
            if (in_array($ability, ['accessAdmin', 'accessUser'], true)) {
                return null; // let the dedicated Gates above run
            }
            if ($user && Variable::hasPermission($ability)) {
                return true;
            }

            return null;
        });
    }
}
