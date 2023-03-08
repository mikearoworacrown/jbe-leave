const formLeaveRequest = document.querySelector(".leave-request-form");
const submitBtnLeave = document.querySelector(".jbe__Leave-submit");

if(formLeaveRequest){
    formLeaveRequest.onsubmit = (e) => {
        e.preventDefault();
    }
}

if(submitBtnLeave) {
    submitBtnLeave.onclick = () => {
        // let employeeid = document.querySelector("#leave-employeeid");
        let leaveyear = document.querySelector("#leave-year");
        let leavereplace = document.querySelector("#leave-replace");
        let leavestatus = document.querySelector("#leave-status");
        let leavecommencing = document.querySelector(".leave-commencing");
        let leaveending = document.querySelector(".leave-ending");
        let leaveresumption = document.querySelector(".leave-resumption");
        let leavenoofdays = document.querySelector("#leave-noofdays");

        if(leaveyear.value !== "" && leavenoofdays.value !== "" && leavereplace.value !== "" && leavecommencing.value !== ""
        && leaveending.value !== "" && leaveresumption.value !== "") {
            let loading = document.querySelector(".loader");
            loading.style.display = "block";
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax_php/applyleave.php", true);
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
            let formData = new FormData(formLeaveRequest); //creating new formData object
            xhr.send(formData); //sending the form data to php
        }
    }
}
