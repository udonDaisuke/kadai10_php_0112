<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css?s">
    <title>Document</title>
</head>
<body>
    <form class="login-form r" action="./result_registration.php" method="post" onsubmit="return inputValidation()">

        <p class="login-text">Create new Account</p>
        <span id = "msg_validation_id" class="msg_validation"></span>
        <input class ="user-input" type="text" id="user_id" name = "user_id" placeholder="New ID" required>
        <span id = "msg_validation_pass" class="msg_validation"></span>
        <input class ="user-input" type="password" id="password" name = "password" placeholder="New Password" required>
        <span id = "msg_validation_name" class="msg_validation"></span>
        <input class ="user-input" type="nickname" id="nickname" name = "nickname" placeholder="Your nickname" required>

        <button type="submit">Create Account</button>
        <div class="msg-text">Go back to Login? <a href="./">Log in</a></div>
    </form>




    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="./js/main.js"></script>   
    <script>
        $('body').fadeIn(1000)
    </script>
    <script>
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
    </script>
    <script>
        $('#loginfail').animate({opacity:0},{duration:2500, easing:'easeInQuint'})
        $('.msg_validation').animate({opacity:0},{duration:2500, easing:'easeInQuint'})
    </script>

</body>
</html>