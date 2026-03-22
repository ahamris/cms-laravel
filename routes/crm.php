<?php

use App\Http\Controllers\Admin\Crm\CrmDashboardController;
use App\Http\Controllers\Admin\Crm\CrmContactController;
use App\Http\Controllers\Admin\Crm\CrmDealController;
use App\Http\Controllers\Admin\Crm\CrmTicketController;
use App\Http\Controllers\Admin\Crm\CrmMessageController;
use App\Http\Controllers\Admin\Crm\CrmAppointmentController;
use App\Http\Controllers\Admin\Crm\CrmNoteController;
use Illuminate\Support\Facades\Route;

Route::prefix('crm')->name('crm.')->group(function () {

    // Dashboard
    Route::get('/', [CrmDashboardController::class, 'index'])->name('dashboard');

    // Contacts
    Route::resource('contacts', CrmContactController::class);
    Route::post('contacts/{contact}/toggle-active', [CrmContactController::class, 'toggleActive'])->name('contacts.toggle-active');
    Route::get('contacts/{contact}/timeline', [CrmContactController::class, 'timeline'])->name('contacts.timeline');
    Route::put('contacts/{contact}/funnel', [CrmContactController::class, 'updateFunnel'])->name('contacts.update-funnel');

    // Deals
    Route::resource('deals', CrmDealController::class);
    Route::post('deals/{deal}/move-stage', [CrmDealController::class, 'moveStage'])->name('deals.move-stage');
    Route::post('deals/{deal}/mark-won', [CrmDealController::class, 'markWon'])->name('deals.mark-won');
    Route::post('deals/{deal}/mark-lost', [CrmDealController::class, 'markLost'])->name('deals.mark-lost');

    // Tickets
    Route::resource('tickets', CrmTicketController::class);
    Route::post('tickets/{ticket}/reply', [CrmTicketController::class, 'reply'])->name('tickets.reply');
    Route::post('tickets/{ticket}/ai-reply', [CrmTicketController::class, 'aiReply'])->name('tickets.ai-reply');
    Route::post('tickets/{ticket}/assign', [CrmTicketController::class, 'assign'])->name('tickets.assign');
    Route::post('tickets/{ticket}/change-status', [CrmTicketController::class, 'changeStatus'])->name('tickets.change-status');

    // Messages (contact form inbox)
    Route::resource('messages', CrmMessageController::class)->only(['index', 'show', 'destroy']);
    Route::post('messages/{message}/reply', [CrmMessageController::class, 'reply'])->name('messages.reply');
    Route::post('messages/{message}/ai-reply', [CrmMessageController::class, 'aiReply'])->name('messages.ai-reply');
    Route::post('messages/{message}/resolve', [CrmMessageController::class, 'resolve'])->name('messages.resolve');
    Route::post('messages/{message}/to-ticket', [CrmMessageController::class, 'convertToTicket'])->name('messages.to-ticket');
    Route::post('messages/{message}/to-deal', [CrmMessageController::class, 'convertToDeal'])->name('messages.to-deal');

    // Appointments
    Route::resource('appointments', CrmAppointmentController::class);
    Route::post('appointments/{appointment}/complete', [CrmAppointmentController::class, 'complete'])->name('appointments.complete');

    // Notes
    Route::resource('notes', CrmNoteController::class)->except(['show', 'create', 'edit']);
    Route::post('notes/{note}/toggle-pin', [CrmNoteController::class, 'togglePin'])->name('notes.toggle-pin');
    Route::get('contacts/{contact}/notes', [CrmNoteController::class, 'forContact'])->name('notes.for-contact');
});
