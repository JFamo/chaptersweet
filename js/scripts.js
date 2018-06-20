//function to handle task form submit
function taskSubmit() {
  //grab the form
    var frm = document.getElementsByName('tasksForm')[0];
      submitValidateForm(frm);
}

//function to handle register form submit
/**
THIS IS TEMPORARILY OUT - TESTING AJAX
function registerSubmit() {
   //grab the form
      var frm = document.getElementsByName('registerForm')[0];
      submitValidateForm(frm);
}
**/

//function to handle login form submit
function loginSubmit() {
   //grab the form
      var frm = document.getElementsByName('loginForm')[0];
      submitValidateForm(frm);
}

//function to handle form validation and resetting
function submitValidateForm(frm){
   if(frm.checkValidity()) {
         //submit it to its action arg, aka taskWrite.php
         frm.submit();
         //clear the fields
         frm.reset();
      }
      else{
         //show error message - incomplete form
         window.alert("Invalid Submission!");
      }
      //do not refresh
      return false;
}


//Accordion tab stuff
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].onclick = function(){
        /* Toggle between adding and removing the "active" class,
        to highlight the button that controls the panel */
        this.classList.toggle("active");

        /* Toggle between hiding and showing the active panel */
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    }
}

/* attach a submit handler to the form
    $("#registerForm").submit(function(event) {

      /* stop form from submitting normally */
      /*event.preventDefault();

      /* get the action attribute from the <form action=""> element */
      /*var $form = $( this ),
          url = $form.attr( 'action' );

      /* Send the data using post with element id name and name2*/
      /*var posting = $.post( url, { fullname: $('#fullname').val(), username: $('#username').val(), password: $('#password').val(), email: $('#email').val(), grade: $('#grade').val(), code: $('#code').val(), chapter: $('#chapter').val() } );

      /* Alerts the results */
      /*posting.done(function( data ) {
          $('#resultRegister').html('<p class = "bodyTextType1">Successfully Registered</p>');
      });
    });
    */


//event confirmation email button
$('.confirmEventsButton').click(function(){
      var ajaxurl = '../php/confirmEventsEmail.php',
      data =  { };
      $.post(ajaxurl, data, function (response) {
          alert("Confirmation Emails Sent Successfully!");
      });
  });

//get info page tab variables
var filesDiv = document.getElementById("filesDiv");
var minutesDiv = document.getElementById("minutesDiv");
var announcementsDiv = document.getElementById('announcementsDiv');
var announceDiv = document.getElementById('postDiv');
var auditDiv = document.getElementById('auditDiv');
var rulesDiv = document.getElementById("rulesDiv");
var parliDiv= document.getElementById("parliDiv");
var currentDiv = filesDiv;
var inTransition = false;

//the function to slide tabs, given a target tab and a direction
function changeInfoTab(newDiv, dir){
  if(newDiv.style.display == "none"){
    inTransition = true;
    //slide current tab out
    if(dir == "left"){
      currentDiv.style.transform = "translate(100%)";
    }
    else{
      currentDiv.style.transform = "translate(-100%)";
    }
    //make current tab invisible
    setTimeout(function () {
      currentDiv.style.display = "none";
      //make new tab visible
      newDiv.style.display = "block";
      newDiv.style.transition = "0s";
      //set new tab on side of screen
      if(dir == "left"){
        newDiv.style.transform = "translate(-100%)";
      }
      else{
        newDiv.style.transform = "translate(100%)";
      }
      newDiv.style.transition = "0.5s ease-in-out";
      //slide new tab in
      setTimeout(function () {
          newDiv.style.transform = "translate(0%)";
          currentDiv = newDiv;
          setTimeout(function () {
              inTransition = false;
          }, 500);
      }, 500);
    }, 500);
  }
}

