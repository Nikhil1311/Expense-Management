<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>POCKET EXPENSES</title>
    <script src="js/jquery.min.js"></script>
    <link href="css/bootstrap2.css" rel="stylesheet">
    <script type="text/javascript" src="js/bootstrap.min2.js"></script>  
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"> -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>     
    <link rel="stylesheet" href="css/icomoon.css">
    <link href="css/styleAdd.css" rel="stylesheet">
    <link href="css/wow.css" rel="stylesheet">
    <script type="text/javascript" src="js/smoothscroll.js"></script> 
    <script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>   
    
    <script type="text/javascript" src="data.json"></script>
    <style>
        

 
  .bar:hover{
    fill: green;
  }

	.axis {
	  font: 15px sans-serif;
	  color: red;
	}

	.axis path,
	.axis line {
	  fill: none;
	  stroke: #000;
	  shape-rendering: crispEdges;
	}

	</style>

    </style>

  </head>


 <body>
	<?php

  // $user = 'root' ;
  //  $pass = '' ;
  //  $db = 'pockets';
  //  $db = new mysqli('localhost' , $user ,$pass , $db) or die("unable to connect !!");
  //  $sql = "SELECT SUM(amount) AS amount,user.firstName FROM user,personalexpense where personalexpense.groupId = '$_GET[gid]' AND personalexpense.emailId = user.emailId GROUP BY user.firstName";
  //  mysqli_query($db , $sql) or die("Error while quering");
  //  $result = mysqli_query($db, $sql);

    include('init.php');
    $result = get_group_members_totals($_GET['gid']); 
    
    $data = array();
	
    while($row = $result->fetch_assoc()) 
	  {
  		#echo $row;
  		$r = array();
  		$r['firstName'] = $row['firstName'];
  		$r['amount'] = $row['amount'] * 1.0;
  		$data[] = $r;
    }
	
  	#echo json_encode($data);
  	$fp = fopen('results.json', 'w');
  	fwrite($fp, json_encode($data));
  	fclose($fp);

?>


<script src="http://d3js.org/d3.v3.min.js"></script>



        <div id="navbar-main">
            <div class="navbar navbar-inverse navbar-fixed-top">
                <div class="container">
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <img src = "img/logo.png" class = "logo1">
                      </button>
                      
                      <a class="navbar-brand hidden-xs hidden-sm" href="index.html"><img src = "img/logo.png" class = "logo"></a>
                    </div>

                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="settings.php" class="smoothScroll"><span class="icon icon-settings" style="font-size:15px;"> &nbsp</span>Settings</a></li>
                            <li><a href="logout.php" class="smoothScroll"><span class="icon icon-user-minus" style="font-size:15px;">&nbsp</span>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


        <div class="loginColumns animated fadeInDown" style = "margin-top:100px">
        <div class="row">
            <div class="col-md-12">
                <p class = "logintext2" style = "color:white;font-size:40px"><?php echo $_GET['gname']; ?></p>
            </div>
            <div class="col-md-12">
                <div class="wrappingbox">
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <p><a href = "user_home.php" class = "back">&larr; Home </a></p>
            </div>
        </div>
    </div>
	
	<script>
	
// set the dimensions of the canvas
var margin = {top: 20, right: 20, bottom: 90, left: 70},
    width = 600 - margin.left - margin.right,
    height = 300 - margin.top - margin.bottom;

// set the ranges
var x = d3.scale.ordinal().rangeRoundBands([0, width], .05);

var y = d3.scale.linear().range([height,0]);
var color = d3.scale.ordinal().range(["#6b486b", "#a05d56", "#d0743c", "#ff8c00","#ff7c00","#98abc5", "#8a89a6", "#7b6888"]);

// define the axis
var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom")


var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left")
    .ticks(10);


// add the SVG element
var svg = d3.select(".wrappingbox").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
	.append("g")
    .attr("transform", 
          "translate(" + margin.left + "," + margin.top + ")");


// load the data
  d3.json("results.json", function(error, data) {

    data.forEach(function(d) {
        d.firstName = d.firstName;
        d.amount = +d.amount;
    });
	
  // scale the range of the data
  x.domain(data.map(function(d) { return d.firstName; }));
  y.domain([0, d3.max(data, function(d) { return d.amount; })]);

  // add axis
  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis)
    .selectAll("text")
      .style("text-anchor", "end")
	 .attr("dx", "-.8em")
      .attr("dy", "-.55em")
      .attr("transform", "rotate(-90)" );


  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 5)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("Amount Spent");


  // Add bar chart
  svg.selectAll(".bar")
      .data(data)
	  .enter().append("rect")
      .attr("class", "bar")
      .attr("x", function(d) { return x(d.firstName); })
      .attr("width", x.rangeBand())
	  .style("fill", function(d, i) {
		return color(i);
		})
	  .attr("y",height)
	  .attr("height", 0)
	  .transition()
	  .duration(1000)
	  .delay(function(d,i){
		  return i * 100;
	  })
      .attr("y", function(d) { return y(d.amount); })
      .attr("height", function(d) { return  height - y(d.amount); });
	  
svg.selectAll("text.bar")
  .data(data)
 .enter().append("text")
  .attr("class", "label")
  .attr("text-anchor", "middle")
  .attr("x", function(d) { return x(d.firstName)+ x.rangeBand()/2; })
  .attr("y", function(d) { return y(d.amount)-5; })
  .text(function(d) { return d.amount; });
	  
	 
	  
	  
	  

});

    	
   
// Define responsive behavior
function resize() {
  var width = parseInt(d3.select(".wrappingbox").style("width"),10) - margin.left - margin.right-10;
  height = parseInt(d3.select(".wrappingbox").style("height"),10) - margin.top - margin.bottom-20;
  // Update the range of the scale with new width/height
  y.range([height,0]);
  x.rangeRoundBands([0,width], 0.1);
  
  
   yAxis.ticks(Math.max(height/50, 2));
    xAxis.ticks(Math.max(width/50, 2));

    d3.select(svg.node().parentNode)
        .style('width', (width + margin.left + margin.right) + 'px');

  // Update the axis and text with the new scale

  
      svg.select(".x.axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis)
    .selectAll("text")
      .style("text-anchor", "end")
	 .attr("dx", "-.8em")
      .attr("dy", "-.55em")
      .attr("transform", "rotate(-90)" );

svg.select(".y.axis")
    .call(yAxis);
 






  // Force D3 to recalculate and update the line
   svg.selectAll(".bar")
         .attr("x", function(d) { return x(d.firstName); })
		.attr("width", x.rangeBand())
	  .style("fill", function(d, i) {
		return color(i);
		})
	  .attr("y",height)
	  .attr("height", 0)
	  .transition()
	  .duration(1000)
	  .delay(function(d,i){
		  return i * 100;
	  })
      .attr("y", function(d) { return y(d.amount); })
      .attr("height", function(d) { return  height - y(d.amount); });
	  
	 
svg.selectAll(".label")
  .attr("class", "label")
  .attr("text-anchor", "middle")
  .attr("x", function(d) { return x(d.firstName)+ x.rangeBand()/2; })
  .attr("y", function(d) { return y(d.amount)-5; })
  .text(function(d) { return d.amount; });
	  
 
  
};

// Call the resize function whenever a resize event occurs
d3.select(window).on('resize', resize);

resize()
</script>


    </body>

</html>