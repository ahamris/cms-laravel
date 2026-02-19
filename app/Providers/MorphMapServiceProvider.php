<?php

namespace App\Providers;

use App\Models\About;
use App\Models\ActivityLog;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\CallAction;
use App\Models\CaseStudy;
use App\Models\Changelog;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\ContentType;
use App\Models\EmailLog;
use App\Models\Event;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\FooterLink;
use App\Models\HelpArticle;
use App\Models\HeroSection;
use App\Models\Legal;
use App\Models\MailSetting;
use App\Models\MarketingEvent;
use App\Models\MarketingPersona;
use App\Models\MarketingTestimonial;
use App\Models\MegaMenuItem;
use App\Models\Module;
use App\Models\OrganizationName;
use App\Models\Page;
use App\Models\PricingBooster;
use App\Models\PricingFeature;
use App\Models\PricingPlan;
use App\Models\ProductFeature;
use App\Models\SentEmail;
use App\Models\Setting;
use App\Models\SocialSetting;
use App\Models\Solution;
use App\Models\StaticPage;
use App\Models\Subscription;
use App\Models\SubscriptionTrial;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class MorphMapServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'about' => About::class,
            'activity_log' => ActivityLog::class,
            'blog' => Blog::class,
            'blog_category' => BlogCategory::class,
            'call_action' => CallAction::class,
            'case_study' => CaseStudy::class,
            'changelog' => Changelog::class,
            'comment' => Comment::class,
            'contact' => Contact::class,
            'content_type' => ContentType::class,
            'email_log' => EmailLog::class,
            'event' => Event::class,
            'faq' => Faq::class,
            'feature' => Feature::class,
            'footer_link' => FooterLink::class,
            'help_article' => HelpArticle::class,
            'hero_section' => HeroSection::class,
            'mail_setting' => MailSetting::class,
            'marketing_event' => MarketingEvent::class,
            'marketing_persona' => MarketingPersona::class,
            'marketing_testimonial' => MarketingTestimonial::class,
            'mega_menu_item' => MegaMenuItem::class,
            'module' => Module::class,
            'organization_name' => OrganizationName::class,
            'page' => Page::class,
            'pricing_booster' => PricingBooster::class,
            'pricing_feature' => PricingFeature::class,
            'pricing_plan' => PricingPlan::class,
            'product_feature' => ProductFeature::class,
            'sent_email' => SentEmail::class,
            'setting' => Setting::class,
            'social_setting' => SocialSetting::class,
            'solution' => Solution::class,
            'subscription' => Subscription::class,
            'subscription_trial' => SubscriptionTrial::class,
            'translation' => Translation::class,
            'user' => User::class,
            'legal' => Legal::class,
            'static_page' => StaticPage::class
        ]);
    }
}
