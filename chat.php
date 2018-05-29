<?php
$admin = 'admin';
$EOL = '`nl';
$comma = '`cm';
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
$log = "chat_log.csv";
$date = date("M jS H:i:s");
function console_log( $a ){
  echo '<script>';
  echo 'console.log('. json_encode($a) .')';
  echo '</script>';
}
function display_chatbox ($inf, $ten) {
    if (strtolower($ten) === strtolower($inf[0])) {
        echo "<div class=\"sBox\" style=\"flex-direction:row-reverse;\">
        <p class=\"message\" style=\"color:navy;background-color:navajowhite;\">{$inf[1]}</p>
        <small class=\"time\">{$inf[2]}</small>
        </div>";
    }
    else {
        echo "<div class=\"sBox\">
        <div class=\"chat-name\">{$inf[0]}</div>  
        <p class=\"message\">{$inf[1]}</p>
        <small class=\"time\">{$inf[2]}</small>
        </div>";  
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css"/>
    <title>Chatting</title>
    <style>
    body {
        font-family: sans-serif;
    }
    .container {
    width: 65%;
    margin: 0 auto;
    }
    .message-submit {
        width: 75px;
        height: 40px;
    }
    .bBox {
        display: block;
    }
    #sending {
    width: 100%;
    background-color: #f9f9f9;
    box-shadow: 0 0 1px rgba(0, 0, 0, 0);
    transition-duration: 0.3s;
    }
    #sending:focus, #sending:hover {
        outline:none;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        transform: scale(1.01);
    }
    .content-box {
        border: 3px solid darkgrey;
        width: 100%;
        height:400px;
        overflow-y: visible;
        overflow-x: hidden;
        border-bottom: none;
    }
    .content {
        width:100%;
        height: 400px;
        word-wrap: break-word; 
    }

    .sBox {
    display: flex;
    margin: 10px 9px 10px 8px;
    position: relative;
    align-items: flex-start;
    }
    .message {
    margin: 0px 10px 0px 10px;
    padding: 10px;
    //border-style: ridge;
    border-radius: 10px;
    word-wrap: break-word;
    color: navy;
    max-width: 79%;
    background-color: #EEE;
    }
    .chat-name {
    border-style: outset;
    border-radius: 5px;
    padding: 5px;
    align-self: flex-start;
    font-weight: bold;

    }
    small.time {
    align-self: flex-end;
    }
    </style>
</head>
<body onpageshow="scroll()">
    <?php
    // check if logged in
        if (isset($_SESSION["usr"])) {
            $name = $_SESSION["usr"];
            echo '<a href="logout.php">Sign out</a><br>';
        } else { 
            echo '<small> To join with us, please click <a href="login.php">Login</a></small><br>';        
            echo '<em>You are anonymous</em>';
            $name = "Anonymous";
        }
    ?>
    <hr>
    <div class="container">
        <div class="header">
            <h1 style='font-family: Oswald;'>Simple Chat System</h1>
            <?php echo "<h2> Hello, {$name}</h2>"; ?>
            <span><h3>Message</h3></span>
            <?php
            if (strtolower($name) === strtolower($admin)) {
                echo '<form method="GET" style="margin-bottom: 5px">
        <input type="submit" name="clear" value="Clear Chat History"/>
        </form>';
                if (isset($_GET['clear']) && !empty($_GET['clear'])) {
                    file_put_contents($log, "");
                    header('Location: ./chat.php');
                }
            }
            ?>
        </div>
        
        <div class="bBox">
            <?php
            // characters that cannot represent in log file
            // save data to chat log
             if (!empty($_POST["message"])) {
                
                $message = str_replace(PHP_EOL, $EOL, str_replace(',', $comma, trim($_POST["message"])));
                $str = $name . "," . $message . "," . $date . PHP_EOL;
                console_log($message);
                console_log($str);
                file_put_contents($log, $str, FILE_APPEND);
                header('Location: ./chat.php');
            } ?>
            <div class="content-box">
                <div class="content">
                <?php
                // get data from chat log
                $content = file_get_contents($log);
                $content = explode(PHP_EOL, $content);
                foreach ($content as $i) {
                $chat = explode(",",$i);
                    if (isset($chat[0]) && isset($chat[1]) && isset($chat[2])){
                        $chat[1] = str_replace($EOL, '<br/>', htmlspecialchars(str_replace($comma, ',', $chat[1])));
                        display_chatbox($chat,$name);
                    }
                }
                ?>
                </div>
            </div>
            <form method="post" class="sending-area">
            <textarea name="message" id="sending" cols="90" rows="5" placeholder="Enter your message..." autofocus style="font-size: 11pt"></textarea>
            <button class="message-submit" type="submit">Send</button>
            </form>
        </div>
    </div>
    <script>
    function scroll() {
        var box = document.getElementsByClassName("content-box")[0];
        box.scrollTop=box.scrollHeight;
    }    
    </script>
</body>
</html>