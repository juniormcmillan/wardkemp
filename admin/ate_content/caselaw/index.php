<?php
$pageTitle = "ATE Training Caselaw Menu";
$pageSection = "4";
$subSection = "2";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$dbRsList = mysql_query("SELECT id, headline, status, category FROM ate_caselaw ORDER BY category, sort_order, headline");
$count = mysql_num_rows($dbRsList);


require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


$gMysql				=	new Mysql_Library();



$current_title	=	"";
$current_id		=	"";

$data_array	=	$gMysql->selectToArray("select distinct(category),category_order from ate_caselaw order by category_order,category", __FILE__, __LINE__,CACHE_CACHE_TIME_NORMAL);

foreach ($data_array as $data)
{

	#if($data['category'] != $current_title)
	{

		$pre				=	strtolower(str_replace(array('&',' '),'_',$data['category']));
		$current_title		=	$data['category'];
		$current_order		=	$data['category_order'];
		$current_id			=	$pre .	"_table";
		$current_input_id	=	$pre .	"_input";
		$current_order_id	=	$pre .	"_order";
		$current_submit_id	=	$pre .	"_submit";
	}


	$string .=	"
	
				<h3><label class='control-label '>".$current_title."</label></h3>
				
				<div class='input-group'>
					<input style='width:10%;' class='form-control ' id='".$current_order_id."' type='text' value='".$current_order."'>
					<input style='width:90%;' class='form-control ' data-category='".$current_title."' id='".$current_input_id."' type='text' value='".$current_title."'>
					<span class='input-group-btn'>
						<button type='button' class='update_button btn btn-default'  data-order-id='".$current_order_id."' data-category='".$current_title."' data-input-id='".$current_input_id."' id='".$current_submit_id."'>Update Caselaw Section Order & Title</button>
    			    </span>
				</div>
				<br>		

				<table  data-category='".$current_title."' id='".$current_id."' class='all_datatables toggles display table table-striped table-bordered 'cellspacing='0' width='100%'>
					<thead>
					<tr>
						<th>id</td>
						<th>id</td>
						<th align='left' width='300'>headline</td>
						<th width='50'>Updated</td>
						<th  width='30'>Status</td>
						<th  width='30'>Edit</td>
						<th  width='30'>Delete</td>
					</tr>
					</thead>
				</table>";
	
}



?>


<style>
#result {
	border: 1px solid #888;
	background: #f7f7f7;
	padding: 1em;
	margin-bottom: 1em;
}

