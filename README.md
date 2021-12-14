# PHP Hierarchical 

Hierarchical is a simple php & mysql program to arrange users based on their rankings, it can also be used to represent data in a chain ranking.
This libery will work very well with Google Organisation chart


![alt text](https://github.com/peterujah/Hierarchical/blob/c0fcb5bc6be51763ae3a04d04e56694d682b7ec5/Screen%20Shot%202021-10-01%20at%206.12.50%20AM.png)

## Installation

Installation is super-easy via Composer:
```md
composer require peterujah/hierarchical
```

# USAGES

Hierarchical can be use as an array, html or google organizations chart

  ```php 
  use Peterujah\NanoBlock\Hierarchical;
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

Initalisation options `new Hierarchical($conn, Hierarchical::CHART)`

| Methods         | Description                                                                         |
|-----------------|-------------------------------------------------------------------------------------|
| LIST            | Retrieve result as an array                                                         |
| HTML            | Retrieve result in HTML list                                                        |
| CHART           | Retrieve result in json data for google chart                                       |

  
  ```
