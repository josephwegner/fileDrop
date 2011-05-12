var login = function(conn) {
    
    var session = sessionHandler.start(conn);
    var SqlBuilder = new sql.Builder();
    var md5 = hash.createHash('md5');
    
    var user = security.sanitizeString(conn.post.user);
    var pass = conn.post.pass;
    
    md5.update(options.salt);
    var salt = md5.digest('hex');
    md5 = hash.createHash('md5');
    md5.update(salt + pass);
    var encode_pass = md5.digest('hex');
    
    SqlBuilder.addFields(["password", "id", "group_id"]);
    SqlBuilder.table = "users";
    SqlBuilder.where = "`user`='" + user+ "'";
    
    var query = SqlBuilder.select();
    
    SQL_CLIENT.get(query, function(err, row) {
        if(err) console.log(err);
        var json;
        if(!row) {
            json = JSON.stringify({"err": "User does not exist", "passed": false});
            resp.sendGeneric(conn.res, json);
            return false;
        }
        
        if(encode_pass === row.password) {
            session.user_id = row.id;
            session.gid = row.group_id;
            
            if(row.gid === 1) {
                session.is_admin = true;   
            }
            
            json = JSON.stringify({"passed": true});
        } else {
            json = JSON.stringify({"err": "Invalid Login", "passed": false});  
        }
        
        resp.sendGeneric(conn.res, json);
    });
    
    
    
};

var register = function(conn) {
      
    var SqlBuilder = new sql.Builder();
    var md5 = hash.createHash('md5');
    var json;
      
      
    var user  = conn.post.user;
    var pass  = conn.post.pass;
    var name  = conn.post.name;
    var email = conn.post.email;
    var phone = conn.post.phone;
    var code  = conn.post.code;
    
    var notSecure = security.verifyInputs([
       {type: "string", value: user,  msg: "Invalid Username"},
       {type: "string", value: name,  msg: "Invalid Name"},
       {type: "phone",  value: phone, msg: "Invalid Phone Number"},
       {type: "string", value: code,  msg: "Invalid Group Code"}
       
    ]);
    
    if(notSecure) {
        json = JSON.stringify({"err": notSecure, "passed": false});
        resp.sendGeneric(conn.res, json);
        return false;
    }
    
    md5.update(options.salt);
    var salt = md5.digest('hex');
    md5 = hash.createHash('md5');
    md5.update(salt + pass);
    var encode_pass = md5.digest('hex');
    
    SqlBuilder.addFields(["id"]);
    SqlBuilder.table = "users";
    SqlBuilder.where = "`user`='" + user + "'";
    var query = SqlBuilder.select();
    SQL_CLIENT.get(query, function(err, row) {
        if(err) console.log(err);
        console.log(row);
        if(row !== undefined) {
            json = JSON.stringify({"err": "User Exists", "passed": false});
            resp.sendGeneric(conn.res, json);
            return false;
        }
        
        SqlBuilder.clearQuery();
        SqlBuilder.addFields(["id"]);
        SqlBuilder.table = "groups";
        SqlBuilder.where = "`code`='" + code + "' AND `open_register`=1";
        query = SqlBuilder.select();
        
        SQL_CLIENT.get(query, function(err, row) {
           if(err) console.log(err);
           
           if(row === undefined) {
               json = JSON.stringify({"err": "Closed Registration", "passed": "false"});
               resp.sendGeneric(conn.res, json);
               return false;
           }
           console.log(row);
           SqlBuilder.clearQuery();
           SqlBuilder.table = "users";
           query = SqlBuilder.insert([
                 {field: "user", value: user},
                 {field: "password", value: encode_pass},
                 {field: "name", value: name},
                 {field: "email", value: email},
                 {field: "phone", value: phone},
                 {field: "group_id", value: row.id},
                 {field: "can_download", value: "1"}
           ]);
           
           console.log("QUERY:" + query);
           
           SQL_CLIENT.run(query, function(lastId) {
               console.log("INSERTED USER WITH ID:" + lastId);
               resp.sendGeneric(conn.res, JSON.stringify({"passed": true}));
           });
           
        });
    });
      
};

/***************
 * Build Exports Object
 * *************/
 
 exports.login = login;
 exports.register = register;