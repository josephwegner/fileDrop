/****Local Functions/Vars****/

var isInit = false;

function init() {
   SQL_CLIENT = new sqLite.Database('ftp');
   
   isInit = true;
}

/****Builder Functions****/

var Builder = function() {
    if(!isInit) {
        init();   
    }
};

Builder.prototype.fields = "";
Builder.prototype.order = "";
Builder.prototype.table = "";
Builder.prototype.where = "";
Builder.prototype.limit = "";

Builder.prototype.addFields = function(fieldArr) {
    
    if(this.fields !== "") {
        this.fields += ", ";   
    }
    
    for(i = 0, max = fieldArr.length; i < max; i++) {
        this.fields += fieldArr[i] + ", ";   
    }
    
    this.fields = this.fields.substring(0, this.fields.length - 2);
    
};

Builder.prototype.addOrderFields = function(fieldArr) {
    
    if(this.order !== "") {
        this.order += ", ";   
    }
    
    for(i = 0, max = fieldArr.length; i < max; i++) {
        this.order += fieldArr[i] + ", ";   
    }
    
    this.order = this.order.substring(0, this.order.length - 2);
    
};

Builder.prototype.select = function() {
    if(this.fields === "" || this.table === "") {
        return false;   
    }
    
    var query = "SELECT " + this.fields + " FROM " + this.table;
    
    if(this.where !== "") {
        query += " WHERE " + this.where;   
    }
    
    if(this.order !== "") {
        query += " ORDER BY " + this.order;   
    }
    
    if(this.limit !== "") {
        query += " LIMIT " + this.limit;   
    }
    
    return query;
    
};

Builder.prototype.insert = function(insertArr) {
    
    var values = "";
    var fields = "";
    var query = "INSERT INTO " + this.table + " (";
    
    for(i = 0, max = insertArr.length; i < max; i++) {
        fields += insertArr[i].field + ", ";
        values += "'" + insertArr[i].value + "', ";
    }
    
    fields = fields.substring(0, fields.length - 2);
    values = values.substring(0, values.length - 2);
    
    query += fields + ") VALUES (" + values + ")";
    
    return query;
      
};

Builder.prototype.clearQuery = function() {
    this.fields = "";
    this.table = "";
    this.where= "";
    this.limit = "";
    this.order = "";
};

/***************
 * Build Exports Object
 * *************/
 
 exports.Builder = Builder;

