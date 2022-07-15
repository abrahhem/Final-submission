$(document).ready(function() {


	document.getElementById("options").onchange = () => {
		for (let index = 0; index < op.length; index++) {
			if (op[index].selected && index == 0) {
				chart.className = "charts-css line show-data-axes show-4-secondary-axes show-labels";
			}
			if (op[index].selected && index == 1) {
				chart.className = "charts-css area show-data-axes show-4-secondary-axes show-labels";
			}
			if (op[index].selected && index == 2) {
				chart.className = "charts-css column show-data-axes show-4-secondary-axes show-labels";
			}
		}
	}


})

const chart = document.getElementById("my-chart");