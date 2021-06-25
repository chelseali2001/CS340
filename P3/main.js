// Work Cited:
// Lecture notes


// Uses express, dbcon for database connection, body parser to parse form data handlebars for HTML templates
var express = require('express');
var mysql = require('./dbcon.js');
var bodyParser = require('body-parser');

var app = express();
var handlebars = require('express-handlebars').create({
        defaultLayout:'main',
        });

app.engine('handlebars', handlebars.engine);
app.use(bodyParser.urlencoded({extended:true}));
app.use('/static', express.static('public'));
app.set('view engine', 'handlebars');
app.set('port', process.argv[2]);
app.set('mysql', mysql);
app.use('/employee', require('./employee.js'));
app.use('/', express.static('public'));

// If page cannot be found
app.use(function(req,res){
  res.status(404);
  res.render('404');
});

// If there are problems with the server
app.use(function(err, req, res, next){
  console.error(err.stack);
  res.status(500);
  res.render('500');
});

// Running the webpage
app.listen(app.get('port'), function(){
  console.log('Express started on http://localhost:' + app.get('port') + '; press Ctrl-C to terminate.');
});
