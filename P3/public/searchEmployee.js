// Work Cited:
// Lecture Notes

// Searching employees by first name
function searchEmployeeByFirstName() {
  var first_name_search_string = document.getElementById('first_name_search_string').value;
  
  if (first_name_search_string !== '') { // Redirecting to a subpage that shows all employees by a given first name
    window.location = '/employee/search/' + encodeURI(first_name_search_string);
  } else { // Redirecting to the main page with all of the current employees when no first name is entered
    window.location = '/employee';
  }
}