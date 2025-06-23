var calendar;
var Calendar = FullCalendar.Calendar;
var events = [];
$(function () {
  if (!!scheds) {
    Object.keys(scheds).map((k) => {
      var row = scheds[k];
      events.push({
        id: row.id,
        title: row.title,
        start: row.start_datetime,
        end: row.end_datetime,
      });
    });
  }
  var date = new Date();
  var d = date.getDate(),
    m = date.getMonth(),
    y = date.getFullYear();

  calendar = new Calendar(document.getElementById("calendar"), {
    headerToolbar: {
      left: "prev,next today",
      right: "dayGridMonth,dayGridWeek,list",
      center: "title",
    },
    selectable: true,
    themeSystem: "bootstrap",
    //Random default events
    events: events,
    eventClick: function (info) {
      var _details = $("#event-details-modal");
      var id = info.event.id;
      if (!!scheds[id]) {
        _details.find("#title").text(scheds[id].title);
        _details.find("#description").text(scheds[id].description);
        _details.find("#start").text(scheds[id].sdate);
        _details.find("#end").text(scheds[id].edate);
        _details.find("#edit,#delete").attr("data-id", id);
        
        if (scheds[id].id_meet != '') {
          _details.find("#edit,#delete").hide(); // Hide the buttons if id_meet is empty
          _details.find("#join-meet").show(); // Hide the buttons if id_meet is empty
          
          var joinMeetButton = document.getElementById("join-meet");
          joinMeetButton.onclick = function() {
            //window.location.href = "./get_meeting_link_by_sch_id.php?id="+id;
            var url = "./get_meeting_link_by_sch_id.php?id="+id;
            window.open(url, "_blank");
        };
        } else {
            _details.find("#edit,#delete").show(); // Show the buttons if id_meet is not empty
            _details.find("#join-meet").hide(); // Show the buttons if id_meet is not empty
        }

        _details.modal("show");
      } else {
        alert("Event is undefined");
      }
    },
    eventDidMount: function (info) {
      // Do Something after events mounted
    },
    editable: true,
  });

  calendar.render();

  // Form reset listener
  $("#schedule-form").on("reset", function () {
    $(this).find("input:hidden").val("");
    $(this).find("input:visible").first().focus();
  });

  // Edit Button
  $("#edit").click(function () {
    var id = $(this).attr("data-id");
    if (!!scheds[id]) {
      var _form = $("#schedule-form");
      console.log(
        String(scheds[id].start_datetime),
        String(scheds[id].start_datetime).replace(" ", "\\t")
      );
      _form.find('[name="id"]').val(id);
      _form.find('[name="title"]').val(scheds[id].title);
      _form.find('[name="description"]').val(scheds[id].description);
      _form
        .find('[name="start_datetime"]')
        .val(String(scheds[id].start_datetime).replace(" ", "T"));
      _form
        .find('[name="end_datetime"]')
        .val(String(scheds[id].end_datetime).replace(" ", "T"));
      $("#event-details-modal").modal("hide");
      _form.find('[name="title"]').focus();
    } else {
      alert("Event is undefined");
    }
  });

  // Delete Button / Deleting an Event
  $("#delete").click(function () {
    var id = $(this).attr("data-id");
    if (!!scheds[id]) {
      var _conf = confirm("Are you sure to delete this scheduled event?");
      if (_conf === true) {
        location.href = "./delete_schedule.php?id=" + id;
      }
    } else {
      alert("Event is undefined");
    }
  });
});

// Add this code to your script.js file
$(document).ready(function () {
  // Handle click event of Join Meet button
  $("#join-meet").click(function () {
    // You can add your logic to join the meeting here
    // For example, redirect the user to the meeting URL
    var eventId = $(this).data("id");
    var eventDetails = scheds[eventId]; // Assuming scheds is your array of event details
    var meetingUrl = eventDetails.meetingUrl; // Assuming you have a meeting URL stored in the event details
    if (meetingUrl) {
      window.open(meetingUrl, "_blank"); // Open meeting URL in a new tab
    } else {
      alert("No meeting URL found for this event.");
    }
  });
});
