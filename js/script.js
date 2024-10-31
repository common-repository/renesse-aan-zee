jQuery(document).ready(function($) {
    
    $('#renesse-button').click(function() {

        // Toggle visibility for buttons and text
        $('#widget-content').slideToggle();
        $('#widget-buttons').slideToggle();

        // If active show icon else cross
        if ($(this).hasClass('active')) {
            $(this).removeClass('active').text('').css({
                'background': 'url("/wp-content/plugins/renesse-aan-zee/assets/img/renesse-aan-zee-plugin-icon.png") no-repeat center center',
                'background-size': 'cover'
            });
        } else {
            $(this).addClass('active').html('<i class="fa-solid fa-xmark"></i>').css({
                'background-color': '#ec671a',
                'background-image': 'none'
            });
        }
    });

    // Show events
    $('#show-events-button').click(function() {
        $('#events-content').slideDown();
        $('#activities-content').slideUp();
        $('.widget-text').hide();
    });

    // Hide events
    $('.close-events').click(function() {
        $('#events-content').slideUp();
        $('#activities-content').slideUp();
        $('.widget-text').show();
    });

    // Show activities
    $('#show-activities-button').click(function() {
        $('#events-content').slideUp();
        $('#activities-content').slideDown();
        $('.widget-text').hide();
    });

    // Hide activities
    $('.close-activities').click(function() {
        $('#events-content').slideUp();
        $('#activities-content').slideUp();
        $('.widget-text').show();
    });

});