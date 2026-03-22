<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Contact;
use App\Models\CrmAppointment;
use App\Models\CrmDeal;
use App\Models\CrmNote;
use App\Models\CrmTicket;
use App\Models\ContactForm;
use Illuminate\View\View;

class CrmDashboardController extends AdminBaseController
{
    public function index(): View
    {
        $funnelStages = [
            'interesseer' => ['label' => 'Attract', 'count' => Contact::where('funnel_fase', 'interesseer')->count()],
            'overtuig'    => ['label' => 'Convert', 'count' => Contact::where('funnel_fase', 'overtuig')->count()],
            'activeer'    => ['label' => 'Close', 'count' => Contact::where('funnel_fase', 'activeer')->count()],
            'inspireer'   => ['label' => 'Delight', 'count' => Contact::where('funnel_fase', 'inspireer')->count()],
        ];

        $stats = [
            'total_contacts'    => Contact::count(),
            'total_deals'       => CrmDeal::count(),
            'open_deals'        => CrmDeal::open()->count(),
            'pipeline_value'    => CrmDeal::open()->sum('value'),
            'won_deals'         => CrmDeal::won()->count(),
            'won_value'         => CrmDeal::won()->sum('value'),
            'open_tickets'      => CrmTicket::open()->count(),
            'unread_messages'   => ContactForm::where('status', 'new')->count(),
            'upcoming_appointments' => CrmAppointment::upcoming()->count(),
        ];

        $recentActivity = CrmNote::with(['user', 'contact'])
            ->latest()
            ->take(10)
            ->get();

        $upcomingAppointments = CrmAppointment::upcoming()
            ->with(['contact', 'assignedTo'])
            ->take(5)
            ->get();

        $dealsByStage = [];
        foreach (['lead', 'qualified', 'proposal', 'negotiation'] as $stage) {
            $dealsByStage[$stage] = CrmDeal::byStage($stage)->count();
        }

        return view('admin.crm.dashboard.index', compact(
            'funnelStages', 'stats', 'recentActivity', 'upcomingAppointments', 'dealsByStage'
        ));
    }
}
