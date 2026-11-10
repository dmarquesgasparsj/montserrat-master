<?php

return [
    'enabled' => env('SOUTELO_PILOT', false),
    'admin_email' => env('SOUTELO_PILOT_EMAIL'),
    'admin_password' => env('SOUTELO_PILOT_PASSWORD'),
    'admin_totp_secret' => env('SOUTELO_PILOT_TOTP_SECRET'),
    'trusted_proxy' => env('SOUTELO_PILOT_TRUSTED_PROXY'),

    'reception_routes' => [
        'calendar', 'api.events', 'rooms', 'rooms.move-reservation', 'rooms.create-reservation',
        'room.index', 'room.show', 'pilot.room-location', 'person.index', 'person.create', 'person.store', 'person.show',
        'person.edit', 'person.update', 'pilot.search-contacts', 'results',
        'pilot.contact-autocomplete', 'pilot.contact-select', 'get_avatar',
        'retreat.index', 'retreat.show', 'retreat.create', 'retreat.store', 'retreat.edit',
        'retreat.update', 'retreat.status', 'retreat.assign_rooms', 'retreat.room_update',
        'retreats.search', 'retreats.results', 'get_event_by_id_number', 'event_room_list',
        'registration.index', 'registration.create', 'registration.store', 'registration.show',
        'registration.edit', 'registration.update', 'registration.register',
    ],

    'reception_permissions' => [
        'show-room', 'show-retreat', 'create-retreat', 'update-retreat',
        'show-contact', 'create-contact', 'update-contact', 'show-avatar',
        'show-registration', 'create-registration', 'update-registration',
    ],

    'admin_routes' => [
        'room.create', 'room.store', 'room.edit', 'room.update',
        'location.index', 'location.show', 'location.create', 'location.store',
        'location.edit', 'location.update', 'report.meal_summary', 'pilot.reservation-export',
    ],
];
