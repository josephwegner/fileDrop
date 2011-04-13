function handlePreviews() {
   $("input.preview").focus(emptyPreview);
}
function emptyPreview() {
    var valu = $(this).val();//Save for re-bind

    $(this).removeClass("preview");
    $(this).val("");//Clear the textbox
    $(this).unbind("focus");
    $(this).blur(function() {
       if($(this).val() == "") {
           $(this).focus(emptyPreview);
           $(this).addClass("preview");
           $(this).val(valu);
       }
    });
}