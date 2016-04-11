<?php


session_start();
if (!isset($_SESSION['username'] )) {
    header("location:./index.php");
}

set_include_path('/home/jhooks/phpseclib1.0.0' . PATH_SEPARATOR . 'phpseclib');
include('Net/SSH2.php');

//  Possible IP's for SSH
//  $ssh = new Net_SSH2('192.168.0.101');
//  $ssh = new Net_SSH2('10.188.28.214'); 
//  $ssh = new Net_SSH2('10.188.40.207');
//  $ssh = new Net_SSH2('10.188.41.217');

/*
* If checking out a tool, check the quantities page to
* ensure tool can be checked out. If not, return error
*/
if($_GET['argument'] == checkOut){
    $tool = $_GET['toolc'];
    $toolq = $tool . ' 1';
    $logger = $_SESSION['username'];
    $data = file_get_contents('quantities.txt');
    if(strpos($data, $toolq) == FALSE)
    {
        exit('Tool not available!');
    }
    else{
        if (isset($_POST['action'])) {
            global $ssh;
			$ssh = new Net_SSH2('10.188.28.214');
			if (!$ssh->login('jhooks', '7broncos18')) {
				exit('Login Failed');
			}
			switch ($_POST['action']) {
            case 'Cat_6_Kit':
                Cat_6_Kit();
                break;
            case 'BNC_Kit':
                BNC_Kit();
                break;
            }
        }
    }
}

/*
* If checking in a tool, check the quantities page to
* make sure the tool is checked out and that you are the 
* person who checked it out, before checking it back in
*/

elseif($_GET['argument'] == checkIn){
    $tool = $_GET['toolc'];
    $toolq = $tool . ' 0';
    $logger = $_SESSION['username'];
    $data = file_get_contents('quantities.txt');
    $stringer = "$tool" . "," . "Checked Out" . "," . "$logger" . ",,,\n";
    if(strpos($data, $toolq) == FALSE)
    {
        exit('Tool already checked in!');
    }
    elseif(strpos(file_get_contents('toolsLog.csv'), $stringer) == FALSE){
        exit('Cannot check in tool for someone else!');
    }
    else{
        if (isset($_POST['action'])) {
            global $ssh;
			$ssh = new Net_SSH2('10.188.28.214');
			if (!$ssh->login('jhooks', '7broncos18')) {
				exit('Login Failed');
			}
			switch ($_POST['action']) {
            case 'Cat_6_Kit':
                Cat_6_Kit();
                break;
            case 'BNC_Kit':
                BNC_Kit();
                break;
            }
        }
    }
}

/*
* Next section defines how each tool relates to a pin
* on the raspberry pi. The function will cause the correct
* door to open when the tool is checked out or in.
*/

function Cat_6_Kit() {
    if($_GET['argument'] == checkOut){
        takeTool('Cat_6_Kit');
    }
    if($_GET['argument'] == checkIn){
        returnTool('Cat_6_Kit');
    }
    global $ssh;
    $ssh->exec("cd /home/jhooks");
    $ssh->exec("./runOpen1.sh 17 > /dev/null 2>/dev/null &");
    logUser("Cat_6_Kit",$_GET['argument']);
    exit('Success!!');
}

function BNC_Kit() {
    if($_GET['argument'] == checkOut){
        takeTool('BNC_Kit');
    }
    if($_GET['argument'] == checkIn){
        returnTool('BNC_Kit');
    }
    global $ssh;
    $ssh->exec("cd /home/jhooks");
    $ssh->exec("./runOpen1.sh 24 > /dev/null 2>/dev/null &"); 
    logUser("BNC_Kit",$_GET['argument']);
    exit('Success!!');
}

/*
* takeTool and returnTool update the values in the text
* file which manages whether or not the tool is available
*/
function takeTool($old){
    $old = $old . ' 1';
    $new = str_replace(' 1',' 0',$old);
    $str = file_get_contents('quantities.txt');
    $str = str_replace($old,$new,$str);
    file_put_contents('quantities.txt',$str);
}

function returnTool($old){
    $old = $old . ' 0';
    $new = str_replace(' 0',' 1',$old);
    $str = file_get_contents('quantities.txt');
    $str = str_replace($old,$new,$str);
    file_put_contents('quantities.txt',$str);
}


/*
* Log user takes in a tool and whether it is being checked
* out or in. The person, tool, and the action are then logged
* to a csv. A function which converts that csv into an XML
* is then called, which allows it to be seen in a table format
*/
function logUser($toolLogged, $argument){
    date_default_timezone_set('America/New_York');
    $logger = $_SESSION['username'];
    $date = date("Y-m-d,h:i:sa");
    if($argument == checkOut){
        $stringer = $date . "," . "$toolLogged" . "," . "Checked Out" . "," . "$logger" . ",,,\n";
        $lines = count(file('toolsLog.csv'));
        $longlines = count(file('longToolsLog.csv'));
        if($lines > 202){
            $file = file('toolsLog.csv');
	    unset($file[1]);
	    file_put_contents('toolsLog.csv', $file);
        }
        if($longLines > 5000){
            $longFile = file('longToolsLog.csv');
            unset($longFile[0]);
            file_put_contents('longToolsLog.csv', $file);
        }
        file_put_contents('toolsLog.csv',$stringer, FILE_APPEND);
        file_put_contents('longToolsLog.csv',$stringer, FILE_APPEND);
        $command = escapeshellcmd('/var/www/csvtoxml.py');
        $output = shell_exec($command);
    }
    if($argument == checkIn){
	$stringer = "$toolLogged" . "," . "Checked Out" . "," . "$logger" . ",,,\n";
	$stringerNew = "$toolLogged" . "," . "Checked Out" . "," . "$logger" . ",Checked In," . $date . "\n";
	$file = file_get_contents('toolsLog.csv');
    $longFile = file_get_contents('longToolsLog.csv');
	$file = str_replace("$stringer","$stringerNew",$file);
	$longFile =  str_replace("$stringer","$stringerNew",$longFile);
	file_put_contents('toolsLog.csv', $file);
	file_put_contents('longToolsLog.csv', $longFile);
	$command = escapeshellcmd('/var/www/csvtoxml.py');
        $output = shell_exec($command);
    }
}

?>
