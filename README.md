# PHP Hierarchical 

Hierarchical is a simple php & mysql program to arrange users based on their rankings, it can also be used to represent data in a chain ranking.


![alt text](https://github.com/peterujah/Hierarchical/blob/0fbd8458e5a3a838d34da5a898ff9e23e831664e/Screen%20Shot%202021-10-01%20at%206.10.46%20AM.png)

# USAGES

Hierarchical can be use as an array, html or google organizations chart

  ```php 
  use PeterUjah\Hierarchical;
  $hierarchy = new Hierarchical($conn, Hierarchical::LIST);
  $hierarchy = new Hierarchical($conn, Hierarchical::HTML);
  $hierarchy = new Hierarchical($conn, Hierarchical::CHART);
  ```
  
  Dump array 
  
   ```php 
   $hierarchy = new Hierarchical($conn, Hierarchical::LIST);
   var_export($hierarchy->run("Peter", "vy7735"));
   ```
   
   Display on google Organisation chart
  
  ```javascript
  google.charts.load('current', {packages:["orgchart"]});
	google.charts.setOnLoadCallback(drawChart);
	function drawChart() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Name');
      data.addColumn('string', 'Manager');
      data.addColumn('string', 'ToolTip');
      data.addRows(<?php echo $hierarchy->run("Peter", "vy7735");?>);
      var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
      chart.draw(data, {'allowHtml':true});
}
  
  ```
