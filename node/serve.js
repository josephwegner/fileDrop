var http = require('http');

require('./tools/require.js');

/****GLOBALS*****/
var ACTIONS = buildActionArray();
SQL_CLIENT = null;
/****************/


server = http.createServer(function(req, res) {
    
    var data = "";
    
    req.on("data", function(chunk) {
        data += chunk;
    });
    
    req.on("end", function() {
        var json = query.parse(data);
        
        passRequest({
            req: req,
            res: res,
            post: json
        });
    });

});
server.listen(1234);
console.log("Server Listening");

function passRequest(conn) {
    var urlParts= url.parse(conn.req.url);
    
    if(ACTIONS[urlParts.pathname] !== undefined) {//If it's a registered action
        action = ACTIONS[urlParts.pathname];//Get function
        action(conn);
    } else {//If not an action, check for resources
    
        var resource = getResource(urlParts);
        
        if(resource) {//If it's a valid resource
            fs.readFile(resource, function(err, data) {
                if(err) console.log(err);//Read and Send the file
                
                console.log("Sending Resource " + urlParts.pathname);
                resp.sendGeneric(conn.res, data);
            });
            
        } else {//If it's not an action or a resource, send a 404 error doc
            console.log("File Not Available - " + urlParts.pathname);
            conn.res.writeHead(404, {"Content-Type": "text/html"});
            conn.res.write("That page does not exist!");
            conn.res.end();
        }
    }   
}

function getResource(urlParts) {
    var ext = path.extname(urlParts.pathname);
    var folder = null;
    
    //We are going to manually set the resource folder based on the file extension
    //This will allow us some crazy amount of security, but still relatively easy
    //process of adding resources.  They just need to be in the correct file.
    //The main hope is that this will block any directory traversal attacks.
    switch(ext) {
    case ".js":
        folder = __dirname + "/resources/js";
        break;
        
    case ".css":
        folder = __dirname + "/resources/css";
        break;
        
    case ".png":
        folder = __dirname + "/resources/images";
        break;
        
    default:
        return false;
        
    }
    
    if(path.existsSync(folder + urlParts.pathname)) {//Check if file exists
        return folder + urlParts.pathname;
    } else {
        return false;
    }
    
    
}

function buildActionArray() {
    var actions = [];
    
    //Format
    //actions['page'] = function
    actions['/login'] = frontend.login;
    actions['/install'] = install.makeDB;
    actions['/ajaxLogin'] = backend.login;
    actions['/ajaxRegister'] = backend.register;
    
    return actions;
}


