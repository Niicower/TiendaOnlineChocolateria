<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `productos` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=stripslashes($v);
        }
    }
}
?>
<div class="card card-outline card-info">
	<div class="card-header">
		<h3 class="card-title"><?php echo isset($id) ? "Actualizar ": "Crear nuevo " ?> Producto</h3>
	</div>
	<div class="card-body">
		<form action="" id="product-form">
			<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
            <div class="form-group">
				<label for="categoria_id" class="control-label">Categoria</label>
                <select name="categoria_id" id="categoria_id" class="custom-select select2" required>
                <option value=""></option>
                <?php
                    $qry = $conn->query("SELECT * FROM `Categoria` order by `name` asc");
                    while($row= $qry->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($categoria_id) && $categoria_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                <?php endwhile; ?>
                </select>
			</div>
            <div class="form-group">
				<label for="categorias_id" class="control-label">Categoria</label>
                <select name="categorias_id" id="categorias_id" class="custom-select select2" required>
                <option value=""></option>
                <?php
                    $qry = $conn->query("SELECT * FROM `categorias` order by categoria asc");
                    while($row= $qry->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($categorias_id) && $categorias_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['categoria'] ?></option>
                <?php endwhile; ?>
                </select>
			</div>
            <div class="form-group">
				<label for="sub_categoria_id" class="control-label">Sub Categoria</label>
                <select name="sub_categoria_id" id="sub_categoria_id" class="custom-select">
                <option value="" selected="" disabled="">Seleccionar categor??a primero</option>
                <?php
                    $qry = $conn->query("SELECT * FROM `sub_categorias` order by sub_categoria asc");
                    $sub_categorias = array();
                    while($row= $qry->fetch_assoc()):
                    $sub_categorias[$row['identificador_id']][] = $row;
                    endwhile; 
                ?>
                </select>
			</div>
			<div class="form-group">
				<label for="name" class="control-label">nombre del producto</label>
                <input type="text" name="name" id="name" class="form-control rounded-0" required value="<?php echo isset($name) ?$name : '' ?>" />
			</div>
            <div class="form-group">
				<label for="especificaciones" class="control-label">Especificaciones</label>
                <textarea name="especificaciones" id="" cols="30" rows="2" class="form-control form no-resize summernote"><?php echo isset($especificaciones) ? $especificaciones : ''; ?></textarea>
			</div>
            <div class="form-group">
				<label for="status" class="control-label">Estado</label>
                <select name="status" id="status" class="custom-select selevt">
                <option value="1" <?php echo isset($status) && $status == 1 ? 'seleccionado' : '' ?>>Activo</option>
                <option value="0" <?php echo isset($status) && $status == 0 ? 'seleccionado' : '' ?>>Inactivo</option>
                </select>
			</div>
            <div class="form-group">
				<label for="" class="control-label">Im??genes</label>
				<div class="custom-file">
	              <input type="file" class="custom-file-input rounded-circle" id="customFile" name="img[]" multiple accept=".png,.jpg,.jpeg" onchange="displayImg(this,$(this))">
	              <label class="custom-file-label" for="customFile">Elija el archivo</label>
	            </div>
			</div>
            <?php 
            if(isset($id)):
            $upload_path = "uploads/product_".$id;
            if(is_dir(base_app.$upload_path)): 
            ?>
            <?php 
            
                $file= scandir(base_app.$upload_path);
                foreach($file as $img):
                    if(in_array($img,array('.','..')))
                        continue;
                    
                
            ?>
                <div class="d-flex w-100 align-items-center img-item">
                    <span><img src="<?php echo base_url.$upload_path.'/'.$img ?>" width="150px" height="100px" style="object-fit:cover;" class="img-thumbnail" alt=""></span>
                    <span class="ml-4"><button class="btn btn-sm btn-default text-danger rem_img" type="button" data-path="<?php echo base_app.$upload_path.'/'.$img ?>"><i class="fa fa-trash"></i></button></span>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
            <?php endif; ?>
			
		</form>
	</div>
	<div class="card-footer">
		<button class="btn btn-flat btn-primary" form="product-form">Guardar</button>
		<a class="btn btn-flat btn-default" href="?page=productos">Cancelar</a>
	</div>
</div>
<script>
    function displayImg(input,_this) {
        console.log(input.files)
        var fnames = []
        Object.keys(input.files).map(k=>{
            fnames.push(input.files[k].name)
        })
        _this.siblings('.custom-file-label').html(JSON.stringify(fnames))
	    
	}
    function delete_img($path){
        start_loader()
        
        $.ajax({
            url: _base_url_+'classes/Master.php?f=delete_img',
            data:{path:$path},
            method:'POST',
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("Ha ocurrido un error mientras se eliminaba la imagen","error");
                end_loader()
            },
            success:function(resp){
                $('.modal').modal('hide')
                if(typeof resp =='object' && resp.status == 'success'){
                    $('[data-path="'+$path+'"]').closest('.img-item').hide('slow',function(){
                        $('[data-path="'+$path+'"]').closest('.img-item').remove()
                    })
                    alert_toast("Imagen eliminada","success");
                }else{
                    console.log(resp)
                    alert_toast("Ha ocurrido un error mientras se eliminaba la imagen","error");
                }
                end_loader()
            }
        })
    }
    var sub_categorias = $.parseJSON('<?php echo json_encode($sub_categorias) ?>');
	$(document).ready(function(){
        $('.rem_img').click(function(){
            _conf("??Est?? seguro de eliminar la imagen permanentemente?",'delete_img',["'"+$(this).attr('data-path')+"'"])
        })
       
        $('#categorias_id').change(function(){
            var cid = $(this).val()
            var opt = "<option></option>";
            Object.keys(sub_categorias).map(k=>{
                if(k == cid){
                    Object.keys(sub_categorias[k]).map(i=>{
                        if('<?php echo isset($sub_categoria_id) ? $sub_categoria_id : 0 ?>' == sub_categorias[k][i].id){
                            opt += "<option value='"+sub_categorias[k][i].id+"' seleccionado>"+sub_categorias[k][i].sub_categoria+"</option>";
                        }else{
                            opt += "<option value='"+sub_categorias[k][i].id+"'>"+sub_categorias[k][i].sub_categoria+"</option>";
                        }
                    })
                }
            })
            $('#sub_categoria_id').html(opt)
            $('#sub_categoria_id').select2({placeholder:"Seleccione aqu??",width:"relative"})
        })
        $('.select2').select2({placeholder:"Seleccione aqu??",width:"relative"})
        if(parseInt("<?php echo isset($categorias_id) ? $categorias_id : 0 ?>") > 0){
            console.log('test')
            start_loader()
            setTimeout(() => {
                $('#categorias_id').trigger("change");
                end_loader()
            }, 750);
        }
		$('#product-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_product",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("Ha ocurrido un error",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.href = "./?page=productos";
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body").animate({ scrollTop: _this.closest('.card').offset().top }, "fast");
                            if(!!resp.id)
                            $('[name="id"]').val(resp.id)
                            end_loader()
                    }else{
						alert_toast("Ha ocurrido un error",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})

        $('.summernote').summernote({
		        height: 200,
		        toolbar: [
		            [ 'style', [ 'style' ] ],
		            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
		            // [ 'fontname', [ 'fontname' ] ],
		            [ 'fontsize', [ 'fontsize' ] ],
		            [ 'color', [ 'color' ] ],
		            [ 'para', [ 'ol', 'ul', 'paragraph' ] ],
		            [ 'table', [ 'table' ] ],
		            [ 'view', [ 'undo', 'redo', 'codeview', 'help' ] ]
		        ]
		    })
	})
</script>