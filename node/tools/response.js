var sendHTML = function sendHTML(res, data) {
    res.statusCode = 200;
    res.setHeader("Content-Type", "text/html");
    res.write(data);
    res.end();
};

var sendGeneric = function sendGeneric(res, data) {//Allows any type of content
    res.statusCode = 200;//Mainly used for css/js resources that the browser
    res.write(data);//parses with its own set content type.
    res.end();
};

var parseHTML = function(data, replacements) {
       return data.replace(/<%(.+?)%>/g, function(str, m) {
            return JSON.stringify(replacements[m] || "");
        });
};
/*********************
 * Build exports object
 *********************/
 
 exports.sendHTML = sendHTML;
 exports.sendGeneric = sendGeneric;
 exports.parseHTML = parseHTML;