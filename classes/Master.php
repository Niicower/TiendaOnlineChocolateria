<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_brand(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = addslashes(trim($v));
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `Categoria` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "El nombre de la categoria ya existe.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `Categoria` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `Categoria` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"Nueva Categoria guardada con éxito.");
			else
				$this->settings->set_flashdata('success',"Categoria actualizada correctamente.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_brand(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `Categoria` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Categoria eliminada con éxito.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_category(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(isset($_POST['description'])){
			if(!empty($data)) $data .=",";
				$data .= " `description`='".addslashes(htmlentities($description))."' ";
		}
		$check = $this->conn->query("SELECT * FROM `categorias` where `categoria` = '{$categoria}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "La categoría ya existe.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `categorias` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `categorias` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"Nueva categoría guardada con éxito.");
			else
				$this->settings->set_flashdata('success',"Categoría actualizada con éxito.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_category(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `categorias` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Categoría eliminada con éxito.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_sub_category(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(isset($_POST['description'])){
			if(!empty($data)) $data .=",";
				$data .= " `description`='".addslashes(htmlentities($description))."' ";
		}
		$check = $this->conn->query("SELECT * FROM `sub_categorias` where `sub_categoria` = '{$sub_categoria}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "La subcategoría ya existe.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `sub_categorias` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `sub_categorias` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"Nueva subcategoría guardada con éxito.");
			else
				$this->settings->set_flashdata('success',"Subcategoría actualizada con éxito.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_sub_category(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `sub_categorias` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Subcategoría eliminada con éxito.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_product(){
		foreach($_POST as $k =>$v){
			$_POST[$k] = addslashes($v);
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .=",";
				$v = addslashes($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(isset($_POST['description'])){
			if(!empty($data)) $data .=",";
				$data .= " `description`='".addslashes(htmlentities($description))."' ";
		}
		$check = $this->conn->query("SELECT * FROM `productos` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "El producto ya existe.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `productos` set {$data} ";
		}else{
			$sql = "UPDATE `productos` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$pid = empty($id) ? $this->conn->insert_id : $id;
			$upload_path = "uploads/product_".$pid;
			if(!is_dir(base_app.$upload_path))
				mkdir(base_app.$upload_path);
			if(isset($_FILES['img']) && count($_FILES['img']['tmp_name']) > 0){
				$err = "";
				foreach($_FILES['img']['tmp_name'] as $k => $v){
					if(!empty($_FILES['img']['tmp_name'][$k])){
						$accept = array('image/jpeg','image/png');
						if(!in_array($_FILES['img']['type'][$k],$accept)){
							$err = "Image file type is invalid";
							break;
						}
						if($_FILES['img']['type'][$k] == 'image/jpeg')
							$uploadfile = imagecreatefromjpeg($_FILES['img']['tmp_name'][$k]);
						elseif($_FILES['img']['type'][$k] == 'image/png')
							$uploadfile = imagecreatefrompng($_FILES['img']['tmp_name'][$k]);
						if(!$uploadfile){
							$err = "Image is invalid";
							break;
						}
						$temp = imagescale($uploadfile,400,400);
						if($_FILES['img']['type'][$k] == 'image/jpeg')
						imagejpeg($temp, base_app.$upload_path.'/'.$_FILES['img']['name'][$k]);
						elseif($_FILES['img']['type'][$k] == 'image/png')
						imagepng($temp, base_app.$upload_path.'/'.$_FILES['img']['name'][$k]);

						imagedestroy($temp);
					}
				}
				if(!empty($err)){
					$resp['status'] = 'failed';
					$resp['msg'] = 'Producto guardado correctamente pero '.$err;
					$resp['id'] = $pid;
				}
			}
			if(!isset($resp)){
				$resp['status'] = 'success';
				if(empty($id))
					$this->settings->set_flashdata('success',"Nuevo listado guardado con éxito.");
				else
					$this->settings->set_flashdata('success',"Listado actualizado correctamente.");
			}
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_product(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `productos` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Producto eliminado con éxito.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function delete_img(){
		extract($_POST);
		if(is_file($path)){
			if(unlink($path)){
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = 'no se pudo eliminar '.$path;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown '.$path.' path';
		}
		return json_encode($resp);
	}
	function save_inventory(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `inventario` where `producto_id` = '{$producto_id}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "El inventario ya existe.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `inventario` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `inventario` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"Nuevo inventario guardado con éxito.");
			else
				$this->settings->set_flashdata('success',"Inventario actualizado correctamente.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_inventory(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `inventario` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Inventario eliminado con éxito.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function register(){
		extract($_POST);
		$data = "";
		$_POST['password'] = md5($_POST['password']);
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `clientes` where `email` = '{$email}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Email already taken.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `clientes` set {$data} ";
			$save = $this->conn->query($sql);
			$id = $this->conn->insert_id;
		}else{
			$sql = "UPDATE `clientes` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"Cuenta creada con éxito.");
			else
				$this->settings->set_flashdata('success',"");
			foreach($_POST as $k =>$v){
					$this->settings->set_userdata($k,$v);
			}
			$this->settings->set_userdata('id',$id);

		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}

	function add_to_cart(){
		extract($_POST);
		$data = " cliente_id = '".$this->settings->userdata('id')."' ";
		$_POST['precio'] = str_replace(",","",$_POST['precio']); 
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `carrito_compras` where `inventario_id` = '{$inventario_id}' and cliente_id = ".$this->settings->userdata('id'))->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$sql = "UPDATE `carrito_compras` set cantidad = cantidad + {$cantidad} where `inventario_id` = '{$inventario_id}' and cliente_id = ".$this->settings->userdata('id');
		}else{
			$sql = "INSERT INTO `carrito_compras` set {$data} ";
		}
		
		$save = $this->conn->query($sql);
		if($this->capture_err())
			return $this->capture_err();
			if($save){
				$resp['status'] = 'success';
				$resp['cart_count'] = $this->conn->query("SELECT SUM(cantidad) as items from `carrito_compras` where cliente_id =".$this->settings->userdata('id'))->fetch_assoc()['items'];
			}else{
				$resp['status'] = 'failed';
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
			return json_encode($resp);
	}
	function update_cart_qty(){
		extract($_POST);
		
		$save = $this->conn->query("UPDATE `carrito_compras` set cantidad = '{$cantidad}' where id = '{$id}'");
		if($this->capture_err())
			return $this->capture_err();
		if($save){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
		
	}
	function empty_cart(){
		$delete = $this->conn->query("DELETE FROM `carrito_compras` where cliente_id = ".$this->settings->userdata('id'));
		if($this->capture_err())
			return $this->capture_err();
		if($delete){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_cart(){
		extract($_POST);
		$delete = $this->conn->query("DELETE FROM `carrito_compras` where id = '{$id}'");
		if($this->capture_err())
			return $this->capture_err();
		if($delete){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_order(){
		extract($_POST);
		$delete = $this->conn->query("DELETE FROM `pedidos` where id = '{$id}'");
		$delete2 = $this->conn->query("DELETE FROM `lista_de_orden` where pedidos_id = '{$id}'");
		$delete3 = $this->conn->query("DELETE FROM `Ventas` where pedidos_id = '{$id}'");
		if($this->capture_err())
			return $this->capture_err();
		if($delete){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Pedido eliminado con éxito");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function place_order(){
		extract($_POST);
		$cliente_id = $this->settings->userdata('id');
		
		$data = " cliente_id = '{$cliente_id}' ";
		$data .= " ,metodo_de_pago = '{$metodo_de_pago}' ";
		$data .= " ,tipo_de_orden = '{$tipo_de_orden}' ";
		$data .= " ,Monto = '{$Monto}' ";
		$data .= " ,pagado = '{$pagado}' ";
		$data .= " ,delivery_address = '{$delivery_address}' ";
		$order_sql = "INSERT INTO `pedidos` set $data";
		$save_order = $this->conn->query($order_sql);
		if($this->capture_err())
			return $this->capture_err();
		if($save_order){
			$pedidos_id = $this->conn->insert_id;
			$data = '';
			$carrito_compras = $this->conn->query("SELECT c.*,p.name,i.precio,p.id as pid from `carrito_compras` c inner join `inventario` i on i.id=c.inventario_id inner join productos p on p.id = i.producto_id where c.cliente_id ='{$cliente_id}' ");
			while($row= $carrito_compras->fetch_assoc()):
				if(!empty($data)) $data .= ", ";
				$total = $row['precio'] * $row['cantidad'];
				$data .= "('{$pedidos_id}','{$row['pid']}','{$row['cantidad']}','{$row['precio']}', $total)";
			endwhile;
			$list_sql = "INSERT INTO `lista_de_orden` (pedidos_id,producto_id,cantidad,precio,total) VALUES {$data} ";
			$save_olist = $this->conn->query($list_sql);
			if($this->capture_err())
				return $this->capture_err();
			if($save_olist){
				$empty_cart = $this->conn->query("DELETE FROM `carrito_compras` where cliente_id = '{$cliente_id}'");
				$data = " pedidos_id = '{$pedidos_id}'";
				$data .= " ,cantidad_total = '{$Monto}'";
				$save_sales = $this->conn->query("INSERT INTO `Ventas` set $data");
				if($this->capture_err())
					return $this->capture_err();
				$resp['status'] ='success';
			}else{
				$resp['status'] ='failed';
				$resp['err_sql'] =$save_olist;
			}

		}else{
			$resp['status'] ='failed';
			$resp['err_sql'] =$save_order;
		}
		return json_encode($resp);
	}
	function update_order_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `pedidos` set `status` = '$status' where id = '{$id}' ");
		if($update){
			$resp['status'] ='success';
			$this->settings->set_flashdata("success"," Estado del pedido actualizado con éxito.");
		}else{
			$resp['status'] ='failed';
			$resp['err'] =$this->conn->error;
		}
		return json_encode($resp);
	}
	function pay_order(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `pedidos` set `pagado` = '1' where id = '{$id}' ");
		if($update){
			$resp['status'] ='success';
			$this->settings->set_flashdata("success"," Estado de pago del pedido actualizado con éxito.");
		}else{
			$resp['status'] ='failed';
			$resp['err'] =$this->conn->error;
		}
		return json_encode($resp);
	}
	function update_account(){
		extract($_POST);
		$data = "";
		if(!empty($password)){
			$_POST['password'] = md5($password);
			if(md5($cpassword) != $this->settings->userdata('password')){
				$resp['status'] = 'failed';
				$resp['msg'] = "La contraseña actual es incorrecta";
				return json_encode($resp);
				exit;
			}

		}
		$check = $this->conn->query("SELECT * FROM `clientes`  where `email`='{$email}' and `id` != $id ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Correo electrónico ya tomado.";
			return json_encode($resp);
			exit;
		}
		foreach($_POST as $k =>$v){
			if($k == 'cpassword' || ($k == 'password' && empty($v)))
				continue;
				if(!empty($data)) $data .=",";
					$data .= " `{$k}`='{$v}' ";
		}
		$save = $this->conn->query("UPDATE `clientes` set $data where id = $id ");
		if($save){
			foreach($_POST as $k =>$v){
				if($k != 'cpassword')
				$this->settings->set_userdata($k,$v);
			}
			
			$this->settings->set_userdata('id',$this->conn->insert_id);
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_brand':
		echo $Master->save_brand();
	break;
	case 'delete_brand':
		echo $Master->delete_brand();
	break;
	case 'save_category':
		echo $Master->save_category();
	break;
	case 'delete_category':
		echo $Master->delete_category();
	break;
	case 'save_sub_category':
		echo $Master->save_sub_category();
	break;
	case 'delete_sub_category':
		echo $Master->delete_sub_category();
	break;
	case 'save_product':
		echo $Master->save_product();
	break;
	case 'delete_product':
		echo $Master->delete_product();
	break;
	
	case 'save_inventory':
		echo $Master->save_inventory();
	break;
	case 'delete_inventory':
		echo $Master->delete_inventory();
	break;
	case 'register':
		echo $Master->register();
	break;
	case 'add_to_cart':
		echo $Master->add_to_cart();
	break;
	case 'update_cart_qty':
		echo $Master->update_cart_qty();
	break;
	case 'delete_cart':
		echo $Master->delete_cart();
	break;
	case 'empty_cart':
		echo $Master->empty_cart();
	break;
	case 'delete_img':
		echo $Master->delete_img();
	break;
	case 'place_order':
		echo $Master->place_order();
	break;
	case 'update_order_status':
		echo $Master->update_order_status();
	break;
	case 'pay_order':
		echo $Master->pay_order();
	break;
	case 'update_account':
		echo $Master->update_account();
	break;
	case 'delete_order':
		echo $Master->delete_order();
	break;
	default:
		// echo $sysset->index();
		break;
}