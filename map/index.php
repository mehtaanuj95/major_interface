
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <script src="map_grid.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="main.css">

    
  	</head>
  	<body>


     	<span id="chelu"> </span>

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

			//Define start and end Times
			var startTime;
			var endTime;

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
                    var cell = tr.appendChild(document.createElement('td'));
                    cell.innerHTML = ++i;
                    cell.style.color = "blue";
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
        	console.log(list);
          	document.getElementById("chelu").innerHTML = "#"+list;
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
			        		cost[i][j] = Math.floor(Math.random()*(xMax+1));
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









          	//------------------------------STEP - 2 Ends--------------------------//


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
      </script>


  </body>
</html>
