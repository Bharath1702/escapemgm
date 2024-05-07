document.getElementById("date").addEventListener("change", function() {
    var date = this.value;
    // Make an AJAX request to fetch available timeslots for the selected date
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "get_timeslots.php?date=" + date, true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          document.getElementById("timeslotsContainer").innerHTML = xhr.responseText;
        } else {
          console.error("Error fetching timeslots: " + xhr.statusText);
        }
      }
    };
    xhr.send();
  });
  