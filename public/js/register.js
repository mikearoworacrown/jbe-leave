const formRegister = document.querySelector(".jbe__register-form form");
const submitBtnRegister = document.querySelector(".jbe__register .jbe__register-submit");

if(formRegister){
    formRegister.onsubmit = (e) => {
        e.preventDefault();
    }
}

if(submitBtnRegister){
    submitBtnRegister.onclick = () => {
        let email_phone = document.querySelector("#email-phone");
        let password = document.querySelector("#password");
        let errorMsgRegister = document.querySelector(".jbe__error-msg");
        let successMsgRegister = document.querySelector(".jbe__success-msg");
        let firstname = document.querySelector("#firstname");
        let lastname = document.querySelector("#lastname");
        let department = document.querySelector("#department");
        let jobTitle = document.querySelector("#job-title");
        let totalLeave = document.querySelector("#total-leave");
        let lineManager = document.querySelector("#line-manager");
        let lineManagerEmail = document.querySelector("#manager-email");
        let region = document.querySelector("#region");
        let branch = document.querySelector("#branch");
        let passwordMsg = document.querySelector("#jbe__password-msg");

        if(email_phone.value !== "" && password.value !== "" && firstname.value !== ""
        && lastname.value !== "" && department.value !== "" && jobTitle.value !== "" && totalLeave !== "" && lineManager.value !== ""
        && lineManagerEmail.value != "" && region.value !== "" && branch.value !== ""){

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax_php/register.php", true);
            xhr.onload = () => {
                if(xhr.readyState === XMLHttpRequest.DONE){
                    if(xhr.status === 200){
                        let data = xhr.response;
                        let dataParsed = JSON.parse(data);
                        if (dataParsed['status'] == "success") {
                            errorMsgRegister.style.display = "none";
                            successMsgRegister.textContent = dataParsed['message'];
                            successMsgRegister.style.display = "block";
                            email_phone.value = "";
                            password.value = "";
                            firstname.value = "";
                            lastname.value = "";
                            department.value = "";
                            jobTitle.value = "";
                            totalLeave.value = "";
                            lineManager.value = "";
                            lineManagerEmail.value = "";
                            region.value = "";
                            branch.value = "";
                            email_phone.focus();
                            console.log("Success");
                            console.log(dataParsed['message']);
                        }else if (dataParsed['status'] == "error"){
                            successMsgRegister.style.display = "none";
                            errorMsgRegister.textContent = dataParsed['message'];
                            errorMsgRegister.style.display = "block";
                            email_phone.focus();
                            console.log("Error");
                            console.log(dataParsed['message']);
                        }
                        
                    }
                }
            }
            ///we have to send the form data through AJAX to php
            let formData = new FormData(formRegister); //creating new formData object
            xhr.send(formData); //sending the form data to php
        }

    }
}