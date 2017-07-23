alert("javascript called!");

$.post('getTeams.php', {}, function(result) { 
   alert(result); 
});
setInterval(updateTeams(), 1000);

function updateTeams(){
	alert("update called!");
	$.post('getTeams.php', {}, function(result) { 
	   alert(result); 
	});
}