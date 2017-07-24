$.post('getTeams.php', {}, function(result) { 
   alert(result); 
});
setInterval(updateTeams, 1000);

function updateTeams(){
	$.post('getTeams.php', {}, function(result) { 
	   alert(result); 
	});
}