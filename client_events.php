<?php

require_once "inc_all_client.php";


if (isset($_GET['calendar_id'])) {
    $calendar_selected_id = intval($_GET['calendar_id']);
}

?>

<link href='plugins/fullcalendar-6.1.10/dist/index.global.js' rel='stylesheet' />

<!-- So that when hovering over a created event it turns into a hand instead of cursor -->
<style>
    .fc-event {
        cursor: pointer;
    }
</style>

<div class="card">
    <div id='calendar'></div>
</div>

<?php
include "calendar_event_add_modal.php";

include "calendar_add_modal.php";


//loop through IDs and create a modal for each
$sql = mysqli_query($mysqli, "SELECT * FROM calendars LEFT JOIN events ON calendar_id = event_calendar_id WHERE event_client_id = $client_id");
while ($row = mysqli_fetch_array($sql)) {
    $event_id = intval($row['event_id']);
    $event_title = nullable_htmlentities($row['event_title']);
    $event_description = nullable_htmlentities($row['event_description']);
    $event_start = nullable_htmlentities($row['event_start']);
    $event_end = nullable_htmlentities($row['event_end']);
    $event_repeat = nullable_htmlentities($row['event_repeat']);
    $calendar_id = intval($row['calendar_id']);
    $calendar_name = nullable_htmlentities($row['calendar_name']);
    $calendar_color = nullable_htmlentities($row['calendar_color']);

    require "calendar_event_edit_modal.php";


}

?>

