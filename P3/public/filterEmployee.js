// Work Cited:
// Lecture Notes

// Filtering employees by project
function filterEmployeeByProject() {
  // Redirecting to a subpage that shows the employees who are working on a chosen project
  var project_pno = document.getElementById('project_filter').value;
  window.location = '/employee/filter/' + parseInt(project_pno);
}

// Clearing all filters
function clearFilters() {
  window.location = '/employee';
}
