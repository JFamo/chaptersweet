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
      //don't refresh
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

/* attach a submit handler to the form */
    $("#registerForm").submit(function(event) {

      /* stop form from submitting normally */
      event.preventDefault();

      /* get the action attribute from the <form action=""> element */
      var $form = $( this ),
          url = $form.attr( 'action' );

      /* Send the data using post with element id name and name2*/
      var posting = $.post( url, { fullname: $('#fullname').val(), username: $('#username').val(), password: $('#password').val(), email: $('#email').val(), grade: $('#grade').val() } );

      /* Alerts the results */
      posting.done(function( data ) {
          $('#result').html('<p class = "bodyTextType1">Successfully Registered</p>');
      });
    });