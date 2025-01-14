/**
 * File: Global Helper Functions
 * Created At: 7/12/2020
 * Auth: TechieTheMastermind
 */

/**
 * Display Blob picture in front end side
 * @param {*} input - file html element
 * @param {*} target - html element to display picture
 */
function display_image(input, target) {
    var file = input.files[0];
    var reader  = new FileReader();
    
    reader.onload = function(e)  {
        target.attr('src', e.target.result);
    }
    // declear file loading
    reader.readAsDataURL(file);
}

/**
 * Display Video in front end side
 * @param {*} url - video or embeded url
 * @param {*} target - html element to display url
 */
function display_iframe(url, target) {

    if (url == '') {
        target.addClass('no-video');
        target.attr('src', '');
        return true;
    }

    // Check video type
    var source = '';

    if(url.includes('youtube')) {
        source = 'youtube';
    }

    if(url.includes('vimeo')) {
        source = 'vimeo';
    }

    switch (source) {
        case 'youtube':
            var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
            var match = url.match(regExp);
            if (match && match[2].length == 11) {
                // if need to change the url to embed url then use below line
                target.attr('src', 'https://www.youtube.com/embed/' + match[2] + '?autoplay=0');
            } else {
                swal("Error!", "URL can not accept", "error");
                return false;
            }
            break;
        
        case 'vimeo':
            $.ajax({
                url: 'https://vimeo.com/api/oembed.json?url='+url,
                async: false,
                success: function(response) {
                    if(response.video_id) {
                        id = response.video_id;
                    }
                }
            });

            target.attr('src', 'https://player.vimeo.com/video/' + id);
            break;
        
        default:
            swal("Error!", "URL can not accept", "error");
            return false;
    }

    return true;
}

/**
 * Return Error mssage
 * @param {*} err - Ajax callback object
 */
function getErrorMessage(err) {
    var errors = JSON.parse(err.responseText).errors;
    var msg = '';
    $.each(errors, function(key, item){
        msg += item[0] + '\n';
    });

    return msg;
}

/**
 * Get Alert HTML
 * @param {*} title - Alert title
 * @param {*} msg - Alert content
 * @param {*} style - style - primary, warning, error
 */
function getAlert(title, msg, style) {

    return `<div class="alert alert-soft-` + style + ` alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <div class="d-flex flex-wrap align-items-start">
            
            <div class="flex" style="min-width: 180px">
                <small class="text-black-100">
                    <strong> `+ title + ` - </strong> ` + msg + `!
                </small>
            </div>
        </div>
    </div>`;
}

/**
 * 
 * @param {*} length : int - Length
 */
function makeId(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
       result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function convertToSlug(Text)
{
    return Text
        .trim()
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'')
        ;
}

function checkValidForm(Form)
{
    var no_empty_eles = Form.find('input[tute-no-empty], textarea[tute-no-empty], select[tute-no-empty]');
    var invalid_found = false;
    $.each(no_empty_eles, function(idx, ele) {
        if ($(ele).val() == '') {
            $(ele).addClass('is-invalid');
            if($(ele).siblings('.invalid-feedback').length < 1) {
                var err_msg = $('<div class="invalid-feedback">This field is required.</div>');
                err_msg.insertAfter($(ele));
            }
            
            $(ele).focus();
            invalid_found = true;
        }
    });

    return (invalid_found) ? false : true;
}

function btnLoading(Button, status)
{
    if(status) {
        Button.addClass('is-loading');
        Button.addClass('is-loading-sm');
        Button.attr('disabled', true);
    } else {
        Button.removeClass('is-loading');
        Button.removeAttr('disabled');
    }
}

function showLoader() {
    $('.preloader').show();
}

function hideLoader() {
    $('.preloader').hide();
}

function getUrlParameter(sParam) {
    let sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

// ===  Global Element Events === //
$(document).on('change', 'input[data-preview]', function() {
    display_image(this, $($(this).attr('data-preview')));
    $(this).removeClass('is-invalid');
});

$(document).on('change', 'input[data-video-preview]', function() {

    let rlt = display_iframe($(this).val(), $($(this).attr('data-video-preview')));

    if (!rlt) {
        $(this).val('');
    }
});

$(document).on('change', 'input[tute-file]', function(e) {
    var file_name = $(this).val().replace(/C:\\fakepath\\/i, '');
    var id = $(this).attr('id');
    $('div.custom-file').find('label[for="'+ id +'"]').text(file_name);
});

$(document).on('keyup', 'input[tute-no-empty], textarea[tute-no-empty], select[tute-no-empty]', function() {
    $(this).removeClass('is-invalid');
    $(this).closest('.form-group').find('div.invalid-feedback').remove();
});

$(document).on('submit', 'form', function(e) {

    var Form = $(this);
    var no_empty_eles = Form.find('input[tute-no-empty], textarea[tute-no-empty], select[tute-no-empty]');
    var invalid_found = false;
    $.each(no_empty_eles, function(idx, ele) {
        if ($(ele).val() == '') {
            $(ele).addClass('is-invalid');
            if($(ele).siblings('.invalid-feedback').length < 1) {
                var err_msg = $('<div class="invalid-feedback">This field is required.</div>');
                err_msg.insertAfter($(ele));
            }
            if(idx == 0) {
                $(ele).focus();
            }
            invalid_found = true;
        }
    });

    if(!invalid_found) {
        return true;
    } else {
        e.preventDefault();
        return false;
    }
});

// Check mobile browser
window.mobileAndTabletCheck = function() {
    let check = false;
    (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
};