window.onload = () => {
	fetch("json/sectors.json")
		.then(response => response.json())
		.then(data => insertSname(data));
	
	s_select.onchange = () => {
		
		let sector_id = -1;
		for (let i = 0; i < inputs.length; i++) {
			inputs[i].disabled = false;
		}

		for (let i = 0; i < s_options.length; i++) {
			if (s_options[i].selected) {
				sector_id = s_options[i].value;
			}
		}
		if(sector_id != -1) {
			document.getElementById("reset").click();
			c_select.innerHTML = "";
			for (let i = 0; i <= s_select.childElementCount; i++) {
				if(s_select.childNodes[i].value == sector_id)
					s_select.childNodes[i].selected = true;
			}
		}
		
		fetch("json/sectors.json")
			.then(response => response.json())
			.then(data => insertCatname(data, sector_id));

		fetch("json/sectors.json")
			.then(response => response.json())
			.then(data => setInputsTags(data, sector_id));
	}
	document.getElementById("reset").onclick = () => {
		c_select.innerHTML = "";
	} 

	
}

let inputs 		= document.getElementsByClassName("myinput");
let s_select 	= document.getElementById("sector");
let c_select 	= document.getElementById("category");
let s_options 	= document.getElementsByClassName("forsector");

function insertSname(data) {

	for (const key in data.sectors) {
		let option = document.createElement("option");
		option.className = "forsector";
		option.value 	 = data.sectors[key].sector_id;
		option.innerHTML = data.sectors[key].name;
		s_select.appendChild(option);	
	}
}

function insertCatname(data, index) {
	if(index === -1)
		return;
	for (const i in data.sectors) {
		if(data.sectors[i].sector_id == index) {
			for (const j in data.sectors[i].Categories) {
				let option		 = document.createElement("option");
				option.value 	 = data.sectors[i].Categories[j].category_id;
				option.innerHTML = data.sectors[i].Categories[j].name;
				c_select.appendChild(option);
			}
			break;
		}
		
	}
}

let quantity	= document.getElementById("quantity");
let address		= document.getElementById("address");
let description = document.getElementById("description");
let title 		=  document.getElementById("title");

function setInputsTags(data, index) {
	if(index === -1)
		return;
		for (const i in data.sectors) {
			if(data.sectors[i].sector_id == index) {
				quantity.hidden 	 = !data.sectors[i].quantity;
				address.hidden 		 = !data.sectors[i].address;
				description.hidden 	 = !data.sectors[i].Description;
				title.hidden 		 = !data.sectors[i].title.set;
				document.getElementById("title_label").innerHTML = data.sectors[i].title.name;
				break;
			}
		}
		if(quantity.hidden) {
			quantity.value = 1;
			quantity.required = false;
		}
		else {
			quantity.value = null;
			quantity.required = true;
		}

		if(address.hidden) {
			address.value = "";
			address.required = false;
		}
		else {
			address.value = null;
			address.required = true;
		}

		if(description.hidden) {
			description.innerHTML = "";
		}
		else {
			description.innerHTML = "Description...";
		}

		if(title.hidden) {
			title.value = "";
			title.required = false;
		}
		else {
			title.value = null;
			title.required = true;
		}
}