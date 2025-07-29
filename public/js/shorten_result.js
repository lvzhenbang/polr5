var original_link;

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
    // console.log('x')
    copyToClipboard(original_link);
    $('#clipboard-copy').tooltip('show');
    setTimeout(() => {
        $('#clipboard-copy').tooltip('dispose');
    }, 1000);
})

$(function () {
    original_link = $('#short_url').val();
});
