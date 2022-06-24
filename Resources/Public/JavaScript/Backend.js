require(["jquery"], function($) {

    // Check if current theme have preview feature of Backend elements?
    // Grab from typo3conf/ext/ns_theme_t3karma/Resources/Public/Backend/JavaScript/Backend.js
    if(typeof(childThemeName) != 'undefined') {

        // PATCH for sub-domains of our demo server
        var isDemoServerPath = '';
        if(window.location.hostname == 'demo.t3terminal.com') {
            const subDirectory = window.location.pathname.split('/');
            isDemoServerPath = '/'+subDirectory[1];
        }

        // Preparing live preview for New element wizard
        $(document).on('click', '.t3js-modal-body .t3-new-content-element-wizard-inner .t3js-tabs .t3js-tabmenu-item', function() {
            $('.t3js-modal-body .t3-new-content-element-wizard-inner .tab-content').each(function( ) {
                
                $(this).find('.tab-pane .panel-body').each(function( ) {
                    
                    $(this).find('.t3js-media-new-content-element-wizard').each(function( ) {
                        
                        // Let's check if on "ns_elementName"
                        var elementName = $(this).find('.media-left .t3js-icon').attr('data-identifier');
                        if(elementName.indexOf('ns_') !== -1) {
                            $(this).find('button').addClass('NsBaseThemeElementWizardPreview');
                            $(this).find('button').attr('data-src',isDemoServerPath+'/typo3conf/ext/'+childThemeName+'/Resources/Public/Backend/ComponentPreview/'+elementName+'.png');
                        }
                    });
                });
            });
            $('.NsBaseThemeElementWizardPreview').anarchytip();
        });

        // Add/Edit form Check if current them have preview feature
        var isNsThemeElement = $('.typo3-TCEforms .tab-content .tab-pane:first-child fieldset.form-section:first-child .form-group:first-child .formengine-field-item .form-wizards-element select').find(":selected").val();
        //console.log(isNsThemeElement);

        if(typeof(isNsThemeElement) != 'undefined') {
            
            // Let's check if on "ns_elementName"
            if(isNsThemeElement.indexOf('ns_') !== -1) {

                setTimeout(function() {
                    
                    // Check every form fields
                    $('.typo3-TCEforms .tab-content .tab-pane:first-child fieldset.form-section').children().eq(1).each(function( ) {
                        $(this).find('.form-section').each(function( ) {

                            // Check if element == selectbox + iconbox
                            var elementsIcon = $(this);
                            var isSelextIcon = elementsIcon.find('.form-group .formengine-field-item .input-group span.input-group-icon');

                            if(typeof(isSelextIcon) != 'undefined') {

                                if(isSelextIcon.length > 0) {

                                    elementsIcon.find('.form-group .formengine-field-item .input-group .NsBasethemeElementIconContainerAtVariance').remove();
                                    elementsIcon.find('.form-group .formengine-field-item .input-group').after('<div class="NsBasethemeElementIconContainerAtVariance">'+isSelextIcon.html()+'</div>');

                                    var selectElement = $(this).find('.form-group .formengine-field-item .input-group select.form-select');
                                    $(selectElement).on('change', function() {
                                        
                                        var findIcon = $('option:selected', this).attr('data-icon');

                                        if(typeof(findIcon) != 'undefined') {

                                            if(findIcon.length > 0) {
                                                elementsIcon.find('.NsBasethemeElementIconContainerAtVariance').remove();
                                                elementsIcon.find('.form-group .formengine-field-item .input-group').after('<div class="NsBasethemeElementIconContainerAtVariance">'+isSelextIcon.html()+'</div>');
                                            }
                                        }
                                    });
                                }
                            }
                        });
                    });
                }, 700);
            }
        }
    }

    /*
    $('.t3js-page-new-ce').on('click', function(e){
        setTimeout(function() {
        }, 1000);
    });
    */
});
