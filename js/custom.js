$(document).ready(function() {



	// TOGGLE POSITION
	$('#changeposition-12-button').click(function() {
		$('#tablearea1').removeClass('col-lg-6 col-md-6').addClass('col-lg-12 col-md-12');
		$('#tablearea2').removeClass('col-lg-6 col-md-6').addClass('col-lg-12 col-md-12');
		$('#changeposition-12-button').addClass('hide'); $('#changeposition-6-button').removeClass('hide');
		$('#container1').removeClass('full').addClass('half');
		$('#container2').removeClass('full').addClass('half');

	});
	$('#changeposition-6-button').click(function() {
		$('#tablearea1').removeClass('col-lg-12 col-md-12').addClass('col-lg-6 col-md-6');
		$('#tablearea2').removeClass('col-lg-12 col-md-12').addClass('col-lg-6 col-md-6');
		$('#changeposition-6-button').addClass('hide'); $('#changeposition-12-button').removeClass('hide');
		$('#container1').removeClass('half').addClass('full');
		$('#container2').removeClass('half').addClass('full');
	});


	// MOBILE NAV BUTTON
	$('#mobile-show-nav').click(function() {
		$('#leftpanel').addClass('mobile-nav-show');
	});
	$('#mobile-hide-nav').click(function() {
		$('#leftpanel').removeClass('mobile-nav-show');
	});


});