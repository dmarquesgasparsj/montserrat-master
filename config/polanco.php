<?php

return [

    /*
     * Site administrator name and email for receiving notifications
     */

    'activity_contacts_type' => [
        'assignee' => '1',
        'creator' => '2',
        'target' => '3',
    ],

    'activity_type' => [
        'meeting' => '1',
        'call' => '2',
        'email' => '3',
        'text' => '4',
        'letter' => '5',
        'other' => '8',
    ],

    'admin_name' => 'Anthony Borrow',
    'admin_email' => 'anthony.borrow@montserratretreat.org',

    // list of donation_descriptions considered to be part of annual giving campaign
    'agc_cool_colors' => [
        0 => '51,105,232',
        1 => '255, 205, 86',
        2 => '255, 99, 132',
        3 => '244,67,54',
        4 => '211,159,153',
        5 => '205,211,153',
        6 => '229,229,201',
        7 => '90,174,174',
        8 => '147,131,107',
    ],

    'chart_colors' => [
        0 => '230, 25, 75', // red
        1 => '245, 130, 48', // orange
        2 => '255, 225, 25', // yellow
        3 => '60, 180, 75', // green
        4 => '0, 130, 200', // blue
        5 => '145,30,180', // purple
        6 => '250,190,212', // pink (light red)
        7 => '255,215,180', // apricot (light orange)
        8 => '255,250,200',  // beige (light yellow)
        9 => '170,255,195', //mint (light green)
        10 => '70, 240, 240',  // cyan (light blue)
        11 => '220,190,255', // lavender (light purple)
        12 => '128,0,0', //maroon (dark red)
        13 => '170,110,40', // brown (dark orange)
        14 => '128, 128, 0', // olive (dark yellow)
        15 => '0,128,128', // teal (dark green)
        16 => '0,0,128', // navy (dark blue)
        17 => '240, 50, 230', // magenta (dark purple)
        18 => '210, 245, 60', // lime
        19 => '128, 128, 128', // gray
    ],

    'agc_donation_descriptions' => [
        'AGC - General',
        'AGC - Endowment',
        'AGC - Scholarships',
        'AGC - Buildings & Maintenance',
    ],

    'asset_task_frequency' => [
        'daily' => 'daily',
        'weekly' => 'weekly',
        'monthly' => 'monthly',
        'yearly' => 'yearly',
    ],

    'asset_job_status' => [
        'Scheduled' => 'Scheduled',
        'Nonscheduled' => 'Nonscheduled',
        'Completed' => 'Completed',
        'Canceled' => 'Canceled',
    ],

    'finance_email' => 'finance@montserratretreat.org',
    'notify_registration_event_change' => '1',

    'country_id_usa' => '1228',
    'state_province_id_tx' => '1042',

    'contact_type' => [
        'individual' => '1',
        'household' => '2',
        'organization' => '3',
        'parish' => '4',
        'diocese' => '5',
        'province' => '6',
        'community' => '7',
        'retreat_house' => '8',
        'vendor' => '9',
        'religious_catholic' => '10',
        'religious_noncatholic' => '11',
        'contract' => '12',
        'foundation' => '13',
    ],

    'contact' => [
        'montserrat' => '620',
        'stripe' => env('STRIPE_VENDOR_ID', 2),
    ],

    'donation_descriptions' => [
        'N/A' => '',
        'Annual Giving Campaign' => 'AGC - General',
        'Bookstore' => 'Bookstore Revenue',
        'Building & Maintenance Fund' => 'AGC - Buildings & Maintenance',
        'Flowers and Landscaping' => 'Flowers and Landscaping',
        'Memorial Donation' => 'Memorials',
        'Montserrat Foundation Endowment' => 'AGC - Endowment',
        'Post-Retreat offering' => 'Retreat Funding',
        'Pre-Retreat offering' => 'Retreat Deposits',
        "Pay for Another Person's Retreat" => 'AGC - Scholarships',
        'Saturday of Renewal' => 'Saturday of Renewal Funding',
        'Tips' => 'Tips',
    ],

    'stripe_balance_transaction_types' => [
        'Annual Giving Fund' => 'AGC - General',
        'Bookstore' => 'Bookstore Revenue',
        'Bookstore + Tips' => ['Bookstore Revenue', 'Tips'],
        'Bookstore + Tips + Flowers' => ['Bookstore Revenue', 'Flowers and Landscaping', 'Tips'],
        'Building and Maintenance Fund' => 'AGC - Buildings & Maintenance',
        'Donacion de Retiro' => 'Retreat Funding',
        'Donation' => 'Donations',
        'Flowers' => 'Flowers and Landscaping',
        'Foundation Endowment' => 'AGC - Endowment',
        'Gift Certificate' => 'Gift Certificates - Funded',
        'Propina' => 'Tips',
        'Retreat Deposit' => 'Retreat Deposits',
        'Retreat Offering' => 'Retreat Funding',
        'Retreat Offering + Bookstore' => ['Bookstore Revenue', 'Retreat Funding'],
        'Retreat Offering + Bookstore + Tips' => ['Bookstore Revenue', 'Retreat Funding', 'Tips'],
        'Retreat Offering + Bookstore + Tips + Flowers' => ['Bookstore Revenue', 'Flowers and Landscaping', 'Retreat Funding', 'Tips'],
        'Saturday of Reflection' => 'Retreat Funding',
        'Tiendita' => 'Bookstore Revenue',
        'Tips' => 'Tips',
    ],

    // when creating database with the seeder, the first event created is the open deposit event
    'event' => [
        'open_deposit' => env('OPEN_DEPOSIT_EVENT_ID', 1),
    ],

    'event_type' => [
        'conference' => '1',
        'exhibition' => '2',
        'fundraiser' => '3',
        'meeting' => '4',
        'performance' => '5',
        'workshop' => '6',
        'ignatian' => '7',
        'diocesan' => '8',
        'other' => '9',
        'day' => '10',
        'contract' => '11',
        'directed' => '12',
        'isi' => '13',
        'jesuit' => '14',
        'saturday' => '15',
    ],

    /*
    * Export list types to fill export_list.type field
    */

    'export_list_types' => [
        'Email' => 'Email',
        'Mailing' => 'Mailing',
        'Other' => 'Other',
    ],

    'file_type' => [
        'contact_attachment' => '1',
        'event_schedule' => '2',
        'event_evaluation' => '3',
        'event_contract' => '4',
        'event_group_photo' => '5',
        'contact_avatar' => '6',
        'event_attachment' => '7',
        'signature' => '8',
        'asset_photo' => '9',
        'asset_attachment' => '10',
    ],

    'group_id' => [
        'innkeeper' => '1',
        'director' => '2',
        'assistant' => '3',
        'bishop' => '4',
        'priest' => '5',
        'deacon' => '6',
        'jesuit' => '7',
        'provincial' => '8',
        'superior' => '9',
        'pastor' => '10',
        'ambassador' => '11',
        'board' => '12',
        'staff' => '13',
        'volunteer' => '14',
        'steward' => '15',
        'runner' => '16',
        'hlm2017' => '17',
    ],

    // communication locations (phone, email, address, etc.)
    'location_type' => [
        'home' => '1',
        'work' => '2',
        'main' => '3',
        'other' => '4',
        'billing' => '5',
    ],
    // physical types of locations
    'locations_type' => [
        'Site' => 'Site',
        'Grounds' => 'Grounds',
        'Building' => 'Building',
        'Floor' => 'Floor',
        'Room' => 'Room',
        'Closet' => 'Closet',
        'Other' => 'Other',
        'Dining Room' => 'Dining Room',
        'Chapel' => 'Chapel',
        'Conference Room' => 'Conference Room',

        // DG Adicões que facilitam a vida
    ],

    'medium' => [
        'in person' => '1',
        'phone' => '2',
        'email' => '3',
        'fax' => '4',
        'letter' => '5',
    ],

    'operators' => [
        '<' => '<',
        '<=' => '<=',
        '=' => '=',
        '>=' => '>=',
        '>' => '>',
    ],

    'participant_role_id' => [
        'ambassador' => '11',
        'retreatant' => '5',
        'director' => '8',
        'innkeeper' => '9',
        'assistant' => '10',
    ],

    'payment_method' => [
        'Unassigned' => 'Unassigned',
        'Cash' => 'Cash',
        'Check' => 'Check',
        'Credit card' => 'Credit card',
        'Gift cert funded' => 'Gift cert funded',
        'Gift cert unfunded' => 'Gift cert unfunded',
        'Journal' => 'Journal',
        'NSF' => 'NSF',
        'Reallocation' => 'Reallocation',
        'Refund' => 'Refund',
        'Other' => 'Other',
        'Wire transfer' => 'Wire transfer',
    ],

    'preferred_communication_method' => [
        '0' => 'N/A',
        '1' => 'Phone',
        '2' => 'Email',
        '3' => 'Postal Mail',
        '4' => 'SMS',
    ],

    'priority' => [
        'emergency' => '0',
        'urgent' => '1',
        'serious' => '2',
        'normal' => '3',
        'low' => '4',
    ],

    'registration_filters' => [
        'active',
        'canceled',
        'confirmed',
        'unconfirmed',
        'arrived',
        'departed',
        'retreatant',
        'dawdler',
    ],

    'registration_status_id' => [
        'registered' => '1',
        'no_show' => '3',
        'canceled' => '4',
        'waitlist' => '7',
        'nonparticipaitng' => '17',

    ],

    'registration_source' => [
        'N/A' => 'N/A',
        'Squarespace' => 'Squarespace',
        'Phone' => 'Phone',
        'Email' => 'Email',
        'In person' => 'In person',
        'Postal Mail' => 'Postal Mail',
    ],

    'relationship_type' => [
        'child_parent' => '1',
        'husband_wife' => '2',
        'sibling' => '4',
        'staff' => '5',
        'volunteer' => '6',
        'parishioner' => '11',
        'bishop' => '12',
        'diocese' => '13',
        'pastor' => '14',
        'superior' => '15',
        'provincial' => '16',
        'community_member' => '17',
        'board_member' => '18',
        'retreat_director' => '19',
        'retreat_assistant' => '20',
        'retreat_innkeeper' => '21',
        'retreatant' => '22',
        'donor' => '23',
        'ambassador' => '24',
        'priest' => '25',
        'deacon' => '26',
        'community' => '27',
        'primary_contact' => '28',
    ],

    'rooms' => [
        'max_floors' => '2',
    ],

    'touchpoint_source' => [
        'Call' => 'Call',
        'Email' => 'Email',
        'Face' => 'Face to Face',
        'Letter' => 'Letter',
        'Other' => 'Other',
    ],

    // Adicionar possivelmente mais linguas
    // To do - por o Set Locale dependendo do que for escolhido ao instalar
    'locale' => [
        'pt' => 'Portuguese',
        'en' => 'English',
        'es' => 'Spanish',
    ],

    // when using the database seeder the first contact created is the self organization
    // name is used by database seeder to create self contact record
    'self' => [
        'id' => env('SELF_CONTACT_ID', 620),
        'name' => env('SELF_NAME', 'Montserrat Jesuit Retreat House'),
    ],

    /*
     *  Polanco's name
     *  TODO: rename self.name and self.id to site.id and use site.variable consistently throughout Polanco
     */

    'site_name' => 'Polanco',

    /*
     *  Polanco's email address for sending notifications
     */

    'site_email' => 'polanco@montserratretreat.org',

    /*
     * Restrict socialite authentication to a particular domain
     */
    'socialite_domain_restriction' => 'montserratretreat.org',

    /*
    * Unit of measurement types to fill uom.type enum field
     */
    'uom_types' => [
        'Area' => 'Area',
        'Electric current' => 'Electric current',
        'Length' => 'Length',
        'Luminosity' => 'Luminosity',
        'Mass' => 'Mass',
        'Temperature' => 'Temperature',
        'Time' => 'Time',
        'Volume' => 'Volume',
    ],

    'website_types' => [
        'Personal' => 'Personal',
        'Work' => 'Work',
        'Main' => 'Main',
        'Facebook' => 'Facebook',
        'Google' => 'Google',
        'Instagram' => 'Instagram',
        'LinkedIn' => 'LinkedIn',
        'MySpace' => 'MySpace',
        'Other' => 'Other',
        'Pinterest' => 'Pinterest',
        'SnapChat' => 'SnapChat',
        'Tumblr' => 'Tumblr',
        'Twitter' => 'Twitter',
        'Vine' => 'Vine',
    ],

];
