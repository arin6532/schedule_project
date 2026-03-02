<?php require('menu_Navbar_user.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome CSS from CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- FullCalendar Core CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.2.0/main.min.css" rel="stylesheet">
    <!-- FullCalendar DayGrid CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.3.0/main.min.css" rel="stylesheet">
    <link rel="stylesheet" href="calendar/calendar.css">
    <title>Document</title>
</head>

<body>
<div style="height: 150px;"></div>
<div id='calendar'></div>

<!-- Add modal -->

<div class="modal fade edit-form" id="form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="modal-title">Add Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="myForm" action="save_event.php" method="POST">
                <div class="modal-body">
                    <div class="alert alert-danger " role="alert" id="danger-alert" style="display: none;">
                        End date should be greater than start date.
                      </div>
                    <div class="form-group">
                        <label for="event-title">Event name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="calendar_name" id="event-title" placeholder="Enter event name" required>
                    </div>
                    <div class="form-group">
                        <label for="start-date">Start date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="calendar_start_date" id="start-date" placeholder="start-date" required>
                    </div>
                    <div class="form-group">
                        <label for="end-date">End date - <small class="text-muted">Optional</small></label>
                        <input type="date" class="form-control" name="calendar_end_date" id="end-date" placeholder="end-date">
                    </div>
                    <div class="form-group">
                        <label for="event-color">Color</label>
                        <input type="color" class="form-control" id="event-color" value="#3788d8">
                      </div>
                </div>
                <div class="modal-footer border-top-0 d-flex justify-content-center">
                    <button type="submit" class="btn btn-success" id="submit-button">Submit</button>
                  </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="delete-modal-title">Confirm Deletion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center" id="delete-modal-body">
          Are you sure you want to delete the event?
        </div>
        <div class="modal-footer border-0">
            <button type="button" class="btn btn-secondary rounded-sm" data-dismiss="modal" id="cancel-button">Cancel</button>
          <button type="button" class="btn btn-danger rounded-lg" id="delete-button">Delete</button>
        </div>
      </div>
    </div>
  </div>
  <script>
document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');
  const myModal = new bootstrap.Modal(document.getElementById('form'));
  const dangerAlert = document.getElementById('danger-alert');
  const close = document.querySelector('.btn-close');

  const myEvents = JSON.parse(localStorage.getItem('events')) || [];

  const calendar = new FullCalendar.Calendar(calendarEl, {
    customButtons: {
      customButton: {
        text: 'Add Event',
        click: function () {
          myModal.show();
          const modalTitle = document.getElementById('modal-title');
          const submitButton = document.getElementById('submit-button');
          modalTitle.innerHTML = 'Add Event'
          submitButton.innerHTML = 'Add Event'
          submitButton.classList.remove('btn-primary');
          submitButton.classList.add('btn-success');

          close.addEventListener('click', () => {
            myModal.hide()
          });
        }
      }
    },
    header: {
      center: 'customButton',
      right: 'today, prev,next '
    },
    plugins: ['dayGrid', 'interaction'],
    allDay: false,
    editable: true,
    selectable: true,
    unselectAuto: false,
    displayEventTime: false,
    events: myEvents,
    eventRender: function (info) {
      info.el.addEventListener('contextmenu', function (e) {
        e.preventDefault();
        let existingMenu = document.querySelector('.context-menu');
        existingMenu && existingMenu.remove();
        let menu = document.createElement('div');
        menu.className = 'context-menu';
        menu.innerHTML = `<ul>
          <li><i class="fas fa-edit"></i>Edit</li>
          <li><i class="fas fa-trash-alt"></i>Delete</li>
        </ul>`;

        const eventIndex = myEvents.findIndex(event => event.id === info.event.id);

        document.body.appendChild(menu);
        menu.style.top = e.pageY + 'px';
        menu.style.left = e.pageX + 'px';

        // Edit context menu
        menu.querySelector('li:first-child').addEventListener('click', function () {
          menu.remove();
          const editModal = new bootstrap.Modal(document.getElementById('form'));
          const modalTitle = document.getElementById('modal-title');
          const titleInput = document.getElementById('event-title');
          const startDateInput = document.getElementById('start-date');
          const endDateInput = document.getElementById('end-date');
          const colorInput = document.getElementById('event-color');
          const submitButton = document.getElementById('submit-button');
          const cancelButton = document.getElementById('cancel-button');
          modalTitle.innerHTML = 'Edit Event';
          titleInput.value = info.event.title;
          startDateInput.value = moment(info.event.start).format('YYYY-MM-DD');
          endDateInput.value = moment(info.event.end, 'YYYY-MM-DD').subtract(1, 'day').format('YYYY-MM-DD');
          colorInput.value = info.event.backgroundColor;
          submitButton.innerHTML = 'Save Changes';

          editModal.show();

          submitButton.classList.remove('btn-success')
          submitButton.classList.add('btn-primary')

          submitButton.addEventListener('click', function () {
            const updatedEvents = {
              id: info.event.id,
              title: titleInput.value,
              start: startDateInput.value,
              end: moment(endDateInput.value, 'YYYY-MM-DD').add(1, 'day').format('YYYY-MM-DD'),
              backgroundColor: colorInput.value
            }

            if (updatedEvents.end <= updatedEvents.start) {
              dangerAlert.style.display = 'block';
              return;
            }

            const eventIndex = myEvents.findIndex(event => event.id === updatedEvents.id);
            myEvents.splice(eventIndex, 1, updatedEvents);

            // Update the event in the calendar
            const calendarEvent = calendar.getEventById(info.event.id);
            calendarEvent.setProp('title', updatedEvents.title);
            calendarEvent.setStart(updatedEvents.start);
            calendarEvent.setEnd(updatedEvents.end);
            calendarEvent.setProp('backgroundColor', updatedEvents.backgroundColor);

            // Send updated event data to the server for database update
            fetch('update_event.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify(updatedEvents)
            })
              .then(response => response.json())
              .then(data => {
                // Handle the response from the server (e.g., display success message)
                console.log(data);
              })
              .catch(error => {
                // Handle any errors that occur during the request
                console.error('Error:', error);
              });

            editModal.hide();
          });
        });

        // Delete menu
        menu.querySelector('li:last-child').addEventListener('click', function () {
          const deleteModal = new bootstrap.Modal(document.getElementById('delete-modal'));
          const modalBody = document.getElementById('delete-modal-body');
          const cancelModal = document.getElementById('cancel-button');
          modalBody.innerHTML = `Are you sure you want to delete <b>"${info.event.title}"</b>`
          deleteModal.show();

          const deleteButton = document.getElementById('delete-button');
          deleteButton.addEventListener('click', function () {
            myEvents.splice(eventIndex, 1);

            // Send a request to the server to delete the event from the database
            fetch('delete_event.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({ id: info.event.id })
            })
              .then(response => response.json())
              .then(data => {
                // Handle the response from the server (e.g., display success message)
                console.log(data);
              })
              .catch(error => {
                // Handle any errors that occur during the request
                console.error('Error:', error);
              });

            calendar.getEventById(info.event.id).remove();
            deleteModal.hide();
            menu.remove();
          });

          cancelModal.addEventListener('click', function () {
            deleteModal.hide();
          });
        });

        document.addEventListener('click', function () {
          menu.remove();
        });
      });
    },

    eventDrop: function (info) {
      let myEvents = JSON.parse(localStorage.getItem('events')) || [];
      const eventIndex = myEvents.findIndex(event => event.id === info.event.id);
      const updatedEvent = {
        ...myEvents[eventIndex],
        id: info.event.id,
        title: info.event.title,
        start: moment(info.event.start).format('YYYY-MM-DD'),
        end: moment(info.event.end).format('YYYY-MM-DD'),
        backgroundColor: info.event.backgroundColor
      };
      myEvents.splice(eventIndex, 1, updatedEvent);
      localStorage.setItem('events', JSON.stringify(myEvents));
      console.log(updatedEvent);
    }
  });

  calendar.on('select', function (info) {
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    startDateInput.value = info.startStr;
    const endDate = moment(info.endStr, 'YYYY-MM-DD').subtract(1, 'day').format('YYYY-MM-DD');
    endDateInput.value = endDate;
    if (startDateInput.value === endDate) {
      endDateInput.value = '';
    }
  });

  calendar.render();

  const form = document.querySelector('form');

  form.addEventListener('submit', function (event) {
    event.preventDefault(); // prevent default form submission

    // retrieve the form input values
    const title = document.querySelector('#event-title').value;
    const startDate = document.querySelector('#start-date').value;
    const endDate = document.querySelector('#end-date').value;
    const color = document.querySelector('#event-color').value;
    const endDateFormatted = moment(endDate, 'YYYY-MM-DD').add(1, 'day').format('YYYY-MM-DD');
    const eventId = uuidv4();

    if (endDateFormatted <= startDate) {
      dangerAlert.style.display = 'block';
      return;
    }

    const newEvent = {
      id: eventId,
      title: title,
      start: startDate,
      end: endDateFormatted,
      allDay: false,
      backgroundColor: color
    };

    // Send the new event data to the server for database insertion
    fetch('save_event.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(newEvent)
    })
      .then(response => response.json())
      .then(data => {
        // Handle the response from the server (e.g., display success message)
        console.log(data);
        // Add the new event to the calendar
        calendar.addEvent(newEvent);
      })
      .catch(error => {
        // Handle any errors that occur during the request
        console.error('Error:', error);
      });

    myEvents.push(newEvent);

    // save events to local storage
    localStorage.setItem('events', JSON.stringify(myEvents));

    myModal.hide();
    form.reset();
  });

  myModal._element.addEventListener('hide.bs.modal', function () {
    dangerAlert.style.display = 'none';
    form.reset();
  });
});
  </script>
      <!-- Bootstrap JavaScript from CDN -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FullCalendar Core JavaScript from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.2.0/main.min.js"></script>
    <!-- FullCalendar DayGrid JavaScript from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.2.0/main.js"></script>
    <!-- FullCalendar Interaction JavaScript from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@4.2.0/main.js"></script>
    <!-- Moment.js from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- UUIDv4 from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/uuid@8.3.2/dist/umd/uuidv4.min.js"></script>
</body>
</html>