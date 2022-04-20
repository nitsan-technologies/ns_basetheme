define([
    'jquery',
    'TYPO3/CMS/Backend/Modal',
    'TYPO3/CMS/NsBasetheme/Main',
], function ($, Modal) {
    
    // PATCH: Let's add preview image feature on click change
    $('.themePreviewSelect').on('change', function() {
        $('.themePreviewImg_'+$(this).attr('data-id')).attr("src", $( this ).find( "option:selected" ).data( "img-src" ));
    });
    
    $('.field-info-trigger').on('click', function(){
        $(this).parents('.form-group').find('.field-info-text').slideToggle();
    });

    $('#TypoScriptTemplateModuleController').on('submit',function(e){
        require(['TYPO3/CMS/Backend/Notification'], function(Notification) {
            Notification.success('Well done', 'Your configuration is updated successfully.');
        });
    });

});
