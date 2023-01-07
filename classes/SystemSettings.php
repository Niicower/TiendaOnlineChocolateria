<?php
if(!class_exists('DBConnection')){
	require_once('../config.php');
	require_once('DBConnection.php');
}
class SystemSettings extends DBConnection{
	public function __construct(){
		parent::__construct();
	}
	function check_connection(){
		return($this->conn);
	}
	function load_system_info(){
		// if(!isset($_SESSION['info_del_sistema'])){
			$sql = "SELECT * FROM info_del_sistema";
			$qry = $this->conn->query($sql);
				while($row = $qry->fetch_assoc()){
					$_SESSION['info_del_sistema'][$row['campo']] = $row['valor'];
				}
		// }
	}
	function update_system_info(){
		$sql = "SELECT * FROM info_del_sistema";
		$qry = $this->conn->query($sql);
			while($row = $qry->fetch_assoc()){
				if(isset($_SESSION['info_del_sistema'][$row['campo']]))unset($_SESSION['info_del_sistema'][$row['campo']]);
				$_SESSION['info_del_sistema'][$row['campo']] = $row['valor'];
			}
		return true;
	}
	function update_settings_info(){
		$data = "";
		foreach ($_POST as $key => $value) {
			if(!in_array($key,array("about_us","privacy_policy")))
			if(isset($_SESSION['info_del_sistema'][$key])){
				$value = str_replace("'", "&apos;", $value);
				$qry = $this->conn->query("UPDATE info_del_sistema set valor = '{$value}' where campo = '{$key}' ");
			}else{
				$qry = $this->conn->query("INSERT into info_del_sistema set valor = '{$value}', campo = '{$key}' ");
			}
		}
		if(isset($_POST['about_us'])){
			file_put_contents('../Nosotros.html',$_POST['about_us']);
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = 'uploads/'.strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'../'. $fname);
			if(isset($_SESSION['info_del_sistema']['logo'])){
				$qry = $this->conn->query("UPDATE info_del_sistema set valor = '{$fname}' where campo = 'logo' ");
				if(is_file('../'.$_SESSION['info_del_sistema']['logo'])) unlink('../'.$_SESSION['info_del_sistema']['logo']);
			}else{
				$qry = $this->conn->query("INSERT into info_del_sistema set valor = '{$fname}',campo = 'logo' ");
			}
		}
		if(isset($_FILES['cover']) && $_FILES['cover']['tmp_name'] != ''){
			$fname = 'uploads/'.strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'],'../'. $fname);
			if(isset($_SESSION['info_del_sistema']['cover'])){
				$qry = $this->conn->query("UPDATE info_del_sistema set valor = '{$fname}' where campo = 'cover' ");
				if(is_file('../'.$_SESSION['info_del_sistema']['cover'])) unlink('../'.$_SESSION['info_del_sistema']['cover']);
			}else{
				$qry = $this->conn->query("INSERT into info_del_sistema set valor = '{$fname}',campo = 'cover' ");
			}
		}
		if(isset($_FILES['banners']) && count($_FILES['banners']['tmp_name']) > 0){
			$err='';
			$banner_path = "uploads/banner/";
			foreach($_FILES['banners']['tmp_name'] as $k => $v){
				if(!empty($_FILES['banners']['tmp_name'][$k])){
					$accept = array('image/jpeg','image/png');
					if(!in_array($_FILES['banners']['type'][$k],$accept)){
						$err = "Image file type is invalid";
						break;
					}
					if($_FILES['banners']['type'][$k] == 'image/jpeg')
						$uploadfile = imagecreatefromjpeg($_FILES['banners']['tmp_name'][$k]);
					elseif($_FILES['banners']['type'][$k] == 'image/png')
						$uploadfile = imagecreatefrompng($_FILES['banners']['tmp_name'][$k]);
					if(!$uploadfile){
						$err = "Image is invalid";
						break;
					}
					$temp = imagescale($uploadfile,1200,400);
					$spath = base_app.$banner_path.'/'.$_FILES['banners']['name'][$k];
					$i = 1;
					while(true){
						if(is_file($spath)){
							$spath = base_app.$banner_path.'/'.($i++).'_'.$_FILES['banners']['name'][$k];
						}else{
							break;
						}
					}
					if($_FILES['banners']['type'][$k] == 'image/jpeg')
					imagejpeg($temp,$spath);
					elseif($_FILES['banners']['type'][$k] == 'image/png')
					imagepng($temp,$spath);

					imagedestroy($temp);
				}
			}
			if(!empty($err)){
				$resp['status'] = 'failed';
				$resp['msg'] = $err;
			}
		}
		
		$update = $this->update_system_info();
		$flash = $this->set_flashdata('success','Información del sistema actualizada con éxito.');
		if($update && $flash){
			// var_dump($_SESSION);
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}
	function set_userdata($field='',$value=''){
		if(!empty($field) && !empty($value)){
			$_SESSION['userdata'][$field]= $value;
		}
	}
	function userdata($field = ''){
		if(!empty($field)){
			if(isset($_SESSION['userdata'][$field]))
				return $_SESSION['userdata'][$field];
			else
				return null;
		}else{
			return false;
		}
	}
	function set_flashdata($flash='',$value=''){
		if(!empty($flash) && !empty($value)){
			$_SESSION['flashdata'][$flash]= $value;
		return true;
		}
	}
	function chk_flashdata($flash = ''){
		if(isset($_SESSION['flashdata'][$flash])){
			return true;
		}else{
			return false;
		}
	}
	function flashdata($flash = ''){
		if(!empty($flash)){
			$_tmp = $_SESSION['flashdata'][$flash];
			unset($_SESSION['flashdata']);
			return $_tmp;
		}else{
			return false;
		}
	}
	function sess_des(){
		if(isset($_SESSION['userdata'])){
				unset($_SESSION['userdata']);
			return true;
		}
			return true;
	}
	function info($field=''){
		if(!empty($field)){
			if(isset($_SESSION['info_del_sistema'][$field]))
				return $_SESSION['info_del_sistema'][$field];
			else
				return false;
		}else{
			return false;
		}
	}
	function set_info($field='',$value=''){
		if(!empty($field) && !empty($value)){
			$_SESSION['info_del_sistema'][$field] = $value;
		}
	}
}
$_settings = new SystemSettings();
$_settings->load_system_info();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'update_settings':
		echo $sysset->update_settings_info();
		break;
	default:
		// echo $sysset->index();
		break;
}
?>