$(document).ready(function() {
	clockUpdate();
	setInterval(clockUpdate, 1000);
	setTime();
	setInterval(clockUpdate, 1000 * 60 * 60);

	document.getElementById("open_menu").onclick = () => {
		menu.style.left = "0%";
	}

	document.getElementById("close_menu").onclick = () => {
		menu.style.left = "-100%";
	}
})
const tm = document.getElementById("setime");
const menu = document.getElementById("menu__box");
const op = document.getElementsByTagName("option");
  
  function clockUpdate() {
	var date = new Date();
	function addZero(x) {
	  if (x < 10) {
		return x = '0' + x;
	  } else {
		return x;
	  }
	}
  
	var h = addZero(date.getHours());
	var m = addZero(date.getMinutes());
	var s = addZero(date.getSeconds());
	
	var dd = addZero(date.getDate());
	var mm = addZero(date.getMonth());
	var yy = addZero(date.getFullYear());

	$(tm.children[1]).text(dd + '/' + mm + '/' + yy);
	$(tm.children[2]).text(h + ':' + m + ':' + s);
}

function setTime() {
	let currTm = new Date();
	let mes = "";
	if (5 <= currTm.getHours() && currTm.getHours() <= 11) {
		mes = "Good morning";
	}
	else if(12 <= currTm.getHours() && currTm.getHours() <= 14) {
		mes = "Good noon";
	}
	else if(15 <= currTm.getHours() && currTm.getHours() <= 17) {
		mes = "Good afternoon";
	}
	else if(18 <= currTm.getHours() && currTm.getHours() <= 21) {
		mes = "Good evening";
	}
	else {
		mes = "Good night";
	}
	tm.children[0].innerHTML = mes;
}
