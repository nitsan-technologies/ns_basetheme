define([
    'jquery',
    'TYPO3/CMS/Backend/Modal',
    'TYPO3/CMS/NsBasetheme/Main',
], function ($, Modal) {
    $('.license-activation .license-activation-latest').on('click', function(e){
        e.preventDefault();
        $(this).addClass('active');
        $('#activation-modal').modal('show');
    });
    $('#activation-modal .activation-modal-update').on('click', function(e){
        var url = $('.license-activation .license-activation-latest.active').attr('href');
        $('.license-activation .license-activation-latest.active').removeClass('active');
        window.location = url;
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
    $('.field-info-trigger').on('click', function(){
        $(this).parents('.form-group').find('.field-info-text').slideToggle();
    });

    $('#TypoScriptTemplateModuleController').on('submit',function(e){
        require(['TYPO3/CMS/Backend/Notification'], function(Notification) {
            Notification.success('Well done', 'Your configuration is updated successfully.');
        });
    });

});
