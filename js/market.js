window.onload = () => { 
	fetch("json/sectors.json")
		.then(response => response.json())
		.then(data => insertSname(data));
}

let sects = document.getElementsByClassName("card-text s_id");

function insertSname(data) {
	for (let i = 0; i < sects.length; i++) {
		let s_id = sects[i].innerHTML;
		for (const key in data.sectors) {
			if(data.sectors[key].sector_id == s_id) {
				sects[i].innerHTML = data.sectors[key].name;
				break;
			}
		}
	}
	
}