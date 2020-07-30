// JavaScript Document
function do_action(iId,dbAction)
{
	if(dbAction)
	{
		var new_loc = 'detail.php?iId=' + iId + '&dbAction=' + dbAction;
		confirmed = confirm('Are you sure?');
	}
	else
	{
		var new_loc = 'detail.php?iId=' + iId;
		confirmed = true;
	}
	if(confirmed)
	{
		window.location = new_loc;
	}
}

function set_referrers()
{
	if(window.document.frmData.selCompanyId.selectedIndex > 0)
	{
		var compId = window.document.frmData.selCompanyId.value;
		for(i=0;i<aReferrers.length;i++)
		{
			if(aReferrers[i][1] == compId)
			{
				referrers = aReferrers[i][0];
				break;
			}
		}
	}
	else
	{
		var referrers = '';
	}
	if(referrers.length > 0)
	{
		referrers = referrers.substr(0,(referrers.length - 1));
		aTmp = referrers.split(',');
		//alert(aTmp.length);
	}
	else
	{
		aTmp = new Array();
	}
	
	var cbx = document.getElementsByName("cbxReferrers[]");
	for(i=0;i<cbx.length;i++)
	{
		cbx[i].checked = false;
		for(j=0;j<aTmp.length;j++)
		{
			if(cbx[i].value == aTmp[j])
			{
				cbx[i].checked = true;
			}
		}
	}
}


function redirect(warning,url)
{
	if (confirm(warning))
	{
		window.location.href=url;
	}
	return false;
}
