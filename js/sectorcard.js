window.onload = () => {
	fetch("json/sectors.json")
		.then(response => response.json())
		.then(data => insertSector(data));
}

let cards 		= document.getElementsByClassName("scard");
let containers	= document.getElementsByClassName("container js");

function insertSector(data) {
	console.log(cards[0].childNodes);
	console.log(containers[0].childNodes);
	if(cards.length > 0 && cards.length == containers.length) {
		for (let i = 0; i < cards.length; i++) {
			let sector_id = cards[i].childNodes[0].innerHTML;
			for (const key in data.sectors) {
				if (data.sectors[key].sector_id == sector_id) {
					cards[i].childNodes[1].src 				= data.sectors[key].img_url;
					containers[i].childNodes[0].innerHTML 	= '<br>' + data.sectors[key].name + '</br>';
				}
			}
		}
	}
}
