//this variable tracks the user's current menu (left of the main menu screen)
var currentMenu = 0;
var maxMenu = 1;
updateMenu();

function updateMenu(){

	//0 - Event Selection
	if(currentMenu == 0){
		document.getElementById("menus").innerHTML = 
			`<table style="width:100%;">
				<tr>
					<td>
						<img src="../imgs/arrowLeft.png" width="20px" height="20px" onclick="left();" />
					</td>
					<td>
						<form action="eventSelection.php">
							<input class="bigButton" type="submit" value="Event Selection" />
						</form>
					</td>
					<td>
						<img src="../imgs/arrowRight.png" width="20px" height="20px" onclick="right();" />
					</td>
				</tr>
			</table>`;
	}

	//1 - Minutes
	if(currentMenu == 1){
		document.getElementById("menus").innerHTML = 
			`<table style="width:100%;">
				<tr>
					<td>
						<button onclick="left();">\<</button>
					</td>
					<td>
						<form action="minutes.php">
							<input class="bigButton" type="submit" value="Minutes" />
						</form>
					</td>
					<td>
						<button onclick="right();">\></button>
					</td>
				</tr>
			</table>`;
	}

}

function left(){
	currentMenu--;
	if(currentMenu < 0){
		currentMenu = maxMenu;
	}
	updateMenu();
}

function right(){
	currentMenu++;
	if(currentMenu > maxMenu){
		currentMenu = 0;
	}
	updateMenu();
}