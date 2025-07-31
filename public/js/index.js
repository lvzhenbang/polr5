$(function() {
    function resetURL() {
       var newUrl = $(".long-link-input").val();
       var paramsStr = "";
       var sourceVal = $("#utmSource").val();
       var mediumVal = $("#utmMedium").val();
       var campaignVal = $("#utmCampaign").val();

       if (sourceVal) {
            paramsStr += `&source=${sourceVal}`;
       }
       if (mediumVal) {
            paramsStr += `&medium=${mediumVal}`;
       }
       if (campaignVal) {
            paramsStr += `&campaign=${campaignVal}`;
       }

       if(newUrl) {
            if (paramsStr) {
                if (newUrl.indexOf('?') > -1) {
                    newUrl += paramsStr;
                } else {
                    newUrl += `?${paramsStr.slice(1)}`;
                }
            }
            $('#destination-url, #link-url').val(newUrl)
       }
    }
    $('#show-link-options').on('click', function(e) {
        var $el = $(e.target);
        if ($el.hasClass('active')) {
            $el.removeClass('active');
            $("#link-options-box").slideUp();
        } else {
            $el.addClass('active');
            $("#link-options-box").slideDown();
        }
    });
    $('#show-utm-options').on('click', function(e) {
        var $el = $(e.target);
        if ($el.hasClass('active')) {
            $el.removeClass('active');
            $("#utm-options-box").slideUp();
        } else {
            $el.addClass('active');
            $("#utm-options-box").slideDown();
        }
    });
    $('.long-link-input, #utmSource, #utmMedium, #utmCampaign').on('change', resetURL);
    $('#check-link-availability').on('click', function() {
        var custom_link = $('#custom-url-field').val();
        var request = $.ajax({
            url: "/api/v2/link_avail_check",
            type: "POST",
            data: {
                link_ending: custom_link
            },
            dataType: "html"
        });
        $('#link-availability-status').html('<span><i class="spinner-border spinner-border-sm" role="status"></i> Loading</span>');
        request.done(function(msg) {
            if (msg == 'unavailable') {
                $('#link-availability-status').html(`<span style="color:red">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0"/>
                    </svg>
                    Already in use
                </span>`);
            } else if (msg == 'available') {
                $('#link-availability-status').html(`<span style="color:green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                    </svg>
                    Available
                </span>`);
            } else if (msg == 'invalid') {
                $('#link-availability-status').html(`<span style="color:orange">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
                        <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                    </svg>
                    Invalid Custom URL Ending
                </span>`);
            } else {
                $('#link-availability-status').html(`<span style="color:red">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                    </svg>
                    An error occured. Try again
                </span>${msg}`);
            }
        });

        request.fail(function(jqXHR, textStatus) {
            $('#link-availability-status').html(`<span style="color:red">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                </svg>
                An error occured. Try again
            </span>${textstatus}`);
        });
    });
    min = 1;
    max = 2;
    var i = Math.floor(Math.random() * (max - min + 1)) + min;
    changeTips(i);
    var tipstimer = setInterval(function() {
        changeTips(i);
        i++;
    }, 8000);

    function setTip(tip) {
        $("#tips").html(tip);
    }

    function changeTips(tcase) {
        switch (tcase) {
            case 1:
                setTip('Create an account to keep track of your links');
                break;
            case 2:
                setTip('Did you know you can change the URL ending by clicking on "Link Options"?');
                i = 1;
                break;
        }
    }
});
