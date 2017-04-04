$( document ).ready(function() {
	
	//=============================
	//== FOOD SERVICE FORM SUBMITION
	//== FOOD SERVICE FORM SUBMITION
	//== FOOD SERVICE FORM SUBMITION	
	$( "#v-fs-form" ).submit(function( event ) {
	var qtyValue = 0;
	var allCorrect = 0;									
	var selectMore = 0;			
				
		//=============================
		//=== CHECK IF NOTHING SELECTED
		//=== CHECK IF NOTHING SELECTED
		//=== CHECK IF NOTHING SELECTED				
		$('input[type=number]').each(function() {
			qtyValue = this.value;					
			if(qtyValue != 0){
				allCorrect = 1; 
			}
			selectMore += Number(qtyValue); // total qty
		});
					
			if(allCorrect == 0){
				$( "#error" ).text( 'Error: choose product please' ).show().delay(2300).fadeOut(1000);
				allCorrect = 0;
			}
			if(selectMore < 3 && selectMore > 0){
				$( "#error" ).text( 'Error: Minimum order 1 tie (any 3 cartons)' ).show().delay(2300).fadeOut(1000);
				allCorrect = 0;
			}
			//=== END CHECKING 
			//=== END CHECKING 
			//=== END CHECKING 
			//================
				
	  if ( allCorrect == 1 ) {
		return true;		
	  }	
	event.preventDefault();
	});

//==============================
//== NEED NEW ACCOUNT + DISCOUNT
//== NEED NEW ACCOUNT + DISCOUNT
//== NEED NEW ACCOUNT + DISCOUNT
//== NEED NEW ACCOUNT + DISCOUNT
/*
var form;
$('form').on('submit', function () {
		var form = $('#'+this.id);		
	});
*/

$('#v-fs-page-top-blocks input[type=submit]').add('#v-fs-discount-block input[type=submit]').one( "click",function() {
	var form = $(this).closest('form').attr('id')
	form = $('#'+form); // get form id		
	var buttonId = this.id; // get button id
	
    // Set up an event listener for the contact form.
    $(form).submit(function(e) {	
	  $('#'+buttonId).addClass('v-is-loading').val('');
        // Stop the browser from submitting the form.
        e.preventDefault();

        // Serialize the form data.
        var formData = $(form).serialize();
        var url = $(form).attr('action');

        // Submit the form using AJAX.
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            dataType: 'html'
        })
            .done(function(response) {
				
		if(response.length > 100){// very long response usually email is not correct
			$('.modal').css('display','block');
			$('.modal-content').html('Provide a valid entry, please'); 
			$('#'+buttonId).removeClass('v-is-loading').val('Submit');
		}


		var json_ = $.parseJSON(response);			

			if(json_.yes1 == 'yes1'){
				window.location = "food-service/";
			}
			if(json_.no1 == 'not_approved'){// login to food service
				$('#err1').html('Sorry, your account is waiting for approval. Come back later please');
				$('#'+buttonId).removeClass('v-is-loading').val('Submit');
			}
			if(json_.no1 == 'no1'){// login to food service
					attemtsCountDown(form,json_.attempts,'#err1','This email is not registered');// code is not correct and attemts coundDown
				$('#'+buttonId).removeClass('v-is-loading').val('Submit');
			}


			if(json_.no_hack == 'no_hack'){
				$('#err7').html('<div style="margin-top:30px;color:red">Too many attempts, come back later</div>');
				$('#err4, #err3').css('display','none');
				$('#'+buttonId).removeClass('v-is-loading').val('Submit');
				form.css('display','none')
			}					
				if(json_.yes2 == 'yes2'){
					$('.ok').html('<p class="v-success-message">Thank you! <br />Registration forms <br />have been emailed to you<br /><br /><a href="http://rocketproducts.co.nz/fb" target="_blank" >Like us</a></p>');
				}					
				if(json_.no2 == 'no2'){
					$('#err2').html('Enter a valid email address please');
					$('#'+buttonId).removeClass('v-is-loading').val('Submit');
				}
			if(json_.no3 == 'no3'){ // shop page
					attemtsCountDown(form,json_.attempts,'#err3','Code is not correct. Try again please.');// code is not correct and attemts coundDown
				$('#'+buttonId).removeClass('v-is-loading').val('Submit');
			}
			if(json_.no3 == 'expired'){// login to food service
					$('#err3').html('Your code has expired, sorry');
					$('#'+buttonId).removeClass('v-is-loading').val('Submit');
				}
			if(json_.yes3 == 'yes3'){
				window.location = "shop/";
			}
				if(json_.no4 == 'no4'){ // food service discount
						attemtsCountDown(form,json_.attempts,'#err4','Code is not correct. Try again please.');// code is not correct and attemts coundDown
					$('#'+buttonId).removeClass('v-is-loading').val('Submit');
				}
				if(json_.no4 == 'expired'){// login to food service
					$('#err4').html('Your code has expired, sorry');
					$('#'+buttonId).removeClass('v-is-loading').val('Submit');
				}
				if(json_.yes4 == 'yes4'){
					window.location = "food-service/";
				}

			if(json_.no5 == 'no5'){ // presenters
					attemtsCountDown(form,json_.attempts,'#err5','Wrong password. Try again please.');// code is not correct and attemts coundDown
					$('#'+buttonId).removeClass('v-is-loading').val('Submit');
			}
			if(json_.yes5 == 'yes5'){
					window.location = "presenters/";
			}
            })
            .fail(function(data) {				
				alert('Error: nothing happened, try again please');			
				$('#'+buttonId).removeClass('v-is-loading').val('Submit');
            });
    });
});

