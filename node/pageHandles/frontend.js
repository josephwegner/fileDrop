//Borrwed some of this code from Cloud9IDE
//Thanks so much!
//www.cloud9ide.com
function parseHTML(data, replacements) {
       return data.replace(/<%(.+?)%>/g, function(str, m) {
            return JSON.stringify(replacements[m] || "");
        });
}
var login = function(conn) {
    
    var loginPage = fs.readFile(__dirname + "/html/login.htm", function(err, data) {
            if(err) console.log(err);
            resp.sendHTML(conn.res, data);
    });
    
    
        
};


/***************
 * Build Exports Object
 * *************/
 
 exports.login = login;
