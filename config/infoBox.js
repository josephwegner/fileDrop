/**
 * Created by JetBrains PhpStorm.
 * User: jwegner
 * Date: 4/6/11
 * Time: 11:12 AM
 * To change this template use File | Settings | File Templates.
 */
function infoBox(id, link, classes) {
    select = "div#" + id;

    html = $(select).html();//Get the data for the infoBox

    newHTML = "<img src='images/info.png' id='" + id + "' />";//Build image

    $(select).replaceWith(newHTML);

    this.id = id;
    this.select = "img#" + this.id;//Create Object Variables
    this.obj = $(this.select);
    obj = this.obj;

    if(typeof classes != undefined) {
        for(var i in classes)
            $(this.select).addClass(classes[i]);
    }

    $.data(this.obj, "msg", html);//Set the data for the infoBox

    if($("#infoBox").size)
        genInfoBox();//If there is no infoBox already added, craete it

    $(this.select).mouseenter(function() {

        pos = $(this).position();
        tp = pos.top + 20;
        left = pos.left;

        newLeft = (left < 200) ? left : (left - 200);

        $("#infoBox").hide();//Just in case it is still showing

        $("#infoBox").html($.data(obj, "msg"));


        $("#infoBox").css({//Place the box by the right infoBox
           'top': tp,
           'left': newLeft
        });
        $("#infoBox").show();//Show it
    });

    $(this.select).mouseleave(function() {
       $("#infoBox").hide();//Hide it
    });

    if(typeof link != "undefined") {//If a link was supplied, go there.
        $(this.select).click(function() {
           window.location = link;
        });
    }

}
function genInfoBox() {

    html = "<div id='infoBox'></div>";

    $("body").prepend(html);
}