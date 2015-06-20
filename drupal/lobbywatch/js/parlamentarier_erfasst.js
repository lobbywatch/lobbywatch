function parlamentarierErfasst(graphicIdName) {

  // Template: http://bl.ocks.org/mbostock/3883245
  var margin = {top: 20, right: 20, bottom: 30, left: 50},
	  width = jQuery(graphicIdName).width() - margin.left - margin.right,
	  height = /*jQuery(graphicIdName).height()*/ 250 - margin.top - margin.bottom;

	  // 2014-09-16 00:00:00
  var parseDate = d3.time.format("%Y-%m-%d %X").parse;

  var startDate = parseDate('2014-01-01 00:00:00');

  var x = d3.time.scale()
	  .range([0, width]);

  var y = d3.scale.linear()
	  .range([height, 0]);

  var xAxis = d3.svg.axis()
	  .scale(x)
	  .orient("bottom")
	  .ticks(d3.time.year, 1)
	  .tickFormat(d3.time.format("%Y"));

  var yAxis = d3.svg.axis()
	  .scale(y)
	  .orient("left");

  var line = d3.svg.line()
	  .x(function(d) { return x(d.date); })
	  .y(function(d) { return y(d.close); })
	  .interpolate("step-after");

  var svg = d3.select(graphicIdName).append("svg")
	  .attr("width", width + margin.left + margin.right)
	  .attr("height", height + margin.top + margin.bottom)
	.append("g")
	  .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

  d3.json("/de/data/interface/v1/json/table/parlamentarier/flat/list?limit=250&select_fields=freigabe_datum", function(error, rawdata) {
	if (error) throw error;

	var nesteddata = d3.nest()
	  .key(function(d) { return d.freigabe_datum; })
	  .sortKeys(d3.ascending)
	  .rollup(function(leaves) { return leaves.length; })
	  .entries(rawdata.data);

    console.log(nesteddata);

  //   data.forEach(function(d) {
  //     d.date = parseDate(d.date);
  //     d.close = +d.close;
  //   });

	// Filter unrelease parlamentarier
	if (nesteddata[nesteddata.length - 1].date == null) {
	  nesteddata.pop();
	}

  //   var data = [];
	var numReleased = 0;
	nesteddata.forEach(function(d) {
  // 	console.log(d.key + "->" + parseDate(d.key));
	  d.date = parseDate(d.key);
  //     d.date = d.key;
	  numReleased += +d.values;
	  d.close = numReleased;
	});

	var data = nesteddata;

	data.unshift({date: startDate, close: 0});
	data.push({date: Date.now(), close: numReleased});

	var targetData = [{date: startDate, close: 246}, {date: Date.now(), close: 246}]

  //   console.log(data);

	// http://stackoverflow.com/questions/4345045/javascript-loop-between-date-ranges
  //   var data = [];
  //   var now = new Date(Date.now());
  //   var daysOfYear = [];
  //   var i = 0;
  //   var numReleased = 0;
  //   for (var d = new Date(2012, 0, 1); d <= now; d.setDate(d.getDate() + 7), i++) {
  // // 	  daysOfYear.push(new Date(d));
  // 	  data.push({date: new Date(d), close: i});
  //   }

	x.domain(d3.extent(data, function(d) { return d.date; }));
  //   y.domain(d3.extent(data, function(d) { return d.close; }));
	y.domain([0, 250]);

	svg.append("g")
		.attr("class", "x axis")
		.attr("transform", "translate(0," + height + ")")
		.call(xAxis);

	svg.append("g")
		.attr("class", "y axis")
		.call(yAxis)
	  .append("text")
		.attr("transform", "rotate(-90)")
		.attr("y", 6)
		.attr("dy", ".71em")
		.style("text-anchor", "end")
		.text("Parlamentarier recherchiert");

	svg.append("path")
		.datum(data)
		.attr("class", "line")
		.attr("d", line);

	svg.append("path")
		.datum(targetData)
		.attr("class", "line")
		.style("stroke-dasharray", ("3, 3"))
		.attr("d", line);

  });
}
