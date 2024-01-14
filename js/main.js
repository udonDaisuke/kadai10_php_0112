let reg = /^[a-zA-Z0-9!~^_`]+$/

function inputValidation(){
    let user_id = document.querySelector("#user_id").value
    let pass = document.querySelector("#password").value
    let msg_id = document.querySelector("#msg_validation_id")
    let msg_pass = document.querySelector("#msg_validation_pass")
    if(!user_id.match(reg)){
        msg_id.textContent="*Invalid id: a-zA-Z0-9!~^_` are available."   
        console.log("first")
    }
    if(!pass.match(reg)){
        msg_pass.textContent="*Invalid password: a-zA-Z0-9 !~^_` is available."   
        console.log("second")
    }
    if(!user_id.match(reg)||!pass.match(reg)){
        return false
    }else{
        return true
    }


}
