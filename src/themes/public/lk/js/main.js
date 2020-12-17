function refreshInputLabel(dom)
{
    if (dom.id == '')
    {
        console.log('id not exist');
        return false;
    }

    let text = dom.title;

    if (dom.files.length >= 1)
    {
        if (dom.multiple)
        {
            text = 'Выбрано файлов: '+dom.files.length;
        }
        else
        {
            text = dom.files[0]['name'];
        }
    }
    let label = $('[for="'+dom.id+'"]');

    label.html(text);
}


function copytext(el) {
    var $tmp = $("<textarea>");
    $("body").append($tmp);
    $tmp.val($(el).text()).select();
    document.execCommand("copy");
    $tmp.remove();
}



jQuery(document).ready(function($) {

    $(document).on('change', '[type=file]', function (event) {
        console.log(this);
        refreshInputLabel(this);
    })

    $(document).on('click', '.copyToClipboard', function (event) {
        console.log(this);
        copytext(this);
    })

});