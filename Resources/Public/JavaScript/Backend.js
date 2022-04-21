require(["jquery"], function($) {

    // Check if current theme have preview feature of Backend elements?
    // Grab from typo3conf/ext/ns_theme_t3karma/Resources/Public/Backend/JavaScript/Backend.js
    if(typeof(childThemeName) != 'undefined') {
        $(document).on('click', '.t3js-modal-body .t3-new-content-element-wizard-inner .t3js-tabs .t3js-tabmenu-item', function() {
            $('.t3js-modal-body .t3-new-content-element-wizard-inner .tab-content').each(function( ) {
                
                $(this).find('.tab-pane .panel-body').each(function( ) {
                    
                    $(this).find('.t3js-media-new-content-element-wizard').each(function( ) {
                        
                        var elementName = $(this).find('.media-left .t3js-icon').attr('data-identifier');
                        if(elementName.indexOf('ns_') !== -1) {
                            $(this).find('button').addClass('NsBaseThemeElementWizardPreview');
                            $(this).find('button').attr('data-src','/typo3conf/ext/'+childThemeName+'/Resources/Public/Backend/ElementPreview/'+elementName+'.jpeg');
                        }
                    });
                });
            });
            $('.NsBaseThemeElementWizardPreview').anarchytip();
        });
    }

    /*
    $('.t3js-page-new-ce').on('click', function(e){
        setTimeout(function() {
        }, 1000);
    });
    */
});