const formProcessLeaveRequest = document.querySelector(".leave-request-form");
const submitProcessBtnLeave = document.querySelector(".jbe__processLeave-submit");

if(formProcessLeaveRequest){
    formProcessLeaveRequest.onsubmit = (e) => {
        e.preventDefault();
    }
}

if(submitProcessBtnLeave) {
    submitProcessBtnLeave.onclick = () => {
        let loading = document.querySelector(".loader");
        loading.style.display = "block";
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../ajax_php/processleave.php", true);
        xhr.onload = () => {
            if(xhr.readyState === XMLHttpRequest.DONE){
                if(xhr.status === 200){
                    let data = xhr.response;
                    let dataParsed = JSON.parse(data);
                    // console.log(dataParsed);
                    if (dataParsed['status'] == "success" || dataParsed['status'] == "error")  {
                        location.href = "../"
                    }
                }
            }
        }
        //we have to send the form data through AJAX to php
        let formData = new FormData(formProcessLeaveRequest); //creating new formData object
        xhr.send(formData); //sending the form data to php
    }
}
