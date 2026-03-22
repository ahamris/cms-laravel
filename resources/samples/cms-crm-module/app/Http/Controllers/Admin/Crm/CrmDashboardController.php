<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Contact;
use App\Models\ContactForm;
use Illuminate\View\View;

/**
 * CRM Dashboard Controller
 *
 * Shows the inbound marketing funnel overview:
 * Strangers → Visitors → Leads → Customers → Promoters
 */
class CrmDashboardController extends AdminBaseController
{
    public function index(): View
    {
        $stats = $this->buildStats();

        return view('admin.crm.dashboard.index', compact('stats'));
    }

    protected function buildStats(): array
    {
        return [
            // Funnel counts
            'visitors'              => 2841,   // TODO: pull from DailyStat / analytics
            'leads'                 => ContactForm::where('status', 'new')
                                        ->orWhere('status', 'contacted')
                                        ->count(),
            'customers'             => Contact::where('is_customer', true)->where('is_active', true)->count(),
            'promoters'             => 9,      // TODO: NPS-driven

            // Conversion rates
            'visitor_to_lead_rate'  => '6.5%',
            'lead_to_customer_rate' => '12.5%',
            'nps_score'             => '72 NPS',

            // Module counts
            'total_contacts'        => Contact::count(),
            'pipeline_value'        => 38400,
            'open_tickets'          => 3,      // TODO: CrmTicket model
            'unread_messages'       => ContactForm::where('status', 'new')->count(),
        ];
    }
}
