define([
    'jquery',
    'TYPO3/CMS/Backend/Modal',
    'TYPO3/CMS/NsBasetheme/Main',
], function ($, Modal) {
    
    // PATCH: Let's add preview image feature on click change
    $('.themePreviewSelect').on('change', function() {
        $('.themePreviewImg_'+$(this).attr('data-id')).attr("src", $( this ).find( "option:selected" ).data( "img-src" ));
    });

    // Toggle each panel
    $('.card-header').on('click', function(e){
        $(this).find('h5 em').toggleClass('fa-caret-down fa-caret-up');
        $(this).next('.card-body').slideToggle();
    });

    // Disable toggle feature on every Submit/Save button
    $('.card-header .btn-primary').on('click', function(e){
        e.stopPropagation();
    });
    
    // Toggle question icon
    $('.field-info-trigger').on('click', function(){
        $(this).parents('.form-group').find('.field-info-text').slideToggle();
    });

    $('#TypoScriptTemplateModuleController').on('submit',function(e){
        require(['TYPO3/CMS/Backend/Notification'], function(Notification) {
            Notification.success('Well done', 'Your configuration is updated successfully.');
        });
    });

});
