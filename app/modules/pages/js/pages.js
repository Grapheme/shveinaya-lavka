// some JS code
$(document).on('click', '.pages_block_redactor_toggle', function(){

    var element = $('#blockEditModal textarea');
    var inited = $(element).parent().find('.redactor_editor').attr('class');

    if( inited ) {
        $('#blockEditModal textarea').redactor('destroy');
        //$(element).addClass('destroy')
    } else {
        $('#blockEditModal .redactor').redactor(imperavi_config || {});
        $('#blockEditModal .redactor-no-filter').redactor(imperavi_config_no_filter || {});
        //$(element).removeClass('destroy')
    }

});


$(document).on('click', 'button#reset_block_content', function(){

    var default_block_content = $(this).parents('form').find('#default_block_content').html();
    console.log(default_block_content);
    if (default_block_content != '')
        $(this).parents('form').find('.editor_block_content').html(default_block_content);
    return true;

});