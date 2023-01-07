<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `sub_categorias` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="container-fluid">
	<form action="" id="categoria-form">
		<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="identificador_id" class="control-label">Categoría principal</label>
			<select name="identificador_id" id="identificador_id" class="custom-select select2">
			<option value=""></option>
			<?php
				$qry = $conn->query("SELECT * FROM `categorias` order by categoria asc");
				while($row= $qry->fetch_assoc()):
			?>
			<option value="<?php echo $row['id'] ?>" <?php echo isset($identificador_id) && $identificador_id == $row['id'] ? 'seleccionado' : '' ?>><?php echo $row['categoria'] ?></option>
			<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="sub_categoria" class="control-label">Nombre de subcategoría</label>
			<input type="text" name="sub_categoria" id="sub_categoria" class="form-control form-control-sm rounded-0" value="<?php echo isset($sub_categoria) ? $sub_categoria : ''; ?>" />
		</div>
		<div class="form-group">
			<label for="description" class="control-label">Descripcion</label>
			<textarea name="description" id="" cols="30" rows="2" class="form-control form no-resize rounded-0"><?php echo isset($description) ? $description : ''; ?></textarea>
		</div>
		<div class="form-group">
			<label for="status" class="control-label">Estado</label>
			<select name="status" id="status" class="form-control form-control-sm rounded-0">
			<option value="1" <?php echo isset($status) && $status == 1 ? 'seleccionado' : '' ?>>Activo</option>
			<option value="0" <?php echo isset($status) && $status == 0 ? 'seleccionado' : '' ?>>Inactivo</option>
			</select>
		</div>
		
	</form>
</div>
<script>
  
	$(document).ready(function(){
        $('.select2').select2({placeholder:"Seleccione aquí",width:"relative"})
		$('#categoria-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_sub_category",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("Ocurrió un error",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.href = "./?page=maintenance/sub_categoria";
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body").animate({ scrollTop: _this.closest('.card').offset().top }, "fast");
                            end_loader()
                    }else{
						alert_toast("Ocurrió un error",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})

	})
</script>