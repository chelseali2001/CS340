var mysql = require('mysql');
var pool = mysql.createPool({
  connectionLimit : 10,
  host            : 'classmysql.engr.oregonstate.edu', 
  user            : 'cs340_lichel',
  password        : '4417',
  database        : 'cs340_lichel'
});

module.exports.pool = pool;
