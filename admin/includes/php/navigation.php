<div id="navContainer">
	<div id="mainNav">
		<ul>
			<li id="link1"><a href="/admin/" rel="nofollow"><i class="fa fa-home"></i></a></li>
			<li id="link2"><a href="/admin/content/">Content</a></li>
			<li id="link3"><a href="/admin/ate_news/" rel="nofollow">BLOG</a></li>
			<li id="link4"><a href="/admin/ate_content/" rel="nofollow">ATE Content</a></li>
			<li id="link5"><a href="/admin/ate_panel_access/" rel="nofollow">ATE Panel Access</a></li>
			<li id="link6"><a href="/admin/system/" rel="nofollow">System Admin</a></li>
		</ul>
	</div>
	<div id="subNav">
		<ul>
			<?php
			switch($pageSection)
			{
			case 2:
				if($subSection == "1")
				{
					echo '<li><strong>Pages &raquo;</strong></li>
                            <li><a href="/admin/content/pages/" rel="nofollow">Page List</a></li>
                            <li><a href="/admin/content/pages/add_page.php" rel="nofollow">Add Page</a></li>';
				}
				elseif($subSection == "2")
				{
					echo '<li><strong>News &raquo;</strong></li>
                            <li><a href="./" rel="nofollow">News List</a></li>
                            <li><a href="./detail.php" rel="nofollow">New Item</a></li>';
				}
				elseif($subSection == "3")
				{
					echo '<li><strong>Banners &raquo;</strong></li>
                            <li><a href="./" rel="nofollow">Banner List</a></li>
                            <li><a href="./detail.php" rel="nofollow">New Banner</a></li>';
				}
				elseif($subSection == "4")
				{
					echo '<li><strong>Press &raquo;</strong></li>
                            <li><a href="./" rel="nofollow">Press Release List</a></li>
                            <li><a href="./detail.php" rel="nofollow">New Press Release</a></li>';
				}
				elseif($subSection == "5")
				{
					echo '<li><strong>Key People &raquo;</strong></li>
                            <li><a href="./" rel="nofollow">Key People List</a></li>
                            <li><a href="./detail.php" rel="nofollow">New Key Person</a></li>';
				}
				else
				{
					echo '<li><a href="./pages/" rel="nofollow">Pages</a></li>';
#					echo '<li><a href="./news/" rel="nofollow">News</a></li>';
#					echo '<li><a href="./press/" rel="nofollow">Press</a></li>';
#					echo '<li><a href="./people/" rel="nofollow">Key People</a></li>';
#					echo '<li><a href="./banners/" rel="nofollow">Banners</a></li>';
				}
			break;
			case 4:
				if($subSection == "1")
				{
					echo '<li><strong>Subjects &raquo;</strong></li>
                            <li><a href="./" rel="nofollow">Subject List</a></li>
                            <li><a href="./detail.php" rel="nofollow">New Subject</a></li>';
				}
				elseif($subSection == "2")
				{
					echo '<li><strong>Caselaw &raquo;</strong></li>
                            <li><a href="./" rel="nofollow">Caselaw List</a></li>
                            <li><a href="./detail.php" rel="nofollow">New Caselaw Item</a></li>';
				}
				elseif($subSection == "3")
				{
					echo '<li><strong>Useful Docunents &raquo;</strong></li>
                            <li><a href="./" rel="nofollow">Document List</a></li>
                            <li><a href="./detail.php" rel="nofollow">New Document</a></li>';
				}
				else
				{
					echo '<li><a href="./useful_downloads/" rel="nofollow">Useful Downloads</a></li>';
					echo '<li><a href="./subjects/" rel="nofollow">ATE Training Subjects</a></li>';
					echo '<li><a href="./caselaw/" rel="nofollow">ATE Training Caselaw</a></li>';
				}
			break;
			case 5:
				if($subSection == "1")
				{
					echo '<li><strong>ATE Panel Users &raquo;</strong></li>
                            <li><a href="./?lType=N" rel="nofollow">New Users</a></li>
                            <li><a href="./?lType=A" rel="nofollow">Active Users</a></li>
	                        <li><a href="./detail.php" rel="nofollow">Add NEW User</a></li>
							<li><a href="./?lType=P" rel="nofollow">Partners</a></li>';
				}
				elseif($subSection == "2")
				{
					echo '<li><strong>Solicitors &raquo;</strong></li>
                            <li><a href="./" rel="nofollow">Solicitor List</a></li>
                            <li><a href="./detail.php" rel="nofollow">New Solicitor</a></li>';
				}
				else
				{
					echo '<li><a href="./users/" rel="nofollow">ATE Panel Users</a></li>';
					echo '<li><a href="./solicitors/" rel="nofollow">Solicitors</a></li>';
				}
			break;
			case 6:
				if($subSection == "1")
				{
					echo '<li><strong>Admin Users &raquo;</strong></li>
                            <li><a href="./" rel="nofollow">User List</a></li>
                            <li><a href="./detail.php" rel="nofollow">New User</a></li>';
				}
				else
				{
					echo '<li><a href="./admin_users/" rel="nofollow">Admin Users</a></li>';
				}
			break;
			}
			?>
		</ul>
	</div>
</div>
</div>						