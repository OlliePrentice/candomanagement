jQuery(document).ready(function($) {


    $( ".faq-list-item" ).click(function() {


        if($(this).find('.faq-svg-target').hasClass('fa-chevron-down')){


            $(this).find( ".faq-post-content").addClass('faq-full-height');
            $(this).find('.faq-svg-target').removeClass('fa-chevron-down');
            $(this).find('.faq-svg-target').addClass('fa-chevron-up');


        } else {


            $(this).find( ".faq-post-content").removeClass('faq-full-height');
            $(this).find('.faq-svg-target').removeClass('fa-chevron-up');
            $(this).find('.faq-svg-target').addClass('fa-chevron-down');


        }


    }); // END OF (.faq-list-item) .click


}); // END OF jQuery(document).ready