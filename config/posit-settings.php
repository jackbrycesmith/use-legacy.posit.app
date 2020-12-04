<?php

return [
    'public_proposal_access_cookie_expiry_days' => env('PUBLIC_PROPOSAL_ACCESS_COOKIE_EXPIRY_DAYS', 1),
    'org_contact_access_code_length' => env('POSIT_ORG_CONTACT_ACCESS_CODE_LENGTH', 16),

    'proposal' => [
        'name_default' => env('POSIT_PROPOSAL_NAME_DEFAULT', 'Proposal'),
        'value_currency_system_default' => env('POSIT_PROPOSAL_VALUE_CURRENCY_SYSTEM_DEFAULT', 'GBP'),
        'value_max_digits' => 9, // e.g. proposal value can be stored up to (e.g. £999,999,999)
        'value_digits_round' => 4, // e.g. proposal value can be stored to up to a 4 decimal place precision (e.g. £9.2323)
        'theme_default' => env('POSIT_PROPOSAL_THEME_DEFAULT', \App\Models\Posit::THEME_COOL_GREY)
    ]
];