//==============================================================================	
// this F shows error message and then shows sqares how many login attempts left
// forks for 3 forms: login to food service, food service discount and shop discount	
function attemtsCountDown(form,attemts,error,message){		
	if(attemts < 5){
		$(error).html(message);
	}
	if(attemts >= 5){
			var squaresUp = 0 + (5-attemts);
				squaresUp = 21 * squaresUp;
		$(error).html(message +
		'<div style="background:url('+jsBaseUrl+'/images/att_left.gif) no-repeat top '+ squaresUp +'px left 100px ">Attempts left:</div>');
	}
	if(attemts == 10){
		form.css('display','none');
		$(error).html('<div style="margin-top:30px;color:red">Too many attempts, wait for 10 min</div>');
	}
}
// END F	
// END F	
// END F	
//======
	
	//=============================================
	//=== SHOW VIDEO BLOCK NICELLY TO AVOID JUMPING	
	$('#v-food-service-vid').animate({ opacity: 1 },500);
	
	
	//============================================
	//=== ALWAYS SHOW SUBMIT BUTTON WHEN SCROLLING	
	//=== ALWAYS SHOW SUBMIT BUTTON WHEN SCROLLING	
	//=== ALWAYS SHOW SUBMIT BUTTON WHEN SCROLLING		
	var fsFormH = $('#v-fs-form').height();
	var containerWidth = $('#v-fs-submit-block').width();
	/*
	var x = $("#v-fs-form").position();
	var topForm = x.top;
	*/
	var fullHeight = parseInt(fsFormH + 300);
	$(window).scroll(function(){
	if ($(window).scrollTop() > 800){			
	    $('#v-fs-submit-block .content').addClass('fixButton');
	    $('#v-fs-submit-block .content').css('width',containerWidth+'px');

	}
		if ($(window).scrollTop() < 800){			
	    $('#v-fs-submit-block .content').removeClass('fixButton');
	}
		if ($(window).scrollTop() > fullHeight){			
	    $('#v-fs-submit-block .content').removeClass('fixButton');
	}
	});
	
	//===============================================================
	// general for all modal cases... just close it on click anywhere
	// general for all modal cases... just close it on click anywhere
	// general for all modal cases... just close it on click anywhere
	$('.modal').click(function(){
		$(this).css('display','none')
	});
 }); // THE END !!!!

	
