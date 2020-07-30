/**
 * Created by Cedric on 13/01/14.
 */





	// for flight number
$('.alphanumeric').keypress(function (e) {
	var regex = new RegExp("^[a-zA-Z0-9]+$");
	var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
	if (regex.test(str)) {
		return true;
	}

	e.preventDefault();
	return false;
});







// if we are passed a hash, scroll to it
function goToByScroll(id)
{
	$('html, body').stop().animate({ 	scrollTop: $("#"+id).offset().top - 160		}, 'slow');
}





//  function to scroll(focus) to an element
function ScrollingTo(el, offeset) {
            var pos = (el && el.size() > 0) ? el.offset().top : 0;

            if (el) {
                if ($('body').hasClass('page-header-fixed')) {
                    pos = pos - $('.page-header').height();
                }
                pos = pos + (offeset ? offeset : -1 * el.height());
            }

            $('html,body').animate({
                scrollTop: pos
            }, 'slow');
}



function redirect(warning,url)
{
	if (confirm(warning))
	{
		window.location.href=url;
	}
	return false;
}








function redirectPost(url, data) {
	var form = document.createElement('form');
	document.body.appendChild(form);
	form.method = 'post';
	form.action = url;
	for (var name in data) {
		var input = document.createElement('input');
		input.type = 'hidden';
		input.name = name;
		input.value = data[name];
		form.appendChild(input);
	}
	form.submit();
}