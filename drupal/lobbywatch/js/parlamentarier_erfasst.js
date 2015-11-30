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
	  .orient("left")
	  .tickValues([50, 100, 150, 200, 246]);

  var line = d3.svg.line()
	  .x(function(d) { return x(d.date); })
	  .y(function(d) { return y(d.released); })
	  .interpolate("step-after");

  var svg = d3.select(graphicIdName).append("svg")
	  .attr("width", width + margin.left + margin.right)
	  .attr("height", height + margin.top + margin.bottom)
	.append("g")
	  .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

  d3.json("/de/data/interface/v1/json/table/parlamentarier/flat/list?limit=600&select_fields=freigabe_datum", function(error, rawdata) {
	if (error) throw error;

	var nesteddata = d3.nest()
	  .key(function(d) { return d.freigabe_datum; })
	  .sortKeys(d3.ascending)
	  .rollup(function(leaves) { return leaves.length; })
	  .entries(rawdata.data);

 	var numReleased = 0;
	nesteddata.forEach(function(d) {
	  d.date = parseDate(d.key);
	  if (d.date != null) {
		numReleased += +d.values;
	  }
	  d.released = numReleased;
	});

	var data = nesteddata;

	// Filter unreleased parlamentarier
	if (data[data.length - 1].date == null) {
	  data.pop();
	}

	data.unshift({date: startDate, released: 0});
	data.push({date: Date.now(), released: numReleased});

	var targetData = [{date: startDate, released: 246}, {date: Date.now(), released: 246}]

	x.domain(d3.extent(data, function(d) { return d.date; }));
  //   y.domain(d3.extent(data, function(d) { return d.released; }));
	y.domain([0, 246]);

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
		.text("");

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
