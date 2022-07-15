
window.onload = () => {
	edit.onclick = () => {
		if (buttons.hidden)
			buttons.hidden = false;
		for (let index = 0; index < inputs.length; index++) {
			if (inputs[index].disabled)
				inputs[index].disabled = false;
		}
		
		sector.disabled = true;
		fetch("json/sectors.json")
			.then(response => response.json())
			.then(data => setInputsTags(data));
	}
	cancel.onclick = () => {
		reset.click();
		if (!buttons.hidden)
			buttons.hidden = true;
		for (let index = 0; index < inputs.length; index++) {
			if (!inputs[index].disabled)
				inputs[index].disabled = true;
		}
	}
	fetch("json/sectors.json")
		.then(response => response.json())
		.then(data => insertgory(data));

	fetch("json/sectors.json")
		.then(response => response.json())
		.then(data => insertSname(data));
	
	setTimeout(removealert, 10000);

}

let edit 		= document.getElementById("edit");
let buttons 	= document.getElementById("buttons");
let inputs 		= document.getElementsByClassName("myinput");
let cancel 		= document.getElementById("cancel");
let all 		= document.getElementById("alert");
let reset 		= document.getElementById("reset");
let c_select 	= document.getElementById("category");
let sector		= document.getElementById("sector");
let sector_id	= sector.value;
let selected_cate = document.getElementById("cate");

function insertgory(data) {
	let cate_id = selected_cate.value;
	c_select.innerHTML = "";
	for (const i in data.sectors) {
		if(data.sectors[i].sector_id == sector_id) {
			for (const j in data.sectors[i].Categories) {
				let option		 = document.createElement("option");
				option.value 	 = data.sectors[i].Categories[j].category_id;
				option.innerHTML = data.sectors[i].Categories[j].name;
				if (data.sectors[i].Categories[j].category_id == cate_id) {
	
					option.selected = true;
				}
				c_select.appendChild(option);
			}
			break;
		}
		
	}
}

function insertSname(data) {
	for (const key in data.sectors) {
		if(data.sectors[key].sector_id == sector_id) {
			sector.value = data.sectors[key].name;
			if(data.sectors[key].title.set) {
				document.getElementById("title_label").innerHTML = data.sectors[key].title.name;
			}
			break;
		}
	}
}

let quantity 	= document.getElementById("quantity");
let address 	= document.getElementById("address");
let description	= document.getElementById("description");
let title	 	= document.getElementById("title");

function setInputsTags(data) {

	for (const i in data.sectors) {
		if(data.sectors[i].sector_id == sector_id) {

			if(!data.sectors[i].quantity) {
				quantity.value = 1;
				quantity.required = false;
				quantity.disabled = true;
			}
			else {
				quantity.required = true;
				quantity.disabled = false;
			}

			if(!data.sectors[i].description) {
				description.required = false;
				description.disabled = true;
			}
			else {
				description.required = true;
				description.disabled = false;
			}

			if(!data.sectors[i].address) {
				address.value = "";
				address.required = false;
				address.disabled = true;
			}
			else {
				address.required = true;
				address.disabled = false;
			}
			
			if(!data.sectors[i].title.set) {
				title.value = "";
				title.required = false;
				title.disabled = true;
			}
			else {
				title.required = true;
				title.disabled = false;
			}
			
			break;
			
		}
	}
	
}

function removealert() {
	if(all)
		all.hidden = true;
}
