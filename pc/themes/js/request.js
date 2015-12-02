/**
 * Created by apple on 15/10/25.
 */
function show_mask(btn_id) {
    $(".btn-loading-img").show();
    $("#"+btn_id).addClass("btn-disabled");
}

function hide_mask(btn_id) {
    $(".btn-loading-img").hide();
    $("#"+btn_id).removeClass("btn-disabled");
}

function show_system_message(message) {
    $("#mask").show();
    $("#message-content").html(message);
}

function hide_system_message() {
    $("#mask").hide();
}

function show_error_message(id, msg) {
    $("#"+id+"-error").text(msg);
}

function hide_error_message(id) {
    $("#"+id+"-error").text("");
}

function show_message(id, msg) {
    $("#"+id).text(msg);
}

if(String.prototype.trim == undefined) {
    String.prototype.trim = function(){
        return this.replace(/^\s+(.*?)\s+$/, "$1");
    }
}
