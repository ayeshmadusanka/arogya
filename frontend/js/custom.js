$(document).ready(function() {
    $(".custom-alert").delay(5000).fadeOut("slow");
  });
// to get current year
function getYear() {
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    document.querySelector("#displayYear").innerHTML = currentYear;
}

getYear();

