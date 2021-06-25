// Work Cited:
// Lecture notes and course materials

module.exports = function() {
  var express = require('express');
  var router = express.Router();

  // Getting the projects for the drop down option
  function getProjects(res, mysql, context, complete) {
    // Collecting all projects (by making queries and selecting data)
    mysql.pool.query("SELECT Pname, Pnumber FROM PROJECT", function(error, results, fields){
      if(error){
          res.write(JSON.stringify(error));
          res.end();
      }
      
      context.projects  = results; // Storing results for HTML template
      complete(); // Indicating that the action is done
    });
  }
  
  // Getting the employees
  function getEmployees(res, mysql, context, complete) {
    // Collecting all employees (by making queries and selecting data)
    mysql.pool.query("SELECT Fname, Lname, Salary, Dno FROM EMPLOYEE", function(error, results, fields){
      if(error){
        res.write(JSON.stringify(error));
        res.end();
      }
      
      context.employees = results; // Storing results for HTML template
      complete(); // Indicating that the action is done
    });   
  }
  
  // Getting the employees by the chosen project
  function getEmployeebyProject(req, res, mysql, context, complete) {
    // Collecting all the employees who were assigned to a specific project (by making queries and selecting data)
    var query = "SELECT Fname, Lname, Salary, Dno FROM EMPLOYEE INNER JOIN WORKS_ON ON Essn = Ssn WHERE Pno = ?";
    var inserts = [req.params.project]
    
    mysql.pool.query(query, inserts, function(error, results, fields){
      if(error){
        res.write(JSON.stringify(error));
        res.end();
      }
      
      context.employees = results; // Storing results for HTML template
      complete(); // Indicating that the action is done
    }); 
  }
  
  // Getting the project information
  function getProjectInfo(res, mysql, context, pnum, complete) {
    // Collecting the information about the chosen project (by making queries and selecting data)
    var sql = "SELECT Pnumber, Pname, Plocation FROM PROJECT WHERE Pnumber = ?";
    var inserts = [pnum];
    
    mysql.pool.query(sql, inserts, function(error, results, fields){
      if(error){
        res.write(JSON.stringify(error));
        res.end();
      }
      
      context.project = results[0]; // Storing results for HTML template
      complete(); // Indicating that the action is done
    });
  }
  
  // Getting the employees by a given first name
  function getEmployeeWithNameLike(req, res, mysql, context, complete) {
    //sanitize the input as well as include the % character
    // Collecting the employees by a given first name (by making queries and selecting data)
    var query = "SELECT Fname, Lname, Salary, Dno FROM EMPLOYEE WHERE Fname LIKE " + mysql.pool.escape(req.params.s + '%');
    
    mysql.pool.query(query, function(error, results, fields){
      if(error){
          res.write(JSON.stringify(error));
          res.end();
      }
      
      context.employees = results; // Storing results for HTML template
      complete(); // Indicating that the action is done
    });
  }

  // Setting the main company database page
  router.get('/', function(req, res){
    var callbackCount = 0;
    var context = {};
    context.jsscripts = ["filterEmployee.js","searchEmployee.js"]; // Collecting the javascript files
    var mysql = req.app.get('mysql'); // Connecting to database
    getProjects(res, mysql, context, complete); // Getting all projects
    getEmployees(res, mysql, context, complete); // Getting all employees
    
    // Updates employee page when all data is collected
    function complete(){
      callbackCount++;
      
      if(callbackCount >= 2){
        res.render('employee', context);
      }
    }
  });
  
  // Setting the page when filtering through employees by project
  router.get('/filter/:project', function(req, res) {
    var callbackCount = 0;
    var context = {};
    context.jsscripts = ["filterEmployee.js","searchEmployee.js"]; // Collecting the javascript files
    var mysql = req.app.get('mysql'); // Connecting to database
    getEmployeebyProject(req, res, mysql, context, complete); // Getting all employees by project
    getProjects(res, mysql, context, complete); // Getting all projects
    getProjectInfo(res, mysql, context, req.params.project, complete); // Getting the information about a specific project
    
    // Updates employee page when all data is collected
    function complete(){
        callbackCount++;
        
        if(callbackCount >= 3){
          res.render('employee', context);
        }
    }
  });
  
  // Setting the page when filtering through the employees by first name
  router.get('/search/:s', function(req, res) {
    var callbackCount = 0;
    var context = {};
    context.jsscripts = ["filterEmployee.js","searchEmployee.js"]; // Collecting the javascript files
    var mysql = req.app.get('mysql'); // Connecting to database
    getEmployeeWithNameLike(req, res, mysql, context, complete); // Getting all employees by a given first name
    getProjects(res, mysql, context, complete); // Getting all projects
    
    // Updates employee page when all data is collected
    function complete(){
        callbackCount++;
        
        if(callbackCount >= 2){
            res.render('employee', context);
        }
    }    
  });

  return router;
}();
