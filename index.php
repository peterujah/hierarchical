<?php 
session_start(); 
include_once __DIR__ . "/conn.php";
include_once __DIR__ . "/Hierarchical.php";
$hierarchy = new Hierarchical($conn, Hierarchical::CHART);
/*
 When a user has logged in
 let the $username parameter be the user ref id
 and $name parameter be the display name of the current session user
*/
$username = "vy7735";
$name = "Peter";
?>
<html>
  <head>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			google.charts.load('current', {packages:["orgchart"]});
			google.charts.setOnLoadCallback(drawChart);
			function drawChart() {
				var data = new google.visualization.DataTable();
				data.addColumn('string', 'Name');
				data.addColumn('string', 'Manager');
				data.addColumn('string', 'ToolTip');
				data.addRows(<?php echo $hierarchy->run($name, $username);?>);
				var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
				chart.draw(data, {'allowHtml':true});
			}
		</script>
	</head>
  	<body>
    	<div id="chart_div"></div>
  	</body>
</html>

