import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';

document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('calendar');
    if (el) {
        const calendar = new Calendar(el, {
            plugins: [dayGridPlugin],
            initialView: 'dayGridMonth',
            events: '/api/events'
        });
        calendar.render();
    }
});