<script src='plugins/fullcalendar-6.1.10/dist/index.global.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            themeSystem: 'bootstrap',
            defaultView: 'dayGridMonth',
            customButtons: {
                addEvent: {
                    text: 'Add Event',
                    bootstrapFontAwesome: 'fa fa-plus',
                    click: function() {
                        $("#addCalendarEventModal").modal();
                    }
                },
                addCalendar: {
                    text: 'Add Calendar',
                    bootstrapFontAwesome: 'fa fa-calendar-plus',
                    click: function() {
                        $("#addCalendarModal").modal();
                    }
                }
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth addEvent addCalendar'
            },
            <?php if (!$session_mobile) {
            ?>aspectRatio: 2.5,
        <?php } else { ?>
            aspectRatio: 0.7,
        <?php } ?>
        navLinks: true, // can click day/week names to navigate views
        selectable: true,
        height: '90vh',

        selectMirror: true,
        eventClick: function(editEvent) {
            $('#editEventModal' + editEvent.event.id).modal();
        },
        dayMaxEvents: true, // allow "more" link when too many events
        views: {
            timeGrid: {
                dayMaxEventRows: 3, // adjust to 6 only for timeGridWeek/timeGridDay
                expandRows: true,
                nowIndicator: true,
                eventMaxStack: 1,
            },
            dayGrid: {
                dayMaxEvents: 3, // adjust to 6 only for timeGridWeek/timeGridDay
                expandRows: true,
            },

        },
            events: [
                <?php
                $sql = mysqli_query($mysqli, "SELECT * FROM calendars LEFT JOIN events ON calendar_id = event_calendar_id WHERE event_client_id = $client_id");
                while ($row = mysqli_fetch_array($sql)) {
                    $event_id = intval($row['event_id']);
                    $event_title = json_encode($row['event_title']);
                    $event_start = json_encode($row['event_start']);
                    $event_end = json_encode($row['event_end']);
                    $calendar_id = intval($row['calendar_id']);
                    $calendar_name = json_encode($row['calendar_name']);
                    $calendar_color = json_encode($row['calendar_color']);

                    echo "{ id: $event_id, title: $event_title, start: $event_start, end: $event_end, color: $calendar_color },";
                }

                //Invoices Created
                $sql = mysqli_query($mysqli, "SELECT * FROM clients LEFT JOIN invoices ON client_id = invoice_client_id WHERE client_id = $client_id");
                while ($row = mysqli_fetch_array($sql)) {
                    $event_id = intval($row['invoice_id']);
                    $scope = strval($row['invoice_scope']);
                    if (empty($scope)) {
                        $scope = "Not Set";
                    }
                    $event_title = json_encode($row['invoice_prefix'] . $row['invoice_number'] . " created -scope: " . $scope);
                    $event_start = json_encode($row['invoice_date']);


                    echo "{ id: $event_id, title: $event_title, start: $event_start, display: 'list-item', color: 'blue', url: 'invoice.php?invoice_id=$event_id' },";
                }

                //Quotes Created
                $sql = mysqli_query($mysqli, "SELECT * FROM clients LEFT JOIN quotes ON client_id = quote_client_id WHERE client_id = $client_id");
                while ($row = mysqli_fetch_array($sql)) {
                    $event_id = intval($row['quote_id']);
                    $event_title = json_encode($row['quote_prefix'] . $row['quote_number'] . " " . $row['quote_scope']);
                    $event_start = json_encode($row['quote_date']);

                    echo "{ id: $event_id, title: $event_title, start: $event_start, display: 'list-item', color: 'purple', url: 'quote.php?quote_id=$event_id' },";
                }

                //Tickets Created
                $sql = mysqli_query($mysqli, "SELECT * FROM clients LEFT JOIN tickets ON client_id = ticket_client_id LEFT JOIN users ON ticket_assigned_to = user_id WHERE client_id = $client_id");
                while ($row = mysqli_fetch_array($sql)) {
                    $event_id = intval($row['ticket_id']);
                    $ticket_status = strval($row['ticket_status']);
                    $username = $row['user_name'];
                    if (empty($username)) {
                        $username = "";
                    } else {
                        //Limit to  characters and add ...
                        $username = "[". substr($row['user_name'], 0, 9) . "...]";
                    }

                    $event_title = json_encode($row['ticket_prefix'] . $row['ticket_number'] . " created - " . $row['ticket_subject'] . " " . $username . "{" . $ticket_status . "}");
                    $event_start = json_encode($row['ticket_created_at']);


                    if ($ticket_status == "Closed") {
                        $event_color = "black";
                    } else if ($ticket_status == "Scheduled") {
                        $event_color = "grey";
                    } else if ($ticket_status == "Pending-Assignment") {
                        $event_color = "red";
                    } else {
                        $event_color = "blue";
                    }

                    echo "{ id: $event_id, title: $event_title, start: $event_start, color: '$event_color', url: 'ticket.php?ticket_id=$event_id' },";
                }

                //Tickets Scheduled
                $sql = mysqli_query($mysqli, "SELECT * FROM clients LEFT JOIN tickets ON client_id = ticket_client_id LEFT JOIN users ON ticket_assigned_to = user_id WHERE ticket_schedule IS NOT NULL AND client_id = $client_id");
                while ($row = mysqli_fetch_array($sql)) {
                    $event_id = intval($row['ticket_id']);
                    $username = $row['user_name'];
                    if (empty($username)) {
                        $username = "";
                    } else {
                        //Limit to  characters and add ...
                        $username = substr($row['user_name'], 0, 9) . "...";
                    }

                    if (strtotime($row['ticket_schedule']) < time()) {
                        if ($row['ticket_status'] == 'Scheduled') {
                            $event_color = "red";
                        } else {
                            $event_color = "green";
                        }
                    } else {
                        $event_color = "grey";
                    }

                    $ticket_status = strval($row['ticket_status']);
                    $event_title = json_encode($row['ticket_prefix'] . $row['ticket_number'] . " scheduled - " . $row['ticket_subject'] . " [" . $username . "]{" . $ticket_status . "}");
                    $event_start = json_encode($row['ticket_schedule']);
                    

                    echo "{ id: $event_id, title: $event_title, start: $event_start, color: '$event_color', url: 'ticket.php?ticket_id=$event_id' },";
                }
                ?>
            ],
            eventClick: function(editEvent) {
                $('#editEventModal'+editEvent.event.id).modal();
            },
            eventOrder: 'allDay,start,-duration,title',

        });

        calendar.render();
    });

</script>

<!-- Automatically set new event end date to 1 hr after start date -->
<script>
    // Function - called when user leaves field (onblur)
    function updateIncrementEndTime() {

        // Get the start date
        let start = document.getElementById("event_add_start").value;

        // Create a date object
        let new_end = new Date(start);

        // Get the time zone offset in minutes, convert it to milliseconds
        let offsetInMilliseconds = new_end.getTimezoneOffset() * 60 * 1000;

        // Adjust the date by the time zone offset before adding an hour
        new_end = new Date(new_end.getTime() - offsetInMilliseconds);

        // Set the end date to 1 hr in the future
        new_end.setHours(new_end.getHours() + 1);

        // Get the date back as a string, with the milliseconds trimmed off
        new_end = new_end.toISOString().replace(/.\d+Z$/g, "");

        // Update the end date field
        document.getElementById("event_add_end").value = new_end;
    }
</script>

<?php
require "footer.php";

