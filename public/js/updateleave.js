const formUpdateLeaveRequest = document.querySelector(".leave-request-form");
const submitUpdateBtnLeave = document.querySelector(".jbe__updateLeave-submit");

if(formUpdateLeaveRequest){
    formUpdateLeaveRequest.onsubmit = (e) => {
        e.preventDefault();
    }
}

if(submitUpdateBtnLeave) {
    submitUpdateBtnLeave.onclick = () => {
        let loading = document.querySelector(".loader");
        loading.style.display = "block";
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../ajax_php/updateleave.php", true);
        xhr.onload = () => {
            if(xhr.readyState === XMLHttpRequest.DONE){
                if(xhr.status === 200){
                    let data = xhr.response;
                    let dataParsed = JSON.parse(data);
                    console.log(dataParsed);
                    if (dataParsed['status'] == "success" || dataParsed['status'] == "error")  {
                        location.href = "../"
                    }
                }
            }
        }
        //we have to send the form data through AJAX to php
        let formData = new FormData(formUpdateLeaveRequest); //creating new formData object
        xhr.send(formData); //sending the form data to php
    }
}
