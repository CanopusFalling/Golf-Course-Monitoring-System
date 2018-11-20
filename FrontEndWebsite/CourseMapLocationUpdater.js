function UpdateMap(){
	alert("Run");
	$.ajax({url: "CourseMapUpdater.php", success: function(result){
		$("InsertDiv").html(result);
    }});
}