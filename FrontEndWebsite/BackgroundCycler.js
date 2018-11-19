function cycleBackgrounds(int) {
	if (Count === 4){Count = 1;}
	document.body.style.background = "url(ImageGallery/bedfordcounty" + Count.toString() + ".png) no-repeat centre centre fixed"
}
 
// When the HTML Loads This Runs
window.onload = function(){
    setInterval(cycleBackgrounds(2), 5000);
};