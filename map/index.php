
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <script src="map_grid.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="main.css">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="../css/fontAwesome.css">
        <link rel="stylesheet" href="../css/hero-slider.css">
        <link rel="stylesheet" href="../css/tooplate-style.css">

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">

        <script src="../js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    	
    	<style type="text/css">
    		ul {
			    list-style-type: none;
			    margin: 0;
			    padding: 0;
			    overflow: hidden;
			    background-color: #333;
			}

			li {
			    float: left;
			    border-right:1px solid #bbb;
			}

			li:last-child {
			    border-right: none;
			}

			li a {
			    display: block;
			    color: white;
			    text-align: center;
			    padding: 14px 16px;
			    text-decoration: none;
			}

			li a:hover:not(.active) {
			    background-color: yellow;
			}

			.active {
			    background-color: #4CAF50;
			}
    	</style>
  	</head>
  	<body>
  		<ul>
		 <li><a class="active" href="#home">Home</a></li>
		  <li><a href="#">News</a></li>
		  <li><a href="#">Contact</a></li>
		  
		</ul>
		  	<div id="signIn">
		        <div class="container">
		        	<div class="row">
		        		<div class="col-md-6">
		                    <div class="left-text">
		                        <h4>Instructions for using this system.</h4>
		                        <p> The Travelling Salesman Problem (often called TSP) is a classic algorithmic problem in the field of computer science. It aims in getting better solution. In this context better solution often means a solution that is cheaper. It is most easily expressed as a graph describing the locations of a set of nodes. Given a set of cities and the cost of travel (or distance) between each possible pairs, the TSP, is to find the best possible way of visiting all the cities and returning to the starting point that minimize the travel cost (or travel distance). 
		                        <br><br>Below is a grid of points which is a virtual representation of points in warehouse. Follow the below instructions to run the simulation.<br>
		                        
		                        	1.	Select the points on grid that u want the robot to keep objects at.
		                        	<br>
		                        	2.	Press Finish to initialize the simulation.
		                        

		                        </p>
		                        
		                    </div>
		                </div>
		                <div class="col-md-6">
		                    <div class="right-image">
		                        <img src="../img/map.jpg" alt="">
		                    </div>
		                </div> 
		            </div>
		        </div>
		    </div>
		    <br>
		    <br>

     	<span id="chelu"> </span> <br>
     	<span id="opti_path"></span> <br>
     	<span id="total_cost"> </span>
      	<script type="text/javascript">
        	var list = [];


        	var cities = [];
			var totalCities = 5;

			var popSize = 50;
			var population = [];
			var fitness = [];

			var recordDistance = Infinity;
			var bestEver;
			var currentBest;

			var statusP;
			var totalGenerations = 100;
			var currentGeneration;

			//Mutation Rate
			var mutationRate = 0.01;

			//Greatest and the least values that the coordinates can take
			var xMax = 40;     //These values are pixels
			var yMax = 40;

			//Cost MAtrix and reduced cost matrix
			var cost = [];

        	//points array stores the selected coordinates
        	var points = new Array(10);
        	for(var i = 0;i < 10; i++) 
        	{
        		points[i] = new Array(2);
        		points[i][0] = 0; points[i][1] = 0;
        	}
        	//Point_counter keeps count of total number of points
        	var point_counter = 0;

        	var lastClicked;
        	var grid = clickableGrid(10,10,function(el,row,col,i)
        	{
            	console.log("You clicked on element:",el);
	            console.log("You clicked on row:",row);
	            console.log("You clicked on col:",col);
	            console.log("You clicked on item #:",i);
	            //console.log(el.textContent);
	            //console.log(el.style.color);
	            if(el.style.color == "blue")
	            {
	            	el.style.backgroundColor = "red";
	            	el.style.color = "white";
	            }
            	else 
            	{
              		el.style.color = "blue";
              		el.style.backgroundColor = "white";
            	}
            	//comsole.log(i);


            	//Limiting Condition for exiting
	            if(i == 100)
	            {
		            console.log("okayy, Element List Finalized");
	            	simulate(list, points, point_counter);
	            }
	            else
	            {

	            	if(list.includes(i)) 
	            	{
	                	var index = list.indexOf(i);
	                	if (index > -1) 
	                	{
	                		list.splice(index, 1);
	                	}
	              	}
	             	else
	              	{
	                	list.push(i);

	                	points[point_counter][0] = row;
	                	points[point_counter][1] = col;
	                	point_counter++;

	              	}

	              	console.log(list);
	              
	              	el.className='clicked';
	              	if (lastClicked) lastClicked.className='';
	              	lastClicked = el;
	            }



            
        });
        //console.log(list);
       	document.body.appendChild(grid);
             
        //Funtion to generate a clickable grid
        function clickableGrid( rows, cols, callback ){
            var i=0;
            var grid = document.createElement('table');
            grid.className = 'grid';
            for (var r=0;r<rows;++r){
                var tr = grid.appendChild(document.createElement('tr'));
                for (var c=0;c<cols;++c){

                	//Change color of initial point :-
                	

                    var cell = tr.appendChild(document.createElement('td'));
                    
                    if(r == 0 && c == 0)
                	{
                		cell.innerHTML = "Start";
                		cell.style.color = "green";
                		++i;
                	}
                	else if(r == rows-1 && c == cols-1)
                	{
                		cell.innerHTML = "Final";
                		cell.style.color = "orange";
                		++i;
                	}
                	else
                	{
                		cell.innerHTML = ++i;
                    	cell.style.color = "blue";
                	}
                    
                    cell.addEventListener('click',(function(el,r,c,i){
                        return function(){
                            callback(el,r,c,i);
                        }
                    })(cell,r,c,i),false);
                }
            }
            return grid;
        }


        function simulate(list, points, point_counter)
        {
        	totalCities = point_counter;
        	console.log(list);
          	//document.getElementById("chelu").innerHTML = "#"+list;
          	console.log(point_counter);
          	console.log(points);

          	//-----------------------------STEP - 1 ------------------------------//
          	 //Create cost matrix
			//Step - 1 : Create lower trianglualr matrix
				for(var i = 0; i < 100; i++)
				{
			    	cost[i] = new Array(100);
			    	for(var j = 0; j <= i; j++)
			    	{
			      		if(i == j) 
			      		{
			        		cost[i][j] = 0;
			      		}
			      		else 
			      		{
			        		//cost[i][j] = Math.floor(Math.random()*(xMax+1));
			        		cost[i][j] = i+j;
			      		}
			    	}
			  	}

			  	//anuj[] is a lower triangular matrix
			  	var anuj = [];
			  	for(var i = 0; i < 100; i++)
			  	{
			    	anuj[i] = new Array(i);
			    	for(var j = 0; j <= i; j++ )
			    	{
			      		anuj[i][j] = cost[i][j];
			    	}
			  	}
			  	//console.log(anuj);

          	//------------------------------STEP - 2--------------------------//
          	//Step - 2 : Create rest cost matrix
				for(var i = 0; i < 100; i++)
			  	{
			    	for(var j = i+1; j < 100; j++ )
			    	{
			      		cost[i][j] = cost[j][i];
			    	}
			  	}
			  	//console.log(cost);


			  	var order = [];
				//Create initial given order of cities (exclude 1st city from it)
				for (var i = 0; i < point_counter; i++) 
				{
					order[i] = list[i];
				}
				//console.log("Cities : ",cities);
				//console.log("Order : ",order);


				//Create shuffled orders to fill up entire population
				population[0] = shuffle(order);
				for (var i = 1; i < popSize; i++)
				{    
					population[i] = shuffle(population[i-1].slice());
				}
				currentGeneration = 1;
				console.log(population);

          	//------------------------------STEP - 3 --------------------------//
          	//STEP - 3 : Create for loop and call all the functions
          	for(var z = 0; z < totalGenerations; z++) 
          	{
	          	calculateFitness();
				normalizeFitness();
				nextGeneration();

				currentGeneration++;
				//console.log(currentGeneration);
				if(currentGeneration == totalGenerations || currentGeneration == (totalGenerations-1)) 
				{
					console.log("recordDistance",recordDistance);
				    bestEver.push(1);
				    bestEver.unshift(1);
				    console.log("bestEver : ", bestEver);
				    
					break;
				}

			}
			//------------------------------STEP - 4----------------------------//
			//STEP - 4: Generate the coordinates from path
			var size_final = bestEver.length;
			var coordinates = new Array(size_final);
			for(var i  = 0; i < size_final; i++) 
			{
				coordinates[i] = new Array(2);
				coordinates[i][0] = 0; coordinates[i][1] = 0;

			}

			for(var i = 0; i < bestEver.length; i++)
			{
				var temp = bestEver[i];
				//row
				coordinates[i][0] = (parseInt((temp-1)/10+""));	
				//column
				var tt = (temp%10);
				if(tt != 0)
					coordinates[i][1] = tt - 1;
				else
				{
					coordinates[i][1] = 9;
				}
				console.log(temp + "  " + coordinates[i][0] + "  " + coordinates[i][1]);
				//temp = temp / 10;
				//coordinates[i][0] = ((temp-1)/10);				
			}
			
			//STEP - 5 : generate signals for robot 
			for(var k = 0; k < size_final - 1; k++) 
			{
				var x = coordinates[k+1][0] - coordinates[k][0];
				var y = coordinates[k+1][1] - coordinates[k][1];
					
				//Vertical movement
				if(x > 0) {
					for(var i = 0; i < x; i++) {
						//echo "down ";
						//console.log("down");
						document.getElementById("chelu").innerHTML += "d";
					}
				}
				else {
					for(var i = 0; i < (-1*x); i++) {
						//console.log("up");
						document.getElementById("chelu").innerHTML += "u";
					}
				}
				//Horizontal movement
				if(y > 0) {
					for(i = 0; i < y; i++) {
						//console.log("right");
						document.getElementById("chelu").innerHTML += "r";
					}
				}
				else {
					for(i = 0; i < (-1*y); i++) {
						//console.log("left");
						document.getElementById("chelu").innerHTML += "l";
					}
				}
				console.log();
			}
			var signal = document.getElementById("chelu").innerHTML;
			console.log(signal);

			document.getElementById("opti_path").innerHTML = "Optimised Path : "+bestEver;
			//document.getElementById("total_cost").innerHTML = "Optimised Path : "+bestEver;
			start_animation(signal, coordinates, size_final, bestEver);
          	//------------------------------------------------------------------//
        }

        //https://stackoverflow.com/questions/951021/what-is-the-javascript-version-of-sleep
        function sleep(ms) {
		  return new Promise(resolve => setTimeout(resolve, ms));
		}

        async function start_animation(signal, coordinates, size_final, bestEver) 
        {
        	//document.getElementById("test1").innerHTML = "This is the result";
        	console.log("Animation function started");
        	console.log('Taking a break...');
		  	//await sleep(2000);
		  	console.log('Two second later');
        	//document.body.innerHTML = '';

        	//-------------------------------START ANIMATION--------------------------------//

        	var grid = document.createElement('table');
            var rows = 10;
            var cols = 10;
            var i = 0;
            grid.className = 'grid';
            for (var r=0;r<rows;++r)
            {
                var tr = grid.appendChild(document.createElement('tr'));
                for (var c=0;c<cols;++c)
                {
                    var cell = tr.appendChild(document.createElement('td'));
                    
                    cell.innerHTML = ++i;
                   	cell.style.color = "blue";
                    
                }
            }
            document.body.appendChild(grid);

            //Accessing grid elements
            //https://www.w3schools.com/jsref/coll_table_cells.asp

            //console.log(grid);
            var g = new Array(10);
            for(var m = 0; m < 10; m++) 
            {
            	g[m] = grid.rows[m].cells;
            }
            console.log(g);
            //console.log(g[0][0].style.backgroundColor);
            //g[0][0].style.backgroundColor = "green";
            //var x = grid.rows[0].cells;
            //console.log(x[0].innerHTML);
            //console.log(x[3].style.backgroundColor = "green");
           
			//console.log("2,3 - " + g[2][3].innerHTML);
			//console.log("3,3 - " + g[3][3].innerHTML);
			//console.log("2,2 - " + g[2][2].innerHTML);
			//console.log("4,3 - " + g[4][3].innerHTML);
			
			var x = 0; var y = 0;
			
			console.log(bestEver);
			//console.log(signal, signal.length);
			for(var i = 0; i < signal.length; i++)
			{
				var e =  parseInt(g[x][y].innerHTML);
				if(bestEver.includes(e))
				{
					//console.log("hehehehehe");
					g[x][y].style.backgroundColor = "yellow";
				}
				else
				{
					g[x][y].style.backgroundColor = "green";
				}
				
				if(signal[i] == "d")
				{
					x++;
				}
				else if(signal[i] == "u")
				{
					x--;
				}
				else if(signal[i] == "r")
				{
					y++;
				}
				else if(signal[i] == "l")
				{
					y--;
				}	
				else
				{
					console.log("Something weird occured :",signal[i]);
				}

				await sleep(500);

			}
			
				
        	//-------------------------------END ANIMATION----------------------------------//
        }


        

		async function demo() {
		  console.log('Taking a break...');
		  await sleep(2000);
		  console.log('Two second later');
		}



        //Fisher–Yates shuffle  algorithm
		function shuffle(array) {
		  var currentIndex = array.length, temporaryValue, randomIndex;

		  // While there remain elements to shuffle...
		  while (0 !== currentIndex) {

		    // Pick a remaining element...
		    randomIndex = Math.floor(Math.random() * currentIndex);
		    currentIndex -= 1;

		    // And swap it with the current element.
		    temporaryValue = array[currentIndex];
		    array[currentIndex] = array[randomIndex];
		    array[randomIndex] = temporaryValue;
		  }

		  return array;
		}

		function swap(a, i, j) {
		  var temp = a[i];
		  a[i] = a[j];
		  a[j] = temp;
		}

		function calcDistance(order) 
		{
		  var sum = 0;
		  for (var i = 0; i < (order.length - 1); i++) {
		    //console.log(order, order.length);
		    var cityAIndex = order[i];
		    //var cityA = points[cityAIndex];
		    var cityBIndex = order[i + 1];
		    //console.log(cityBIndex, cityAIndex);
		    //var cityB = points[cityBIndex];
		    //var d = dist(cityA.x, cityA.y, cityB.x, cityB.y);
		    var d = cost[cityAIndex][cityBIndex];
		    sum += d;
		  }
		  sum += cost[0][ order[0] ] + cost[ order[(order.length - 1)] ][0];  
		  return sum;
		}

		function calculateFitness() {
		  var currentRecord = Infinity;
		  for (var i = 0; i < population.length; i++) {
		    var d = calcDistance(population[i]);
		    if (d < recordDistance) {
		      recordDistance = d;
		      bestEver = population[i];
		    }
		    if (d < currentRecord) {
		      currentRecord = d;
		      currentBest = population[i];
		    }
		    fitness[i] = 1 / (Math.pow(d, 8) + 1);
		  }
		}

		function normalizeFitness() {
		  var sum = 0;
		  for (var i = 0; i < fitness.length; i++) {
		    sum += fitness[i];
		  }
		  for (var i = 0; i < fitness.length; i++) {
		    fitness[i] = fitness[i] / sum;;
		  }
		}

		function nextGeneration() {
		  var newPopulation = [];
		  for (var i = 0; i < population.length; i++) {
		    var orderA = pickOne(population, fitness);
		    var orderB = pickOne(population, fitness);
		    var order = crossOver(orderA, orderB);
		    mutate(order, mutationRate);
		    newPopulation[i] = order;
		  }
		  population = newPopulation;

		}

		function pickOne(list, prob) {
		  var index = 0;
		  var r = Math.random();

		  while (r > 0) {
		    r = r - prob[index];
		    index++;
		  }
		  index--;
		  return list[index].slice();
		}

		function crossOver(orderA, orderB) {
		  var start = Math.floor(Math.random()*(orderA.length-1));
		  //var end = Math.floor(Math.random() * ((orderA.length) - (start + 1) + (start + 1)));
		  var end;
		  if(start == orderA.length - 2) {
		    end = start+1;
		  }
		  else {
		    end = Math.floor(Math.random() * (orderA.length-1 )) + (start+1);
		  }

		  
		  var neworder = orderA.slice(start, end);
		  // var left = totalCities - neworder.length;
		  for (var i = 0; i < orderB.length; i++) {
		    var city = orderB[i];
		    if (!neworder.includes(city)) {
		      neworder.push(city);
		    }
		  }
		  //To remove undefined elements
		  /*neworder = neworder.filter(function( element ) {
		   return element !== undefined;
		  });*/
		  return neworder;
		}

		function mutate(order, mutationRate) {
		  for (var i = 0; i < totalCities-1; i++) {
		    if (Math.random() < mutationRate) {
		      var indexA = Math.floor(Math.random()*(order.length));
		      var indexB = (indexA + 1) % (totalCities-1);
		      swap(order, indexA, indexB);
		    }
		  }
		}

      </script>

    
  </body>
</html>
