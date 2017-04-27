<!-- Common Library functions pageAuthor: Sanjay Bhosale---------------------------------------------------------------------------------------------------| Version Info  |  Date           | Autor              | Description                              || 0.1           | 27th S                     ept 2016  | Sanjay Bhosale     | All common functions are defined here	  |-----------------------------------------------------------------------------------------------------><?phpsession_start();require_once('const.php');function read_config_file($config_file){	if(!is_file($config_file)){		return array(False, "$config_file Not Found");	}	$settings = parse_ini_file($config_file);	if($settings === False){		echo "$!";		return array(False, "Unable to parse $config_file");	}	return array(True, $settings);}function get_db_connection($config_file){	list($return_val, $return_details) = read_config_file($config_file);	if($return_val == False){		return array($return_val, $return_details);	}		$db_host = $db_name = $db_user = $db_pswd = "";		if(array_key_exists('host', $return_details)){		$db_host = $return_details['host'];	}else{		return array(False, "database host(host) key not present in config file");	}		if(array_key_exists('name', $return_details)){		$db_name = $return_details['name'];	}else{		return array(False, "database name(name) key not present in config file");	}		if(array_key_exists('user', $return_details)){		$db_user = $return_details['user'];	}else{		return array(False, "database user name(user) key not present in config file");	}		if(array_key_exists('pswd', $return_details)){		$db_pswd = $return_details['pswd'];	}else{		return array(False, "database password for user $db_user (pswd) key not present in config file");	}	try{		$conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pswd);	}catch (PDOException $e){		return array(False, $e->getMessage());	}	return array(True, $conn);}function is_user_exists($db_connection, $user_name, $user_pswd){	$users_login_table = 'Registration';	$user_login_name_column = 'email';	$user_login_pswd_column = 'password';		$select_query = "select first_name,reg_id					 from `$users_login_table`					 where 						`$user_login_name_column`='$user_name' and 						`$user_login_pswd_column`='$user_pswd';";	$stmt = $db_connection->prepare($select_query);	$stmt->execute();	$stmt->setFetchMode(PDO::FETCH_ASSOC);		    $result=$stmt->fetch();						if($result == NULL){				return array(False,"User Is Not Present");;			}			else{			     $_SESSION['regid']=$result['reg_id'];			     $_SESSION['user']=$result['first_name'];		         return True;				}}function show($query,$db_connection){		$stmt = $db_connection->prepare(query);	$stmt->execute();	$stmt->setFetchMode(PDO::FETCH_ASSOC);	$result=$stmt->fetch();		    if($result==NULL)	return False;//return array('False',"Records are not avilable");	$count=$result['view_profile'];return $count;} function insertData($insert_query,$db_connection){ 	$db_connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);	$db_connection->exec($insert_query);	$lastid=$db_connection->lastInsertId();		$_SESSION['lid']=$lastid;	$db_connection=null;	return True;	}	/*function Close_Db_Connection($db_connection){		$db_connection=NULL;	return $db_connection;	}*/function isExite_Email($db_connection,$email){	$users_login_table = 'Registration';	$user_login_name_column = 'email';		$select_query="select count(*) from $users_login_table where $user_login_name_column='$email';";    $stmt = $db_connection->prepare($select_query);	$stmt->execute();	$row = $stmt->fetchAll(PDO::FETCH_NUM);	unset($stmt);	if($row[0][0] > 0){		return False;	}	return True;}function isValid_To_Create_Account($date){$ndate=date_create($date);$fdate=date_format($ndate,'Y/m/d');$Cdate =date("Y/m/d");$d1 = new DateTime($fdate);$d2 = new DateTime($Cdate);$diff = $d1->diff($d2);$year= $diff->y;return $year;}function redirect_to_page($page){	header('Location: '.$page);	}function GetAbsolutePath(){$host  = $_SERVER['HTTP_HOST'];	$uri   = rtrim(dirname(dirname($_SERVER['PHP_SELF'])), '//');	$home_page = "http://$host$uri/";	return $home_page;	echo "$home_page";}function redirect_to_home($err_msg = ""){	$host  = $_SERVER['HTTP_HOST'];	$uri   = rtrim(dirname(dirname($_SERVER['PHP_SELF'])), '//');	$home_page = "http://$host$uri/index.php";	if($err_msg != ""){		$home_page = $home_page."?error=$err_msg";	}	redirect_to_page($home_page);}function get_udne_message($user_name){	$message = "No such user with name $user_name exists";	return $message;}function get_udn_message($user_name){	$message1 = "Your are not successfule Register your info";	return $message1;} function Personal($db_connection,$name,$address,$Religon,$cast,$subcast,$Relation,$work,$height,$income,$mother_tongue,$adhar){   $regid=$_SESSION['lid'];	$db_connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);	$db_connection->exec("insert into Personal_info(name,address,religon,cast,subcast,iam,workstatus,height,annual_income,mother_tongue,adhar_card,reg_id)values('$name','$address','$Religon','$cast','$subcast','$Relation','$work','$height','$income','$mother_tongue','$adhar',$regid)");	return True;	} function Educational_info($db_connection,$degree,$special,$ugdegree,$ugcollege,$pgcollege){   $regid=$_SESSION['lid'];	$db_connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);	$db_connection->exec("insert into Education_info(degree,special,UGdegree,UGCOllege,PGCOllege,reg_id)values('$degree','$special','$ugdegree','$ugcollege','$pgcollege',$regid)");	return True;	} function Family_info($db_connection,$familytype,$noofmem,$motheris,$fatheris,$brother,$sister,$gothra,$familyincome){   $regid=$_SESSION['lid'];	$db_connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);	$db_connection->exec("insert into Family_info(family_type,NO_OF_Member,mother_is,father_is,brother,sister,gothra,family_income,reg_id)values('$familytype','$noofmem','$motheris','$fatheris','$brother','$sister','$gothra','$familyincome',$regid)");	return True;	}  function Browseprofile($db_connection){ 	$select_query="select first_name,last_name,date,religon,height,annual_income,iam,cast,degree from Registration,Personal_info,Education_info;";    $stmt = $db_connection->prepare($select_query);	$stmt->execute();	$row = $stmt->fetchAll(PDO::FETCH_NUM);	$result=$stmt->fetch();	unset($stmt);	return $result; }?>