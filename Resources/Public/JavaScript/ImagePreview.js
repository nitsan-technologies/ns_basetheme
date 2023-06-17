require(["jquery"], function($) {
    
    !function(a) {
        "use strict";
        a.fn.anarchytip = function(b) {
            var c = a.extend({
                xOffset: 10,
                yOffset: 30
            }, b);
            return this.each(function() {
                var b = a(this);
                b.hover(function(b) {
                    this.t = this.title, this.title = "";
                    var d = "" != this.t ? "<br/>" + this.t : "";
                    // PATCH
                    var getDataSrc = $(this).attr("data-src");
                    a("body").append("<p id='NsBaseThemeElementWizardPreview'><img src='" + getDataSrc + "' alt='' />" + d + "</p>"), a("#NsBaseThemeElementWizardPreview").css({
                        top: b.pageY - c.xOffset + "px",
                        left: b.pageX + c.yOffset + "px"
                    }).fadeIn()
                }, function() {
                    this.title = this.t, a("#NsBaseThemeElementWizardPreview").remove()
                }), b.mousemove(function(b) {
                    a("#NsBaseThemeElementWizardPreview").css("top", b.pageY - c.xOffset + "px").css("left", b.pageX + c.yOffset + "px")
                })
            })
        }
    }(jQuery);
    
});