///////////////////////////////////////
// Author: Donatas Stonys            //
// WWW: http://www.BlueWhaleSEO.com  //
// Date: 26 July 2012                 //
// Version: 0.7                      //
///////////////////////////////////////

// Asign current date to variable //
var currentDate = new Date();

// Create some DOM elements
var newCookiesWarningDiv = document.createElement("div");

// Retrieving cookie's information
function checkCookie(cookieName) {
	"use strict";
	var cookieValue, cookieStartsAt, cookieEndsAt;

	// Get all coookies in one string
	cookieValue = document.cookie;
	// Check if cookie's name is within that string
	cookieStartsAt = cookieValue.indexOf(" " + cookieName + "=");
	if (cookieStartsAt === -1) {
		cookieStartsAt = cookieValue.indexOf(cookieName + "=");
	}
	if (cookieStartsAt === -1) {
		cookieValue = null;
	} else {
		cookieStartsAt = cookieValue.indexOf("=", cookieStartsAt) + 1;
		cookieEndsAt = cookieValue.indexOf(";", cookieStartsAt);

		if (cookieEndsAt === -1) {
			cookieEndsAt = cookieValue.length;
		}

		// Get and return cookie's value
		cookieValue = unescape(cookieValue.substring(cookieStartsAt, cookieEndsAt));
		return cookieValue;
	}
}

// Cookie setup function
function setCookie(cookieName, cookieValue, cookiePath, cookieExpires) {
	"use strict";

	// Convert characters that are not text or numbers into hexadecimal equivalent of their value in the Latin-1 character set
	cookieValue = escape(cookieValue);

	// If cookie's expire date is not set
	if (cookieExpires === "") {
		// Default expire date is set to 6 after the current date
		currentDate.setMonth(currentDate.getMonth() + 6);
		// Convert a date to a string, according to universal time (same as GMT)
		cookieExpires = currentDate.toUTCString();
	}

	// If cookie's path value has been passed
	if (cookiePath !== "") {
		cookiePath = ";path = " + cookiePath;
	}

	// Add cookie to visitors computer
	document.cookie = cookieName + "=" + cookieValue + ";expires = " + cookieExpires + cookiePath;

	// Call function to get cookie's information
	checkCookie(cookieName);
}

// Check if cookies are allowed by browser //
function checkCookiesEnabled() {
	"use strict";
	// Try to set temporary cookie
	setCookie("TestCookieExist", "Exist", "", "");
	// If temporary cookie has been set, delete it and return true
	if (checkCookie("TestCookieExist") === "Exist") {
		setCookie("TestCookieExist", "Exist", "", "1 Jan 2000 00:00:00");
		return true;
	// If temporary cookie hasn't been set, return false	
	}
	if (checkCookie("TestCookieExist") !== "Exist") {
		return false;
	}
}

// Add HTML form to the website		
function acceptCookies() {
	"use strict";

//		"<input type='checkbox' name='agreed' value='Agreed' class='checkbox'>I accept cookies from this site." +
//		"<input type='submit' value='Continue' onclick='getAgreementValue(); return false;' class='button'>" +


	document.getElementById("cookiesWarning").appendChild(newCookiesWarningDiv).setAttribute("id", "cookiesWarningActive");
	document.getElementById("cookiesWarningActive").innerHTML = "<form name='cookieAgreement'>Please click ‘yes’ if you consent to our use of cookies on this website in accordance with the terms of our <span id='readMoreURL'></span>" +
		"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name='yes' type='submit' value='Yes' onclick='getAgreementValue2(1); return false;' class='button'>&nbsp;&nbsp; " +
		"<input name='no' type='submit' value='No ' onclick='getAgreementValue2(2); return false;' class='button'> " +
		"</form>";
	// Change the URL of "Read more..." here
	document.getElementById("readMoreURL").innerHTML = "<a href='/cookie_policy' title='Our Cookie Policy' rel='nofollow'>Cookie Policy.</a>";
}

function acceptCookiesTickBoxWarning() {
	"use strict";

	setCookie("TestCookie", "Yes", "", "1 Jan 2000 00:00:00");
	document.getElementById("cookiesWarning").appendChild(newCookiesWarningDiv).setAttribute("id", "cookiesWarningActive");
	document.getElementById("cookiesWarningActive").innerHTML = "<strong id='text' class='cookiesWarningFont'>Do you consent to us using cookies to help us make this website better and to provide a better service to you?.  </strong><span id='readMoreURL'></span><form name='cookieAgreement'><p id='warning'><small>You must tick the 'I accept cookies from this site' box to accept. If you continue without changing your settings, we'll assume that you agree to receive all cookies on this website.</small></p><input type='checkbox' name='agreed' value='Agreed' class='checkbox'>I accept cookies from this site.<input type='submit' value='Continue' onclick='getAgreementValue()' class='button'></form>";
	// Change the URL of "Read more..." here
	document.getElementById("readMoreURL").innerHTML = "<a href='http://www.ico.gov.uk/for_organisations/privacy_and_electronic_communications/the_guide/cookies.aspx' title='ICO - New EU cookie law (e-Privacy Directive)' target='_blank' rel='nofollow'>Read more...</a>";
}

