window.onload = () => {
	fetch("json/sectors.json")
		.then(response => response.json())
		.then(data => insertSname(data));
	
	fetch("json/sectors.json")
		.then(response => response.json())
		.then(data => insertgory(data));
	
	document.getElementById("delete").onclick = () => {
		console.log(1);
		for(let i = 0; i < trash.length; i++) {
			if (trash[i].hidden) {
				trash[i].hidden = false;
			}
			else {
				trash[i].hidden = true;
			}
		}
	}
}

let trash = document.getElementsByClassName("fa-duotone fa-trash-can fa-xl");
let category = document.getElementsByClassName("insertgory");
let sector	 = document.getElementsByClassName("s_id");
const sector_id = sector[0].innerHTML;

function insertSname(data) {
	for (const key in data.sectors) {
		if(data.sectors[key].sector_id == sector_id) {
			for (let i = 0; i < sector.length; i++) {
				sector[i].innerHTML = data.sectors[key].name;	
			}
			break;
		}
	}
}

function insertgory(data) {
	for (const i in data.sectors) {
		if(data.sectors[i].sector_id == sector_id) {
			for (const j in data.sectors[i].Categories) {
				for (let k = 0; k < category.length; k++) {
					if(category[k].innerHTML == data.sectors[i].Categories[j].category_id){
						category[k].innerHTML = data.sectors[i].Categories[j].name;
					}

					
				}
			}
		}
	}
}
