</div>


<script>
	if (document.getElementById("txtContent"))
	{
		CKEDITOR.replace( 'txtContent', {});
		
	}



	function redirect(warning,url)
	{
		if (confirm(warning))
		{
			window.location.href=url;
		}
		return false;
	}


</script>


<!-- CLOSE MAIN CONTAINER -->
</body>
</html>