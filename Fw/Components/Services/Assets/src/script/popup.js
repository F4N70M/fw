console.log('>> popup.js');


function popup(url, data={})
{
    console.log(url.split('?'));
    urlSplit = url.split('?');
    url = urlSplit[0]+'.popup';
    if(urlSplit[1]!=undefined)
    {
        url += '?'+urlSplit[1];
    }
    // url += '.popup';
    console.log('!!!!!',url);
    let formData = new FormData;
    $.each(data, function (key,value) {
        formData.append(key, value);
    });
    console.log(url,formData);
    ajax(url,function (data) {
        // $('body').append(data);
        popupPrint(data);
    }, formData);
}

var popups = {};
function popupPrint(html)
{
    let id = getUID();
    console.log(id);
    $('body').append('<div id="'+id+'">'+html+'</div>');
}

function getUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g,
        function(c) {
            var r = Math.random() * 16 | 0,
                v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        }).toUpperCase();
}

jQuery(document).ready(function($) {
    console.log('>> popup.js ready');

    $(document).on('click', '.popup-link',function (event) {
        event.preventDefault();
        console.log('click', event.target);
        popup(this.href);
    });

    $(document).on('click', '[data-popup]',function (event) {
        url = $(this).data('popup');
        data = {};
        $.each($(this).data(), function (key,value) {
            pattern = /popup(.+)/i;
            matches = key.match(pattern);
            if (matches)
            {
                console.log(matches[1], value);
                data.append(matches[1], value);
            }
        });
        popup(url,data);
    });

    $(document).on('click', '.popup__close', function (event) {
        if(event.target == this)
            $(this).closest('.popup').remove();
    })
});