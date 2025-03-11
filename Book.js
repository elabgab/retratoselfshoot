document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: 'get_events.php',  // Endpoint to fetch confirmed bookings
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        }
    });
    calendar.render();

    // Add click handler for confirm payment buttons
    document.querySelectorAll('.confirm-payment').forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.dataset.bookingId;
            const name = this.dataset.name;
            const date = this.dataset.date;

            if (confirm('Confirm payment for this booking?')) {
                $.ajax({
                    url: 'confirm.php',
                    method: 'POST',
                    data: { id: bookingId, name: name, date: date },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.status === 'success') {
                            // Add event to calendar
                            calendar.addEvent({
                                title: name,
                                start: date,
                                allDay: true
                            });
                            calendar.refetchEvents();
                            
                            // Update button state
                            button.innerHTML = 'Confirmed';
                            button.disabled = true;
                            button.classList.remove('btn-primary');
                            button.classList.add('btn-success');
                            
                            alert('Payment confirmed successfully!');
                        } else {
                            alert('Error confirming payment: ' + result.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while confirming payment.');
                    }
                });
            }
        });
    });
});