<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Perfex CRM API
    |--------------------------------------------------------------------------
    | Used by PerfexCrmService to send contact form and demo requests as leads.
    | Requires the REST API module in Perfex (Setup → Modules) and an API key
    | from the API Manager. Header: X-API-KEY (e.g. pk_xxx).
    */

    'enabled' => env('PERFEX_CRM_ENABLED', false),

    'base_url' => rtrim(env('PERFEX_CRM_BASE_URL', 'https://office.code-labs.nl'), '/'),

    'api_key' => env('PERFEX_CRM_API_KEY', ''),

    /*
    | Lead defaults (optional; Perfex may use its own defaults if omitted)
    | - source: lead source name or ID (e.g. "website", "contact_form", "demo")
    | - status: lead status ID (e.g. 1 = new)
    | - assigned: staff ID to assign the lead to (null = no assignment)
    */
    'default_lead_source' => env('PERFEX_CRM_LEAD_SOURCE', 'website'),
    'default_lead_status' => (int) env('PERFEX_CRM_LEAD_STATUS', 1),
    'default_assigned' => env('PERFEX_CRM_ASSIGNED_STAFF_ID') ? (int) env('PERFEX_CRM_ASSIGNED_STAFF_ID') : null,

];
