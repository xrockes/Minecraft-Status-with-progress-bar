<?php

$serverIP = " Put your IP here "; // Minecraft server address
$serverPort = "25565"; // Server Port
$title = "Put the name here";  // This is the title of the webpage

class minecraft_server {
    private $address;
    private $port;
    public function __construct($address, $port = 25565){
        $this->address = $address;
        $this->port = $port;
    }
    public function get_ping_info(&$info){
        $socket = @fsockopen($this->address, $this->port, $errno, $errstr, 1.0);
        if ($socket === false){
            return false;
        }
        fwrite($socket, "\xfe\x01");
        $data = fread($socket, 256);
        if (substr($data, 0, 1) != "\xff"){
            return false;
        }
        if (substr($data, 3, 5) == "\x00\xa7\x00\x31\x00"){
            $data = explode("\x00", mb_convert_encoding(substr($data, 15), 'UTF-8', 'UCS-2'));
        }else{
            $data = explode('§', mb_convert_encoding(substr($data, 3), 'UTF-8', 'UCS-2'));
        }
        if (count($data) == 3){
            $info = array(
                'Players'        => intval($data[1]),
                'Slots'    => intval($data[2]),
            );
        }else{
            $info = array(
                'Players'        => intval($data[2]),
                'Slots'    => intval($data[3]),
            );
        }
        return true;
    }
}
$server = new minecraft_server($serverIP, $serverPort);
$server->get_ping_info($info);
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> <?php echo $title ?> </title>   
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>

<div class="col-lg-12">
    <div class="progress">
        <div class="progress-bar progress-bar-striped active" role="progressbar"
             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $info["Slots"] ?>%">
            <?php echo $info["Slots"] ?>
        </div>
    </div>
</div>
<!-- I'd appreciate it if you didn't remove this footer, however feel free to do so. If you want to give me credit I'd appreciate if not, that's cool too. -->
<footer style="float:right;"   >Designed by <a href="http://Twitter.com/xRockes">Rockes</a></footer>

</body>
</html>
