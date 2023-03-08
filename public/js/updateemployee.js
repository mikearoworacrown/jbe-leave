const formUpdate = document.querySelector(".update-employee");
const submitBtnUpdate = document.querySelector(".jbe__update-employee");

if(formUpdate){
    formUpdate.onsubmit = (e) => {
        e.preventDefault();
    }
}


if(submitBtnUpdate) {
    submitBtnUpdate.onclick = () => {
        let loading = document.querySelector(".loader");
        loading.style.display = "block";
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../ajax_php/updateemployee.php", true);
        xhr.onload = () => {
            if(xhr.readyState === XMLHttpRequest.DONE){
                if(xhr.status === 200){
                    let data = xhr.response;
                    let dataParsed = JSON.parse(data);
                    console.log(dataParsed);
                    if (dataParsed['status'] == "success")  {
                        location.href = "employees.php";
                    }
                }
            }
        }
        //we have to send the form data through AJAX to php
        let formData = new FormData(formUpdate); //creating new formData object
        xhr.send(formData); //sending the form data to php
    }
}