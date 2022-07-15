
var opts = {
	angle: -0.25,
	lineWidth: 0.2,
	radiusScale: 0.9,
	pointer: {
		length: 0.6,
		strokeWidth: 0.05,
		color: '#000000'
	},
	staticLabels: {
		font: "17px sans-serif",
		labels: [-100, -50, 0, 50, 150],
		fractionDigits: 0
	},
	staticZones: [
		{ strokeStyle: "#ff0000", min: -100, max: -50},
		{ strokeStyle: "#fff44f", min: -50, max: 0 },
		{ strokeStyle: "#0096FF", min: 0, max: 50 },
		{ strokeStyle: "#00ff00", min: 50, max: 150 },
	],
	limitMax: false,
	limitMin: false,
	highDpiSupport: true
};
var target = document.getElementById('demo');
var gauge = new Gauge(target).setOptions(opts);
gauge.maxValue = 150;
gauge.setMinValue(-100); 
gauge.animationSpeed = 20;


let Carr = document.getElementsByClassName("cost");
let Varr = document.getElementsByClassName("value");
let Csum = 0, Vsum = 0;


$(document).ready(function() {
	setGmeter();
})

function setGmeter() {
	for (let i = 0; i < Carr.length; i++) {
		Csum += parseInt(Carr[i].innerHTML);
	}
	for (let i = 0; i < Varr.length; i++) {
		Vsum += parseInt(Varr[i].innerHTML);
	}
	gauge.set(percIncrease(Csum, Vsum));
	gauge.setTextField(document.getElementById("preview-textfield"));
}
	
function percIncrease(a, b) {
	let percent;
	if(b !== 0) {
		if(a !== 0) {
			percent = (b - a) / a * 100;
		} else {
			percent = b * 100;
		}
	} else {
		percent = - a * 100;
	}
	return Math.floor(percent);
}


