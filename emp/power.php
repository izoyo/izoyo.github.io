<?php
date_default_timezone_set('PRC'); //设置北京时区
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>提示</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
<?php
function reCode($fmsg, $fqq){
    for ($i=1; $i < 11; $i++) { 
        $fmsg = str_ireplace(chr(64 + $i*2), ($i-1), $fmsg);
    }
    $z = explode($fqq,$fmsg);
    if($z <= 0){
        return 1000000000;
    }
    return $z[0] - $fqq;
}
function setCode($ftime, $fqq){
    $ftime = $ftime + $fqq;
    $ftime = $ftime.$fqq.$ftime;
    for ($i=1; $i < 11; $i++) { 
        $ftime = str_ireplace(($i-1), chr(64 + $i*2), $ftime);
    }
    return $ftime;
}
//2532556800
if(!isset($_POST['ma'])){
    echo '请填写授权码！';
    return;
}
//echo $_POST['ma'];
$servername = "57b075a7346d3.gz.cdb.myqcloud.com:16483";
$username = "cdb_outerroot";
$password = "757@skyhub";
$dbname = "manager";
// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}else{
    echo 'Link star！<br>';
}
$ma = mysqli_real_escape_string($conn,$_POST['ma']);//转义
$oldQ = mysqli_real_escape_string($conn,$_POST['old']);
$newQ = mysqli_real_escape_string($conn,$_POST['new']);

$sql = "SELECT * FROM operation_log 
WHERE op='$ma'";
echo $sql.' ，count：';
$result = $conn->query($sql);//执行SQL
echo $result->num_rows.'<br>';

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo 'QQ：'.$row["ue"]. "<br>";
} else {
    echo "授权卡号或QQ号填写错误";
}
if ($oldQ != $row["ue"]){
    echo "授权卡号或QQ号填写错误";
}
echo '匹配成功<br>';

function getTime($fqq){
    $fres = $GLOBALS['coon']->query("SELECT te FROM login_log WHERE re=$fqq");
    $frow = $fres->fetch_assoc();
    return reCode($frow['te'],$fqq);
}
function setTime($ftime, $fqq){
    global $coon;
    $fres = $coon->query("SELECT te FROM login_log
    WHERE re=$fqq");
    $te = setCode($ftime, $fqq);
    if($fres->num_rows == 0){
        $coon->query("INSERT INTO login_log
        VALUES($fqq,'$te')");
    }else{
        $coon->query("UPDATE login_log SET te='$te'
        WHERE re=$fqq");
    }
}

echo date('Y-m-d H:i:s',getTime($oldQ));

?>
</body>
</html>