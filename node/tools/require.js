/*********************
 * Node.JS Modules
**********************/
fs = require('fs');
console.log("fs Loaded");
url = require('url');
console.log("url Loaded");
path = require('path');
console.log("path Loaded");
query = require('querystring');
console.log"querystring Loaded");

/*********************
 * My Modules
 *********************/
 
//Require Paths//
require.paths.unshift(__dirname + "/../pageHandles");
require.paths.unshift(__dirname);
require.paths.unshift(__dirname + "/../packages");

//Require Third-Party Modules//
sqLite = require('node-sqlite3');
console.log("node-sqlite3 Loaded");


//Require Modules//
frontend = require('frontend.js');
console.log("frontend Loaded");
resp = require('response.js');
console.log("response Loaded");
options = require('options.js');
console.log("options Loaded");
sql = require('sqlBuilder.js');
console.log("sqlBuilder Loaded");
install = require('install.js');
console.log("install Loaded");
sessionHandler = require('session.js');
console.log("session Loaded");

