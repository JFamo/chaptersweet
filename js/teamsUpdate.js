//$.post('getTeams.php', {}, function(result) { 
//   alert(result); 
//});

$("#events").load('../php/getTeams.php');
$("#eventPoints").load('../php/getEventPoints.php');

setInterval(updateTeams, 500);

function updateTeams(){
	$("#changed").load('../php/getChanges.php');
	$("#eventPoints").load('../php/getEventPoints.php');
	if(document.getElementById('changed').innerHTML.indexOf('yes') != -1){
		$("#events").load('../php/getTeams.php');
	}
}