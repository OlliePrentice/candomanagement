jQuery(document).ready(function($) {

$('.pie').each(function(index, element) {
    var num = +($(this).text());
    var chart = '<svg class="gp-pie" viewBox="0 0 32 32"><circle class="circle" r="16" cx="16" cy="16" style="stroke-dasharray: 10 100" /></svg>';
    $(this).html(chart);
    $(this).find('.circle').css('stroke-dasharray', num + ' 100');
    $(this).append('<h2 class="gp-stats-circle">' + num + '%</h2>');
});


    $('.pie2').each(function(index, element) {
        var num = +($(this).text());
        var chart = '<svg class="gp-pie" viewBox="0 0 32 32"><circle class="circle" r="16" cx="16" cy="16" style="stroke-dasharray: 10 100" /></svg>';
        $(this).html(chart);
        $(this).find('.circle').css('stroke-dasharray', num + ' 100');
        $(this).append('<h2 class="gp-stats-circle">' + num + '%</h2>');
    });

});