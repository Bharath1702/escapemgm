document.addEventListener("DOMContentLoaded", function() {
    fetchData(); // Fetch data when the page loads
  
    // Submit form using AJAX
    document.getElementById("data-form").addEventListener("submit", function(event) {
      event.preventDefault();
      var formData = new FormData(this);
  
      fetch("admin_dashboard.php", {
        method: "POST",
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        alert(data); // Show alert with response
        fetchData(); // Fetch data after adding record
      })
      .catch(error => console.error("Error:", error));
    });
  });
  
  // Function to fetch data using AJAX
  function fetchData() {
    fetch("getData.php")
    .then(response => response.text())
    .then(data => {
      document.getElementById("data-container").innerHTML = data; // Update data-container with fetched data
    })
    .catch(error => console.error("Error:", error));
  }
  