jQuery(document).ready(function($) {
    $('#add-new-comment button').text('فرستادن تیکت');
    $('.save.button-primary #addbtn').text('فرستادن تیکت');
    $('.save.button-primary #savebtn').text('به‌روزرسانی تیکت');
    $('.save.button-primary #replybtn').text('فرستادن پاسخ');
    $('label[for=\'comment_status\']').text('پذیرفتن تیکت جدید').prepend('<input name=\"comment_status\" type=\"checkbox\" id=\"comment_status\" value=\"open\" checked=\"checked\">');
});
jQuery(document).ready(function($) {
    // Remove the spam and trash links from comment actions
    $('span.spam, span.trash').remove();
});
