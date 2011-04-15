var sanitizeString = function(pre, allowSpaces) {
    if(typeof allowSpaces === "undefined") {
        allowSpaces = false;   
    }
    
    if(allowSpaces) {
        return pre.replace(/^a-zA-Z0-9.\s]/g, "_");
    } else {
        return pre.replace(/^a-zA-Z0-9.]/g, "_");
    }
}

var verifyEmail = function(email)
    
    var parts = email.split("@");//check for @
    
    if(parts.length === 2) {
        return (parts[1].indexOf(".") > -1) ? true : false;//check for . (eg .com)
    } else {
        return false;
    }
};

var verifyPhone = function(number) {
    
    var clean = number.replace(/[-().,_\/\\\s]/g, "");
    
    var len = clean.length;
    
    
    //These are valid phone number lengths.
    //12345678901 (1-234-567-8901) = 11 chars
	//2345678901 (234-567-8901) = 10 chars
	//5678901 (567-8901) = 7 chars
	//Any more?
    if(len === 11 || len === 10 || len === 7) {
        return true;
    } else {
        return false;
    }
}