// Check if cookie has been set before //
function checkCookieExist() {
	"use strict";
	// Call function to check if cookies are enabled in browser
	if (checkCookiesEnabled()) {
		// If cookies enabled, check if our cookie has been set before and if yes, leave HTML block empty
		if (checkCookie("TestCookie") === "Yes") {
			document.getElementById("cookiesWarning").innerHTML = "";
		// If our cookie hasn't been set before, call cookies' agreement form to HTML block
		return true;
		} else {
			acceptCookies();
		}
	} else {
		// Display warning if cookies are disabled on browser
//		document.getElementById("cookiesWarning").appendChild(newCookiesWarningDiv).setAttribute("id", "cookiesWarningActive");
//		document.getElementById("cookiesWarningActive").innerHTML = "<span id='cookiesDisabled'><strong>Cookies are disabled.</strong><br /> Your browser currently not accepting cookies.</span>";
	}
}






// Get agreement results
function getAgreementValue() {
	"use strict";

	// If agreement box has been checked, set permanent cookie on visitors computer
	if (document.cookieAgreement.agreed.checked) {
		// Hide agreement form
		document.getElementById("cookiesWarning").innerHTML = "";
		setCookie("TestCookie", "Yes", "", "");
	} else {
		// If agreement box hasn't been checked, delete cookie (if exist) and add extra warning to HTML form
		acceptCookiesTickBoxWarning();
	}
}

// Get agreement results
function getAgreementValue2(value)
{
	"use strict";
	var cookiePolicyShowPopup;

	// If agreement box has been checked, set permanent cookie on visitors computer
	if (value == 1)
	{
		cookiePolicyShowPopup		=	"off";
		// Hide agreement form
		document.getElementById("cookiesWarning").innerHTML = "";
//		setCookie2("cookiePolicyEnabled", "Yes", "/", 24);
//		write_cookie ("cookiePolicyEnabled", "Yes", "/");
		setCookies("cookiePolicyEnabled","Yes");

		window.location.reload();
	}
	else
	{
		cookiePolicyShowPopup		=	"off";
	}

	// turn off the popup and set session cookies
	document.cookie="cookiePolicyShowPopup=" + cookiePolicyShowPopup + ";path=/";
	document.getElementById("cookiesWarning").innerHTML = "";

//	setCookies("cookiePolicyShowPopup",cookiePolicyShowPopup);

}

// check we can accept cookies
function acceptCookiesCheck()
{
	// if we have a cookie set
	if (checkCookie("cookiePolicyEnabled") === "Yes")
	{
		// we can skip displaying the html and return true
	}
	else
	{
		// if session cookie is set to turn off popup
		if (checkCookie("cookiePolicyShowPopup") === "off")
		{
		}
		else
		{

			acceptCookies();
		}
	}

}





function setCookie2(name, value, path, expires, domain, secure){

	"use strict";

	document.cookie = name + "=" + escape(value) + "; ";

	if(expires){
		expires = setExpiration(expires);
		document.cookie += "expires=" + expires + "; ";
	}
	if(path){
		document.cookie += "path= " + path + "; ";
	}
	if(domain){
		document.cookie += "domain=" + domain + "; ";
	}
	if(secure){
		document.cookie += "secure; ";
	}
}

function setExpiration(cookieLife){
    var today = new Date();
    var expr = new Date(today.getTime() + cookieLife * 24 * 60 * 60 * 1000);
    return  expr.toGMTString();
}


function write_cookie (name, value, path)
{
        // Build the expiration date string:
        var expiration_date = new Date ();
        expiration_date . setYear (expiration_date . getYear () + 1);
        expiration_date = expiration_date . toGMTString ();

        // Build the set-cookie string:
        var cookie_string = escape (name) + "=" + escape (value) +
                "; expires=" + expiration_date;
        if (path != null)
                cookie_string += "; path=" + path;

        // Create/update the cookie:
        document . cookie = cookie_string;
}

function setCookies(nm, valu) {
    var e2 = (new Date(2019, 1, 1)).toGMTString();
    document.cookie = nm + "=" + escape(valu) + "; expires=" + e2;
}