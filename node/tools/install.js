var makeDB = function(conn) {
    
     var sql = fs.readFile(__dirname + "/barebones.sql", "utf8", function(err, data) {
            if(err) console.log(err);//Read SQL Import file
            
            var Client = new sqLite.Database("ftp");
            
            Client.exec(data, function(err) {//execute import
                if(err) console.log(err);
                
                resp.sendHTML("Installation Complete.");
            });
            
    });
       
};


/***************
 * Build Exports Object
 * *************/
 
 exports.makeDB = makeDB;