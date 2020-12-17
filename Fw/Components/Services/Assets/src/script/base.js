
console.log('>> base.js');


/**
 *
 */
class Inputs
{
    inputs;

    /**
     *
     */
    constructor()
    {
        this.inputs = $('input, textarea, select');
        // this.buttons = $('['type']');

        console.log(this.inputs.filter('[type=file]'));
        this.inputs.filter('[type=file]').each(function(key,item) {
            Inputs.createLabel(item);
            Inputs.refreshInputLabel(item);
        });
        this.inputs.filter('[type=file]').on('change',function (event) {
            console.log(this);
            Inputs.refreshInputLabel(this);
        });
    }

    /**
     *
     * @param item
     */
    static createLabel(item) {
        if($('[for="'+item.id+'"]').length == 0)
        {
            $("#" + item.id).after('<label for="' + item.id + '"></label>');
            $('[for="'+item.id+'"]')[0].classList += item.classList.value
        }
    }

    /**
     *
     * @param dom
     * @returns {boolean}
     */
    static refreshInputLabel(dom)
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
}

/**
 *
 * @param url
 * @param callback
 * @param formData
 */
function ajax(url, callback, formData=null)
{
    console.log('ajax ['+url+']');
    $.ajax({
        url: url,
        type: 'POST',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        success: function (data) {
            callback(data);
        },
        data: formData,
        cache: false,
        contentType: false,
        processData: false
    });
}

/**
 *
 * @param to
 * @returns {boolean}
 */
function scrollTo(to)
{
    /*
     *	Отмена предыдущего скроллинга
     */
    let doc = document.scrollingElement;
    let elem = $(to);
    // let doc = $(document);
    if (elem.length == 0) return false;

    if ( typeof scrollingTo != 'undefined' ) {
        clearInterval(scrollingTo);
    }

    let scrollStart = $(doc).scrollTop();
    // let scrollStart = document.scrollingElement.scrollTop;
    let scrollFinish = elem.offset().top /*- $('.nav__panel').height()*/;
    let scrollingToTime = 0;
    let fps = 30;
    let scrollingToMaxTime = .5;
    var scrollingTo = setInterval(function() {
        if(scrollingToTime >= fps) {
            clearInterval(scrollingTo);
        }
        let newScroll = scrollStart + (scrollFinish - scrollStart) * scrollingToTime / fps;
        $(doc).scrollTop(newScroll);
        // document.scrollingElement.scrollTop = newScroll;
        scrollingToTime++;
    },1000*scrollingToMaxTime/fps);
}

/**
 *
 */
jQuery(document).ready(function($) {
    console.log('>> base.js ready');

    /**
     *
     */
    $('body').on('click', 'a', function(event)
    {
        to = this.href.split('#')[1];
        if(to && $('#'+to).length > 0)
        {
            event.preventDefault();
            scrollTo( '#'+to );
        }
    });

    // let inputs = new Inputs();

});