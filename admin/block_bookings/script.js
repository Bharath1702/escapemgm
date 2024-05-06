document.getElementById('date').addEventListener('change', function() {
  var date = this.value;
  fetchTimeSlots(date);
});

function fetchTimeSlots(date) {
  fetch('get_timeslots.php?date=' + date)
    .then(response => response.json())
    .then(data => {
      var timeSlotsDiv = document.getElementById('timeSlots');
      timeSlotsDiv.innerHTML = '';
      if (data.length > 0) {
        data.forEach(function(slot) {
          var container = document.createElement('div');
          var input = document.createElement('input');
          input.type = 'checkbox';
          input.name = 'timeslot_id[]';
          input.value = slot.id;
          var label = document.createElement('label');
          label.textContent = slot.timeslot;
          var timeSlotText = document.createElement('span');
          timeSlotText.textContent = slot.timeslot;

          container.appendChild(input);
          container.appendChild(label);
          container.appendChild(timeSlotText);

          timeSlotsDiv.appendChild(container);
          timeSlotsDiv.appendChild(document.createElement('br'));
        });
      } else {
        timeSlotsDiv.textContent = 'No available time slots for selected date.';
      }
    })
    .catch(error => console.error('Error:', error));
}
