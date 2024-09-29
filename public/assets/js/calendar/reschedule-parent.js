document.addEventListener('DOMContentLoaded', function () {
    var selectedSlots = []; // Array to store selected slots
    var calendarEl = document.getElementById('calendar');

    // Initialize the calendar
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        initialView: 'dayGridMonth',
        navLinks: true,
        selectable: true,
        nowIndicator: true,
        events: calendarSlots, // Load the slot data from the backend (calendarSlots is passed in Blade)
        eventClick: function (info) {
            var selectedSlotId = info.event.id;
            var selectedSlotDate = info.event.start; // Get the date in a readable format
            var selectedSlotStartTime = info.event.start.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false // Force 24-hour format (HH:MM)
            });
            var selectedSlotEndTime = info.event.end.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false // Force 24-hour format (HH:MM)
            });
            var selectedSlotDayIndex = info.event.start.getUTCDay(); // Get day number (0 = Sunday, 6 = Saturday)

            // Convert day index to day name
            var dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            var selectedSlotDay = dayNames[selectedSlotDayIndex];

            var formattedDate = selectedSlotDate.toISOString().split('T')[0]; // Get the date part in YYYY-MM-DD

            // Show slot details in the modal
            document.getElementById('slotDate').value = formattedDate;
            document.getElementById('slotStartTime').value = selectedSlotStartTime; // Set start time in HH:MM format
            document.getElementById('slotEndTime').value = selectedSlotEndTime;     // Set end time in HH:MM format
            document.getElementById('slotDay').value = selectedSlotDay;
            
            // Show the modal for slot confirmation
            $('#eventDetailsModal').modal('show');

            // Store the slot data temporarily in the confirm button
            $('#confirmSlotBtn').data('slot', {
                id: selectedSlotId,
                date: formattedDate,
                start_time: selectedSlotStartTime,
                end_time: selectedSlotEndTime,
                day: selectedSlotDay // Pass day in word form
            });
        },
        droppable: true, // Allows events to be dropped onto the calendar
        drop: function (arg) {
            // Remove the event after drop if checkbox is checked
            if (document.getElementById('drop-remove').checked) {
                arg.draggedEl.parentNode.removeChild(arg.draggedEl);
            }
        }
    });

    calendar.render();

    // Handle confirm button in modal
    $('#confirmSlotBtn').on('click', function () {
        var slotData = $(this).data('slot');

        // Add the slot to the selected slots array
        selectedSlots.push({
            id: slotData.id,
            date: slotData.date,
            start_time: slotData.start_time,
            end_time: slotData.end_time,
            day: slotData.day // Store day as word form (Sunday, Monday, etc.)
        });
    });
});
