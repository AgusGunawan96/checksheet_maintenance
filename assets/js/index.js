$(function () {
	"use strict";
	// chart 1
	var options = {
		series: [
			{
				name: "Audience",
				data: [427, 613, 901, 257, 505, 414, 671, 160, 440],
			},
		],
		chart: {
			type: "area",
			height: 80,
			toolbar: {
				show: false,
			},
			zoom: {
				enabled: false,
			},
			dropShadow: {
				enabled: true,
				top: 3,
				left: 14,
				blur: 4,
				opacity: 0.12,
				color: "#673ab7",
			},
			sparkline: {
				enabled: true,
			},
		},
		markers: {
			size: 0,
			colors: ["#673ab7"],
			strokeColors: "#fff",
			strokeWidth: 2,
			hover: {
				size: 7,
			},
		},
		dataLabels: {
			enabled: false,
		},
		stroke: {
			show: true,
			width: 3,
			curve: "smooth",
		},
		colors: ["#673ab7"],
		xaxis: {
			categories: [
				"Jan",
				"Feb",
				"Mar",
				"Apr",
				"May",
				"Jun",
				"Jul",
				"Aug",
				"Sep",
				"Oct",
				"Nov",
				"Dec",
			],
		},
		fill: {
			opacity: 1,
		},
		tooltip: {
			theme: "dark",
			fixed: {
				enabled: false,
			},
			x: {
				show: false,
			},
			y: {
				title: {
					formatter: function (seriesName) {
						return "";
					},
				},
			},
			marker: {
				show: false,
			},
		},
	};
	var chart = new ApexCharts(document.querySelector("#chart1"), options);
	chart.render();
	// chart 2
	var options = {
		series: [
			{
				name: "Impressions",
				data: [427, 613, 901, 257, 505, 414, 671, 160, 440],
			},
		],
		chart: {
			type: "area",
			height: 80,
			toolbar: {
				show: false,
			},
			zoom: {
				enabled: false,
			},
			dropShadow: {
				enabled: true,
				top: 3,
				left: 14,
				blur: 4,
				opacity: 0.12,
				color: "#32ab13",
			},
			sparkline: {
				enabled: true,
			},
		},
		markers: {
			size: 0,
			colors: ["#32ab13"],
			strokeColors: "#fff",
			strokeWidth: 2,
			hover: {
				size: 7,
			},
		},
		dataLabels: {
			enabled: false,
		},
		stroke: {
			show: true,
			width: 3,
			curve: "smooth",
		},
		colors: ["#32ab13"],
		xaxis: {
			categories: [
				"Jan",
				"Feb",
				"Mar",
				"Apr",
				"May",
				"Jun",
				"Jul",
				"Aug",
				"Sep",
				"Oct",
				"Nov",
				"Dec",
			],
		},
		fill: {
			opacity: 1,
		},
		tooltip: {
			theme: "dark",
			fixed: {
				enabled: false,
			},
			x: {
				show: false,
			},
			y: {
				title: {
					formatter: function (seriesName) {
						return "";
					},
				},
			},
			marker: {
				show: false,
			},
		},
	};
	var chart = new ApexCharts(document.querySelector("#chart2"), options);
	chart.render();
	// chart 3
	var options = {
		series: [
			{
				name: "Engagement",
				data: [427, 613, 901, 257, 505, 414, 671, 160, 440],
			},
		],
		chart: {
			type: "area",
			height: 80,
			toolbar: {
				show: false,
			},
			zoom: {
				enabled: false,
			},
			dropShadow: {
				enabled: true,
				top: 3,
				left: 14,
				blur: 4,
				opacity: 0.12,
				color: "#f02769",
			},
			sparkline: {
				enabled: true,
			},
		},
		markers: {
			size: 0,
			colors: ["#f02769"],
			strokeColors: "#fff",
			strokeWidth: 2,
			hover: {
				size: 7,
			},
		},
		dataLabels: {
			enabled: false,
		},
		stroke: {
			show: true,
			width: 3,
			curve: "smooth",
		},
		colors: ["#f02769"],
		xaxis: {
			categories: [
				"Jan",
				"Feb",
				"Mar",
				"Apr",
				"May",
				"Jun",
				"Jul",
				"Aug",
				"Sep",
				"Oct",
				"Nov",
				"Dec",
			],
		},
		fill: {
			opacity: 1,
		},
		tooltip: {
			theme: "dark",
			fixed: {
				enabled: false,
			},
			x: {
				show: false,
			},
			y: {
				title: {
					formatter: function (seriesName) {
						return "";
					},
				},
			},
			marker: {
				show: false,
			},
		},
	};
	var chart = new ApexCharts(document.querySelector("#chart3"), options);
	chart.render();
	// chart 4
	var options = {
		series: [
			{
				name: "New Visitors",
				data: [94, 55, 57, 56, 61, 58, 63, 60, 66, 75],
			},
			{
				name: "Returning Visitors",
				data: [-76, -85, -101, -98, -87, -105, -91, -114, -94, -105],
			},
		],
		chart: {
			foreColor: "#9a9797",
			type: "bar",
			height: 350,
			stacked: true,
			toolbar: {
				show: false,
			},
		},
		plotOptions: {
			bar: {
				horizontal: false,
				columnWidth: "12%",
				endingShape: "rounded",
			},
		},
		legend: {
			position: "top",
			horizontalAlign: "left",
			offsetX: -20,
		},
		dataLabels: {
			enabled: false,
		},
		stroke: {
			show: true,
			width: 2,
			colors: ["transparent"],
		},
		colors: ["#6236af", "#f02769"],
		xaxis: {
			categories: [
				"Feb",
				"Mar",
				"Apr",
				"May",
				"Jun",
				"Jul",
				"Aug",
				"Sep",
				"Oct",
				"Nov",
				"Dec",
			],
		},
		fill: {
			opacity: 1,
		},
		grid: {
			show: true,
			borderColor: "#ededed",
			strokeDashArray: 4,
		},
		responsive: [
			{
				breakpoint: 480,
				options: {
					chart: {
						height: 310,
					},
					plotOptions: {
						bar: {
							columnWidth: "30%",
						},
					},
				},
			},
		],
	};
	var chart = new ApexCharts(document.querySelector("#chart4"), options);
	chart.render();
});
