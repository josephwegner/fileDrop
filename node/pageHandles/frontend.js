//Borrwed some of this code from Cloud9IDE
//Thanks so much!
//www.cloud9ide.com

var login = function(conn) {
    
    var SqlBuilder = new sql.Builder();
SqlBuilder.addFields(["user", "id"]);
SqlBuilder.table = "users";
var query = SqlBuilder.select();
console.log(query);
SQL_CLIENT.each(query, function(err, row) {
    if(err) console.log(err);
   console.log("ID:" + row.id + " USER:" + row.user); 
});
    
    var loginPage = fs.readFile(__dirname + "/html/login.htm", function(err, data) {
            if(err) console.log(err);
            resp.sendHTML(conn.res, data);
    });
    
    
        
};


/***************
 * Build Exports Object
 * *************/
 
 exports.login = login;
