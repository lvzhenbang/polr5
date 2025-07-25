var original_link;

function select_text() {
    $('.result-box').trigger('focus').trigger('select');
}

function copyToClipboard(str) {
    var el = document.createElement('textarea');
    el.value = str;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    var selected =
        document.getSelection().rangeCount > 0
    ? document.getSelection().getRangeAt(0)
    : false;
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
    if (selected) {
        document.getSelection().removeAllRanges();
        document.getSelection().addRange(selected);
    }
}

$('#generate-qr-code').on('click', function () {
    var container = $('.qr-code-container');
    container.empty();
    new QRCode(container.get(0), {
        text: original_link,
        width: 280,
        height: 280
    });
    container.find('img').attr('alt', original_link);
    container.show();
});

$('#clipboard-copy').on('click',function () {
    copyToClipboard($('#shor-url').val());
    $('#clipboard-copy').tooltip('show');
})

$(function () {
    original_link = $('.result-box').val();
    select_text();
});

$('.result-box').on('click', select_text);
$('.result-box').on('change', function () {
    $(this).val(original_link);
});