</style>




	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.4/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>
	<script src="https://cdn.datatables.net/rowreorder/1.2.5/js/dataTables.rowReorder.min.js"></script>



	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.4/css/buttons.dataTables.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.7/css/select.dataTables.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.5/css/rowReorder.dataTables.min.css"/>
	<link rel="stylesheet" type="text/css" href="/admin/includes/css/editor/css/editor.dataTables.min.css"/>



	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

	<div class="mainAdminPage">
		<div class="mainAdminFull">
			<h1><?php echo $pageTitle ?></h1>


			<!--- main content -->
			<? echo $string ?>

			<br><br>

			<div id="result"></div>



			<script>

				$(document).ready(function()
				{
					// turns on the toggle buttons system
					$('#toggle-event').bootstrapToggle('on');




					// the clever init part
					$(document).on( 'preInit.dt', function (e, settings) {
						var api = new $.fn.dataTable.Api( settings );

						// grab the element and category
						var elem		=	e.target;
						var category	=	$(elem).attr("data-category");

						// set the category
						settings.ajax.data.category	=	category;

				//		alert(category+" "+settings.ajax.data.category);


					} );

					// inits all the datatables
					$('.all_datatables').DataTable(
					{
							"pageLength": 50,
							"ajax":	{
								'type':		'GET',
								'url':		"doGetAllCaseLaw.php",
								// send all these variables along to the php
								'data'	: 	{	"category" 	:	''},

							},
							// sorts them by this default column
							order: [ [0,'asc']	],

							// allow drag n drop
							rowReorder: true,

							// defines these columns
							"columnDefs": [

								//	{ targets: 0, visible: false },
								// turn off the id column from showing
								{ targets: 0, searchable: false,	orderable: false,	visible: false,	},
								{ targets: 1, searchable: false,	orderable: false,	visible: false,	},

								{ className: "reorder dt-body-left", "targets": [ 2 ] },
								// we need to have columns that are not sortable at all
								{ orderable: false, targets: '_all'}
							],

							// called after redrawing the table
							"drawCallback": function( settings )
							{

								// this needs initializing (toggle buttons)
								$('.toggle-demo').bootstrapToggle();
							},

					} );

					// turns on the toggle buttons system
					$('#toggle-event').bootstrapToggle('on');


					// this works for all datatables
					$('.all_datatables').on('row-reordered.dt', function ( e, diff, edit ) {
						// convert to variable
						var id		=	$(this).attr("id");

						// fluke was to create the table again, but datatables knows just to return pointer if init already
						var table = $('#'+id+'').DataTable();

						//
						doReorder(e,table);

					});








					// when we change the toggle, we need to call a function that updates them all
					$('.toggles').change(function(e)
					{
						var elem	=	e.target;
						id			=	$(elem).attr("data-id");
						status		=	$("#status"+id).prop('checked');

						$.ajax(
							{
								url:		'doUpdateToggles.php',
								type:		'POST',
								cache:		false,
								async:		true,
								dataType:	'json',
								data:		{
									id: id,
									status: status,

								},
								success:	function(data)
								{
									if	(data.returncode == 'error')
									{
									}
									else if (data.returncode == 'success')
									{
										// reload the results table
								//		table.ajax.reload();

									}
								},
								failure:	function()
								{
									alert('failure');
								}
							}
						)
					});


					// this will update the database with this element
					$('.update_button').on('click', function(e)
					{
						var elem			=	e.target;
						var category		=	$(elem).attr("data-category");
						var order_id		=	$(elem).attr("data-order-id");
						var new_order		=	$('#'+order_id).val();


				//		alert('order_id:'+order_id+' new_order:'+new_order);

						var id				=	$(elem).attr("data-input-id");
						var new_category	=	$('#'+id).val();



						// how it looks at this point is the order which it will be saved
						$.ajax(
							{
								url: 'doUpdateCategory.php',
								type: 'POST',
								cache: false,
								async: true,
								dataType: 'json',
								data: {
									// for sending data to the API
									new_order 				:	new_order,
									category 				:	category,
									new_category 			:	new_category
								},
								success: function (data) {

									window.location.reload(false);

								},

								failure: function () {
									alert('failure');
								}
							}
						);



					});


					// reorders a table and updates the database
					function doReorder(e,table)
					{
						var elem		=	e.target;
						var category	=	$(elem).attr("data-category");


//						alert(category);

						// Wait for the dom to settle before doing anything
						setTimeout(function() {

							var idOrderList=[];
							var idOrderNameList=[];
							var	i=0;
							var	j=0;
							console.clear();
							table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

								var data = this.data();

									idOrderList[i++]		=	data[1];
									idOrderNameList[j++]	=	data[2];

									console.log("Row:"+rowIdx+ " ID:"+data[0]+" N:"+data[1]);

							})


							// how it looks at this point is the order which it will be saved
							$.ajax(
								{
									url: 'doEditor.php',
									type: 'POST',
									cache: false,
									async: true,
									dataType: 'json',
									data: {
											// for sending data to the API
										category 				:	category,
										idOrderList 			:	idOrderList,
										idOrderNameList 		:	idOrderNameList
									},
									success: function (data) {

										//		alert(data.message);

									},

									failure: function () {
										alert('failure');
									}
								}
							);

						}, 1000); // Give it time to load in the DOM
					}





				}); // end document.ready





			</script>



<!--

			<?php
			if($count > 0)
			{
				$cat = "";

				while($item = mysql_fetch_array($dbRsList))
				{
					if($item["category"] != $cat)
					{
						if($cat != "")
						{
							echo '</table>';
						}
						echo '<h3>'.$item["category"].'</h3>
					<table cellspacing="0" class="listTable">';
					}
					$id = $item["id"];
					$headline = $item["headline"];
					$status = $item["status"];
					if($status == "A")
					{
						$statusLink = '<a href="detail.php?cId='.$id.'&dbAction=Deactivate">DEACTIVATE</a>';
					}
					else
					{
						$statusLink = '<a href="detail.php?cId='.$id.'&dbAction=Activate">ACTIVATE</a>';
					}
					echo '<tr valign="top" class="trInitial" onmouseover="this.className=\'trHighlight\';" onmouseout="this.className=\'trInitial\';" >
						<td><strong>'.urldecode($headline).'</strong></td>
						<td width="110">'.$statusLink.'</td>
						<td width="60"><a href="detail.php?cId='.$id.'">EDIT</a></td>
						<td width="60"><a href="detail.php?cId='.$id.'&dbAction=Delete" onclick="return confirm(\'Are you sure?\')">DELETE</a></td>
					</tr>';
					$cat = $item["category"];
				}
				echo '</table>';
			}
			else
			{
				echo '<p>There are no Caselaw items to show</p>';
			}
			?>
			<hr/>
			<ul>
				<li><a href="detail.php">New Caselaw item</a></li>
			</ul>
		</div>
	</div>

--->


<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>