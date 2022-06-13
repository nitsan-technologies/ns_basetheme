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
        $('#nsLicenseLoader').show();
    });
    
    // Toggle question icon
    $('.field-info-trigger').on('click', function(){
        $(this).parents('.form-group').find('.field-info-text').slideToggle();
    });

    $('.custom-reset').on('click', function(){
        var that = $(this);
        that.find('i').addClass('fa-spin');
        var id = that.attr('data-id');
        var defaultValue = $("#" + id).attr('data-value');
        $("#" + id).val(defaultValue);
        $("#" + id).addClass('form__field');
        setTimeout(function(){
            $("#" + id).removeClass('form__field');
            that.find('i').removeClass('fa-spin');
        }, 2000);
    });


    $('#TypoScriptTemplateModuleController').on('submit',function(e){
        require(['TYPO3/CMS/Backend/Notification'], function(Notification) {
            Notification.success('Well done', 'Your configuration is updated successfully.');
        });
    });
    $('#createExtensionTemplate').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            url:$(this).attr('href'),
            method:'post',
            success:function(){
                window.location.reload();
            }
        })
    })
});
