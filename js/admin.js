window.onload = function () {
	URLGenerate();
	calculateKeyword();
	calculateDescript();
	var imgUrlOnl = document.getElementById('extra_img').value;
	var currentImgOnl = document.getElementById('extra_cover');
	if (imgUrlOnl != '') {
		currentImgOnl.style.backgroundImage = 'url('+imgUrlOnl+')';
	}
	document.getElementById('title').addEventListener("keyup", URLGenerate, false);
	document.getElementById('extra_album').addEventListener("keyup", URLGenerate, false);
	document.getElementById('extra_year').addEventListener("keyup", URLGenerate, false);
	document.getElementById('extra_country').addEventListener("keyup", URLCountryGenerate, false);
	document.getElementById('extra_img').addEventListener("keyup", IMGPaste, false);
	document.getElementById('title').addEventListener("keyup", ALTGenerate, false);
	document.getElementById('extra_album').addEventListener("keyup", ALTGenerate, false);
	document.getElementById('extra_year').addEventListener("keyup", ALTGenerate, false);
	document.getElementById('deskey_description').addEventListener("keyup", calculateDescript, false);
	document.getElementById('deskey_keywords').addEventListener("keyup", calculateKeyword, false);
	document.getElementById('descript_generate').addEventListener("click", descriptGenerate, false);
	document.getElementById('keyword_generate').addEventListener("click", keywordGenerate, false);
	document.getElementById('songs_get_json').addEventListener("click", songsGetJson, false);
};
function URLCountryGenerate() {
	var extra_country = document.getElementById('extra_country').value;
	var urlIdCountry = translite(extra_country).replace(/-{2,}/g, '-');
	document.getElementById('extra_country_id').value = urlIdCountry;
}
function URLGenerate() {
	var title = document.getElementById('title').value;
	var extra_album = document.getElementById('extra_album').value;
	var extra_year = document.getElementById('extra_year').value;
	if (translite(extra_album).length > 0) extra_album = '-'+extra_album;
	if (extra_year.length > 0) extra_year = '-'+extra_year;
	var url = translite(title)+translite(extra_album)+extra_year;
	document.getElementById('extra_url').value = url.replace(/-{2,}/g, '-');
	document.getElementById('extra_band_id').value = translite(title).replace(/-{2,}/g, '-');
	//document.getElementById('editable-post-name-full').innerText = translite(title)+translite(extra_album)+extra_year;
}
function ALTGenerate() {
	if (document.getElementById('extra_img').value == '') {
		document.getElementById('extra_alt').value = '';
	}
	else {
		var titleALT = document.getElementById('title').value;
		var extra_albumALT = document.getElementById('extra_album').value;
		var extra_yearALT = document.getElementById('extra_year').value;
		if (extra_albumALT.length > 0) extra_albumALT = ' - '+extra_albumALT;
		if (extra_yearALT.length > 0) extra_yearALT = ' - '+extra_yearALT;
		document.getElementById('extra_alt').value = titleALT+extra_albumALT+extra_yearALT;
	}
}
function translite(str) {
	var res = str.toLowerCase();
	var arr={'а':'a', 'б':'b', 'в':'v', 'г':'g', 'ѓ':'g', 'ґ':'g', 'д':'d', 'е':'e', 'ё':'yo', 'є':'ye', 'ж':'zh', 'з':'z', 'и':'i', 'й':'j', 'ј':'j', 'і':'i', 'ї':'yi', 'к':'k', 'ќ':'k', 'л':'l', 'љ':'l', 'м':'m', 'н':'n', 'њ':'n', 'о':'o', 'п':'p', 'р':'r', 'с':'s', 'т':'t', 'у':'u', 'ў':'u', 'ф':'f', 'х':'h', 'ц':'ts', 'ч':'ch', 'ш':'sh', 'щ':'shh', 'ъ':'', 'ы':'y', 'ь':'', 'э':'e', 'ю':'yu', 'я':'ya', ' ':'-'};
	var replacer=function(a){return arr[a]||a};
	res = res.replace(/[а-яёѓґєіїќљњў ]/g, replacer);
	res = res.replace(/[^a-z0-9-]/g, '');
	return res;
}
function IMGPaste() {
	ALTGenerate();
	var imgUrl = document.getElementById('extra_img').value;
	var currentImg = document.getElementById('extra_cover');
	if (imgUrl == '') {
		currentImg.removeAttribute('style');
	}
	else {
		currentImg.style.backgroundImage = 'url('+imgUrl+')';
	}
}
function keywordGenerate() {
	document.getElementById('deskey_keywords').value = document.getElementById('tax-input-post_tag').value;
	calculateKeyword();
}
function descriptGenerate() {
	document.getElementById('deskey_description').value = document.getElementById('excerpt').value;
	calculateDescript();
}
function calculateDescript() {
	document.getElementById('descNum').innerHTML = document.getElementById('deskey_description').value.length;
	return false;
}
function calculateKeyword() {
	document.getElementById('keyNum').innerHTML = document.getElementById('deskey_keywords').value.length;
}
function songsGetJson() {
	document.getElementById('songs_area').value = '[{"name":"...","time":"..."}, {"name":"...","time":"..."}]';
}