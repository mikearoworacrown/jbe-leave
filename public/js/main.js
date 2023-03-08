/*****Navbar Collapse*******/
let collapseButton = document.getElementById("jbe__navbar-collapse");

function collapse() {
  if (collapseButton.className === "jbe__navbar-collapse") {
    collapseButton.className += " collapse";
  } else {
    collapseButton.className = "jbe__navbar-collapse";
  }
}

/********************Login Password show/hide **************************/
let passwdField = document.querySelector(".jbe__login input[type='password']"),
toggleBtn = document.querySelector(".jbe__login .form-group i");
if(toggleBtn) {
  toggleBtn.onclick = () => {
      if(passwdField.type == "password"){
          passwdField.type = "text";
          toggleBtn.classList.add("active");
      }else{
          passwdField.type = "password";
          toggleBtn.classList.remove("active");
      }
  }
}


//Commencing Date
var dateToday = new Date();
$(function() {
  $("#datepicker1").datepicker({ dateFormat: 'dd-mm-yy', minDate: dateToday });
});

//Ending Date
$(function() {
  $("#datepicker2").datepicker({ dateFormat: 'dd-mm-yy', minDate: dateToday });
});

//Resumption Date
$(function() {
  $("#datepicker3").datepicker({ dateFormat: 'dd-mm-yy', minDate: dateToday });
});


/*******************Register Password show/hide **************************/
let passwdFieldReg = document.querySelector(".jbe__register input[type='password']"),
toggleBtnReg = document.querySelector(".jbe__register .form-group i");
if(toggleBtnReg) {
  toggleBtnReg.onclick = () => {
      if(passwdFieldReg.type == "password"){
          passwdFieldReg.type = "text";
          toggleBtnReg.classList.add("active");
      }else{
          passwdFieldReg.type = "password";
          toggleBtnReg.classList.remove("active");
      }
  }
}
