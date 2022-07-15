window.onload = () => {
	edit.onclick = () => {
		for (let index = 0; index < inputs.length; index++) {
			if(inputs[index].disabled) 
				inputs[index].disabled = false;
		}
		save.hidden = false;
		reset.hidden = false;
		img.hidden = false;
		cancel.hidden = false;
		edit.hidden = true;
	}
	cancel.onclick = () => {
		reset.click();
		for (let index = 0; index < inputs.length; index++) {
			if(!inputs[index].disabled) 
				inputs[index].disabled = true;
		}
		save.hidden = true;
		reset.hidden = true;
		img.hidden = true;
		cancel.hidden = true;
		edit.hidden = false;
	}
	fetch("json/sectors.json")
		.then(response => response.json())
		.then(data => insertSname(data));
	
	setTimeout(removealert, 10000);
		
	
	
}

function insertSname(data) {
	let s_id = common.innerHTML;
	for (const key in data.sectors) {
		if (data.sectors[key].sector_id == s_id) {
			common.innerHTML = data.sectors[key].name;
		}
	}
}

const save		= document.getElementById("save");
const reset		= document.getElementById("reset");
const edit 		= document.getElementById("edit");
const inputs 	= document.getElementsByClassName("myinput");
const img 		=  document.getElementById("img");
const cancel 	= document.getElementById("cancel");
const all 		= document.getElementById("alert");
const common	= document.getElementById("s_id");


function removealert() {
	if(all)
		all.hidden = true;
}
