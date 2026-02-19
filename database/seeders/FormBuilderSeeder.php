<?php

namespace Database\Seeders;

use App\Models\FormBuilder;
use Illuminate\Database\Seeder;

class FormBuilderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (FormBuilder::count() > 0) {
            return;
        }
        
        FormBuilder::create([
            'title' => 'Contact Form',
            'description' => 'Standard contact form for general inquiries',
            'identifier' => 'contact_form',
            'fields' => [
                [
                    'id' => 1,
                    'type' => 'text',
                    'label' => 'Name',
                    'name' => 'name',
                    'placeholder' => 'Enter your full name',
                    'required' => true,
                    'options' => [],
                ],
                [
                    'id' => 2,
                    'type' => 'email',
                    'label' => 'Email',
                    'name' => 'email',
                    'placeholder' => 'Enter your email address',
                    'required' => true,
                    'options' => [],
                ],
                [
                    'id' => 3,
                    'type' => 'tel',
                    'label' => 'Phone',
                    'name' => 'phone',
                    'placeholder' => 'Enter your phone number',
                    'required' => false,
                    'options' => [],
                ],
                [
                    'id' => 4,
                    'type' => 'text',
                    'label' => 'Company',
                    'name' => 'company',
                    'placeholder' => 'Enter your company name',
                    'required' => false,
                    'options' => [],
                ],
                [
                    'id' => 5,
                    'type' => 'select',
                    'label' => 'Subject',
                    'name' => 'subject',
                    'placeholder' => 'Select a subject',
                    'required' => true,
                    'options' => [
                        'General Inquiry',
                        'Sales Question',
                        'Technical Support',
                        'Partnership',
                        'Other',
                    ],
                ],
                [
                    'id' => 6,
                    'type' => 'textarea',
                    'label' => 'Message',
                    'name' => 'message',
                    'placeholder' => 'Enter your message',
                    'required' => true,
                    'options' => [],
                ],
            ],
            'settings' => [
                'show_labels' => true,
                'field_spacing' => 'normal',
                'button_style' => 'primary',
            ],
            'success_message' => 'Thank you for contacting us! We will get back to you as soon as possible.',
            'redirect_url' => null,
            'send_email_notification' => true,
            'notification_emails' => 'admin@example.com',
            'submit_button_text' => 'Send Message',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->command->info('✅ Contact form seeded successfully!');

        // Create Newsletter Form
       
        FormBuilder::create([
                'title' => 'Newsletter Signup',
                'description' => 'Newsletter subscription form',
                'identifier' => 'newsletter_form',
                'fields' => [
                    [
                        'id' => 1,
                        'type' => 'text',
                        'label' => 'First Name',
                        'name' => 'first_name',
                        'placeholder' => 'Enter your first name',
                        'required' => false,
                        'options' => [],
                    ],
                    [
                        'id' => 2,
                        'type' => 'text',
                        'label' => 'Last Name',
                        'name' => 'last_name',
                        'placeholder' => 'Enter your last name',
                        'required' => false,
                        'options' => [],
                    ],
                    [
                        'id' => 3,
                        'type' => 'email',
                        'label' => 'Email',
                        'name' => 'email',
                        'placeholder' => 'Enter your email address',
                        'required' => true,
                        'options' => [],
                    ],
                    [
                        'id' => 4,
                        'type' => 'checkbox',
                        'label' => 'I agree to receive newsletter emails',
                        'name' => 'newsletter_consent',
                        'placeholder' => '',
                        'required' => true,
                        'options' => [],
                    ],
                ],
                'settings' => [
                    'show_labels' => true,
                    'field_spacing' => 'compact',
                    'button_style' => 'primary',
                ],
                'success_message' => 'Thank you for subscribing to our newsletter!',
                'redirect_url' => null,
                'send_email_notification' => true,
                'notification_emails' => 'admin@example.com',
                'submit_button_text' => 'Subscribe',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $this->command->info('✅ Newsletter form seeded successfully!');
    }
}
