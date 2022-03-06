jQuery(document).ready(function($){

    $('.carosel-control-right').click(function(e) {
        e.preventDefault();
        $(this).blur();
        $(this).parent().find('.carosel-item-stack').first().insertAfter($(this).parent().find('.carosel-item-stack').last());
    });
    $('.carosel-control-left').click(function(e) {
        e.preventDefault();
        $(this).blur();
        $(this).parent().find('.carosel-item-stack').last().insertBefore($(this).parent().find('.carosel-item-stack').first());
    });

    // Do popularity in background
    var url = window.location.href;

    if (url.indexOf('category') !== -1){
        var response = httpGet(url + '?action=do-popularity');
        console.log(response);
    }

})

function httpGet(theUrl)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
    xmlHttp.send( null );
    return xmlHttp.responseText;
}
