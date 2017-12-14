define([
    'jquery',
], function($) {
    return {
        pincodeData: function(ajaxurl) {
            $(document).on('keyup', '#pincode_number', function (event) {
                event.preventDefault();
                var pincode = $(this).val();
                
                if(pincode.length >= 6)
                {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        showLoader: true,
                        dataType: 'json',
                        data: { pincode: pincode },
                        success : function(data) {
                            $('.checkTxt').html(data['message']);
                            if(data['success'] == "true" || data['success'] == "false")
                            {
                                $(".check-product-availability input").hide();
                                $(".check-product-availability h3").hide();
                            }
                        },
                        error : function(request, error) {
                            $('.checkTxt').html('error in searching pincode');
                        }
                    });                    
                }
            });
            $(document).on("click",".pincode-search-visible", function(){
                $(".check-product-availability input").show();
                $(".check-product-availability input").val("");
                $(".check-product-availability h3").show();
                $(".check-product-availability .checkTxt").html("");
            })
            $(document).on("click",".pincode-search-visible", function(){
                $(".check-product-availability input").show();
                $(".check-product-availability input").val("");
                $(".check-product-availability h3").show();
                $(".check-product-availability .checkTxt").html("");
            })
            $(document).on("mouseenter", ".pincode-search-help", function(event){
                    if($('.picode-popup-text').hasClass('hide'))
                    {
                        $('.picode-popup-text').removeClass('hide');
                        $('.picode-popup-text').addClass('show');
                    }
            })

            $(document).on("mouseleave", ".pincode-search-help", function(event){
                    if($('.picode-popup-text').hasClass('show'))
                    {
                        $('.picode-popup-text').removeClass('show');
                        $('.picode-popup-text').addClass('hide');
                    }
               
            })
        }
    }
});