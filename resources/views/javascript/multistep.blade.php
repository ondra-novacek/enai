<script type="text/javascript">
window.onload = function() {
//change minor
//copyright w
var currentOnePart = 0; // Current OnePart is set to be the first OnePart (0)
showOnePart(currentOnePart); // Display the current OnePart

function showOnePart(n) {
  // This function will display the specified OnePart of the form ...
  var x = document.getElementsByClassName("onepart");
  //div is block element (not inline) => therefore its "block"
  x[n].style.display = "block";
  //to the top
  scroll(0,0);
  // display cases: first part, last submit part, other parts(nothing is changing)
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    // document.getElementById("nextBtn").innerHTML = "Submit";
    // alert(n);
    document.getElementById("nextBtn").innerHTML = "Submit";
    console.log(document.getElementById("nextBtn").innerHTML);

    // document.getElementById("nextBtn").onclick = function(){
        
    //     console.log(n);
    // };
    
  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  // little fun to display on what step user is
  fixStepIndicator(n)
}

function nextPrev(n) {
  // This function will figure out which OnePart to display
  var x = document.getElementsByClassName("onepart");
  
  // Exit the function if any field in the current OnePart is invalid:
//UNCOMMENT LATER ON! 
  // RadioValidator();  
  if (n == 1 && !RadioValidator())
  { 
    return false;
  }
  if (n == -1)
  { 
    $('.alert').removeClass('message-show').addClass('message-hide');
  }

  // Hide the current OnePart:
  x[currentOnePart].style.display = "none";
  // Increase or decrease the current OnePart by 1:
  currentOnePart = currentOnePart + n;
  // if you have reached the end of the form... :
  if (currentOnePart >= x.length) {
    //...the form gets submitted:
    document.getElementById("surForm").submit();
    return false;
  }
  // Otherwise, display the correct OnePart:
  showOnePart(currentOnePart);
}
window.nextPrev = nextPrev;

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("onepart");
  y = x[currentOnePart].getElementsByTagName("input");
  // y = x[currentOnePart].getElementsByName("radio");
  // A loop that checks every input field in the current OnePart:
  for (i = 0; i < y.length; i++) {
// console.log(y[i].value);    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false:
      valid = false;
    }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentOnePart].className += " finish";
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class to the current step:
  x[n].className += " active";
}

function RadioValidator()
{
  $('.alert').removeClass('message-show').addClass('message-hide');
  var x = document.getElementsByClassName("onepart");
  var AllOnePartElements = x[currentOnePart].getElementsByTagName("input");
  // var AllOnePartElements = x[currentOnePart]; 
  var ShowAlert = '';
  var notAnswered = 0;
  // console.log(AllOnePartElements.length);
  for (i = 0; i < AllOnePartElements.length; i++)
  {
    // console.log(AllOnePartElements[i].type);
    if (AllOnePartElements[i].type == 'radio')
    {
        var ThisRadio = AllOnePartElements[i].name;
        // console.log(ThisRadio);
        // console.log(AllOnePartElements[i]);
        var ThisChecked = 'No';
        var AllRadioOptions = document.getElementsByName(ThisRadio);
        // console.log(AllRadioOptions);
        for (x = 0; x < AllRadioOptions.length; x++)
        {
              if (AllRadioOptions[x].checked && ThisChecked == 'No')
              {
                  ThisChecked = 'Yes';
                  break;
              } 
        }
        var AlreadySearched = ShowAlert.indexOf(ThisRadio);
        if (ThisChecked == 'No' && AlreadySearched == -1)
        {
          notAnswered++;
          //not neccesary
        ShowAlert = ShowAlert + ThisRadio + ' radio button must be answered\n';
        }  
    }
  }
  if (ShowAlert != '')
  {
    // alert(ShowAlert);
    $('.alert').removeClass('message-hide').addClass('message-show');
    if (notAnswered == 1){
      $('.alert').html(notAnswered + " question has not been answered.");
    }else{
      $('.alert').html(notAnswered + " questions have not been answered.");
    }
    return false;
  }
  else
  {
    return true;
  }
}

}

</script>