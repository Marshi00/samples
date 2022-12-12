<?PHP

function generate_id()
{

    $now = new DateTime();
    ini_alter('date.timezone', 'Asia/Tehran');
    $now = $now->format('YmdHis');
    $microtime = microtime();
    $id = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', $microtime);
    $id = substr($id, 11, 1);
    $random = (rand(10000, 99999));
    $va_id = $now . $id . $random;
    return $va_id;
}

function getUserIpAddr(){

    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


function _date()
{
    ini_alter('date.timezone', 'Asia/Tehran');
    $now = new DateTime();
    $va_date = $now->format('Y-m-d');
    return $va_date;
}


function _time()
{
    ini_alter('date.timezone', 'Asia/Tehran');
    $now = new DateTime();
    $va_time = $now->format('H:i:s');
    return $va_time;
}

$idLog = generate_id();
$action="insert";
$description ="User Replyed  Ticket";
$date = _date();
$time = _time();
$ipUser = getUserIpAddr();
$logUser = mysqli_query($conn,"insert into users_log(id, id_user, action, description,ip, date, time)
     values ('$idLog','$user_id','$action','$description','$ipUser','$date','$time')");
