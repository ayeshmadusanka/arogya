document.getElementById("contactForm").addEventListener("submit", function(event) {
    const phoneField = document.getElementsByName("phone")[0];
    if (!phoneField.checkValidity()) {
      event.preventDefault(); // Prevent form submission if phone field is invalid
      alert("Please enter a 10-digit phone number.");
    } else {
      document.getElementById("successMessage").style.display = "block";
      setTimeout(function() {
        document.getElementById("successMessage").style.display = "none";
      }, 5000);
    }
  });
