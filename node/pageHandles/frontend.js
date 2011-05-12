var login = function(conn) {
    
    var loginPage = fs.readFile(__dirname + "/html/login.htm", function(err, data) {
            if(err) console.log(err);
            resp.sendHTML(conn.res, data);
    });
    
    
        
};

var index = function(conn) {
    var session = sessionHandler.start(conn);
    
    if(!security.isLoggedIn(conn, session)) {
        return false;
    }
    
    if(session.is_admin) {
            clientIndex(conn, session);
    } else {
            clientIndex(conn, session);
    }
};

/*****Private Functions*****/
/*
*---------INDEX---------
*header
*businessIndex
*clientIndex
*buildClientPage
*buildList
*/
function header(session) {
    fs.readFile(__dirname + "/html/header.htm", 'utf8', function(err, data) {
        if(err) console.log(err);
        
        var subs = {
               buildnum: "1.0" //TODO: DO NOT HARD CODE THIS
        };
        
        if(session.is_admin) {
               subs.groupslink = "<div class='headLink' onClick=\"goLink('groups.php');\">Groups</div>";
        }
        
        return resp.parseHTML(data, subs);
    });
}
function businessIndex(conn, session) {
    
}

function clientIndex(conn, session) {
    var sqlBuilder = new sql.Builder();
    var offset = (session.page - 1) * 20;
    
    sqlBuilder.addFields(['*']);
    sqlBuilder.table = "files";
    sqlBuilder.where = "`group_id`=" + session.gid;
    sqlBuilder.addOrderFields(['is_downloaded', 'upload_date']);
    sqlBuilder.limit = "(SELECT CASE WHEN " + offset + " > COUNT(*) THEN ";
        sqlBuilder.limit += offset + " ELSE 0 END),20";
    
    var fileQuery = sqlBuilder.select();
    
    sqlBuilder.clearQuery();
    
    sqlBuilder.addFields(['name']);
    sqlBuilder.table = "groups";
    sqlBuilder.where = "`id`="  + session.gid;
    
    var groupQuery = sqlBuilder.select();
    
    
    var semaphore = 2; //http://stackoverflow.com/questions/3709597/wait-until-all-jquery-ajax-request-are-done
                        //^^ same concept as ajax..  thanks SO!^^
    var data = {};
    
    SQL_CLIENT.all(fileQuery, function(err, rows) {
        if(err) console.log(err);
        
        data.files = rows;
        semaphore--;

        if(semaphore === 0) {
            buildClientPage(conn, session, data);
        }
    });
    
    SQL_CLIENT.get(groupQuery, function(err, row) {
        if(err) console.log(err);
        
        data.group = row.name;
        semaphore--;

        if(semaphore === 0) {
            buildClientPage(conn, session, data);
            
        }
    });
    
    
}

function buildClientPage(conn, session, data) {
    
    var subs = {
        header : header(session),
        group  : data.group,
        list   : buildList(data.files)
    }
    
    var clientRaw = fs.readFile(__dirname + "/html/client.htm", 'utf8', function(err, htm) {
        if(err) console.log(err);
        resp.sendHTML(conn.res, resp.parseHTML(htm, subs));
    });
    
}

function buildList(rows) {
    if(!rows.length) return "No Files Available<br>";
    
    var fini = "";
    fs.readFile(__dirname + "/html/list.htm", 'utf8', function(err, data) {
    
        for(var i=0,max=rows.length; i<max; i++) {
            var cur = rows[i];
            var prev = "";
            
            if(cur.has_preview) {//Build preview link
                prev  = "<a class='spanLink nobubble'";
                prev += "href=\"javascript:prepImage(<?php echo $r_id;?>, ";
                prev += "'<?php echo $r_file_name;?>');\">Preview</a>";
            }
            
            var subs = {
                downloaded  : cur.is_downloaded,
                id          : cur.id,
                filename    : cur.file_name,
                filesize    : cur.file_size,
                preview     : prev,
                c_name      : cur.name,
                c_email     : cur.email,
                c_phone     : cur.phone,
                upload_by   : cur.upload_by,
                upload_time : cur.upload_date,
                os          : cur.os,
                revision    : cur.is_revision,
                details     : cur.detail
            }
            fini += resp.parseHTML(data, subs);
        }
    });
    
    return fini;
}

/***************
 * Build Exports Object
 * *************/
 
 exports.login = login;
 exports.index = index;