//get the direction to slide, given the current tab and the target tab
function getDirection(goDiv){
  var myval;
  var goval;
  //get the current tab value
  if(currentDiv == filesDiv){
    myval = 1;
  }
  if(currentDiv == rulesDiv){
    myval = 2;
  }
  if(currentDiv == minutesDiv){
    myval = 3;
  }
  if(currentDiv == announcementsDiv){
    myval = 4;
  }
  if(currentDiv == postDiv){
    myval = 5;
  }
  if(currentDiv == auditDiv){
    myval = 6;
  }
  if(currentDiv == parliDiv){
    myval = 7;
  }
  //get the target tab value
  if(goDiv == filesDiv){
    goval = 1;
  }
  if(goDiv == rulesDiv){
    goval = 2;
  }
  if(goDiv == minutesDiv){
    goval = 3;
  }
  if(goDiv == announcementsDiv){
    goval = 4;
  }
  if(goDiv == postDiv){
    goval = 5;
  }
  if(goDiv == auditDiv){
    goval = 6;
  }
  if(goDiv== parliDiv){
    myval = 7;
  }
  //get the direction
  if(myval < goval){
    return 'right';
  }
  if(myval > goval){
    return 'left';
  }
}

//function for Information Page files tab
function showFiles(){
  if(!inTransition){
    changeInfoTab(filesDiv, getDirection(filesDiv));
  }
}

//function for Information Page rules tab
function showRules(){
  if(!inTransition){
    changeInfoTab(rulesDiv, getDirection(rulesDiv));
  }
}

//function for Information Page minutes tab
function showMinutes(){
  if(!inTransition){
    changeInfoTab(minutesDiv, getDirection(minutesDiv));
  }
}

//function for Information Page announcements tab
function showAnnouncements(){
  if(!inTransition){
    changeInfoTab(announcementsDiv, getDirection(announcementsDiv));
  }
}

//function for Information Page announce tab
function showPost(){
  if(!inTransition){
    changeInfoTab(postDiv, getDirection(postDiv));
  }
}

//function for Information Page audit tab
function showAudit(){
  if(!inTransition){
    changeInfoTab(auditDiv, getDirection(auditDiv));
  }
}

//function for Information Page parli tab
function showParliamentarian(){
  if(!inTransition){
    changeInfoTab(parliDiv, getDirection(parliDiv));
  }
}

//Event selection Event Points move with page scroll
var eventPointsDiv = document.getElementById('eventPoints');

$(window).scroll(function (){

  if(eventPointsDiv === null){
  //nothing
  }
  else{

    if(document.body.scrollTop > 250){
      eventPointsDiv.style.position = "fixed";
      eventPointsDiv.style.backgroundColor = "#B60000";
      eventPointsDiv.style.borderRadius = "15px";
      eventPointsDiv.style.width = "175px";
      eventPointsDiv.style.height = "25px";
    }
    else{
      eventPointsDiv.style.position = "static";
      eventPointsDiv.style.backgroundColor = "transparent";
      eventPointsDiv.style.borderRadius = "0px";
      eventPointsDiv.style.width = "auto";
      eventPointsDiv.style.height = "auto";
    }

  }

});

$('input[class="noCheckBox"]').click(function(event) {
    var $checkbox = $(this);

    // Ensures this code runs AFTER the browser handles click however it wants.
    setTimeout(function() {
      //$checkbox.removeAttr('checked');
    }, 0);

    event.preventDefault();
    event.stopPropagation();
});

//function to update Settings on Admin Page
function updateSettings(infoPerm, emailPerm, blockPages, chapterCode, eventpointsPerm){

  var officerInfoPerm = document.getElementById('officerInfoPerm');
  officerInfoPerm.value = infoPerm;

  var officerEmailPerm = document.getElementById('officerEmailPerm');
  officerEmailPerm.value = emailPerm;

  var blockedPages = document.getElementById('blockedPages');
  blockedPages.value = blockPages;

  var cc = document.getElementById('chapterCode');
  cc.value = chapterCode;

  var evtp = document.getElementById('eventPerm');
  evtp.value = eventpointsPerm;

}

//enable popovers
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({container: 'body'});   
});

//View events scroll to events
$('.scroll').on('click', function(e) {
  e.preventDefault();
  var target = $(this).attr('href');
  var offset = 0;
  if ($(this).data('offset') != undefined) offset = $(this).data('offset');
  $.scrollTo(target, 300, { offset: -offset });
});