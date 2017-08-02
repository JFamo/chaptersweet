$.post('getTeams.php', {}, function(result) { 
   alert(result); 
});
setInterval(updateTeams, 500);

function updateTeams(){
	$("#events").load('../php/getTeams.php');
	$("#eventPoints").load('../php/getEventPoints.php');
}