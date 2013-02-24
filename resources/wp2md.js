jQuery(document).ready(function($){
	$('#readme-url').focus(function(event){
		console.log('url');
		$(':radio[name=submit-type]').attr('checked', false);
		$('#submit-type-url')[0].checked = true;
	});

	$('#readme-file').click(function(event){
		$(':radio[name=submit-type]').attr('checked', false);
		$('#submit-type-file')[0].checked = true;
	});

	$('#readme-txt').focus(function(event){
		$(':radio[name=submit-type]').attr('checked', false);
		$('#submit-type-text')[0].checked = true;
	});
});
