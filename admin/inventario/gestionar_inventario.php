<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `inventario` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="card card-outline card-info">
	<div class="card-header">
		<h3 class="card-title"><?php echo isset($id) ? "Update ": "Crear nuevo " ?> Inventario</h3>
	</div>
	<div class="card-body">
		<form action="" id="inventory-form">
			<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
			<div class="form-group">
				<label for="producto_id" class="control-label">Producto</label>
                <select name="producto_id" id="producto_id" class="custom-select select2" required>
                    <option value=""></option>
                    <?php
                        $qry = $conn->query("SELECT * FROM `productos` order by `name` asc");
                        while($row= $qry->fetch_assoc()):
                            foreach($row as $k=> $v){
								$row[$k] = trim(stripslashes($v));
							}
                    ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($producto_id) && $producto_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
			</div>
            <div class="form-group">
				<label for="cantidad" class="control-label">Cantidad inicial</label>
                <input type="number" class="form-control form" required name="cantidad" value="<?php echo isset($cantidad) ? $cantidad : '' ?>">
            </div>
            <div class="form-group">
				<label for="precio" class="control-label">Precio</label>
                <input type="number" step="any" class="form-control form" required name="precio" value="<?php echo isset($precio) ? $precio : '' ?>">
            </div>
		</form>
	</div>
	<div class="card-footer">
		<button class="btn btn-flat btn-primary" form="inventory-form">Guardar</button>
		<a class="btn btn-flat btn-default" href="?page=inventario">Cancelar</a>
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
	$(document).ready(function(){
        $('.select2').select2({placeholder:"Seleccione aquí",width:"relative"})
		$('#inventory-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_inventory",
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
						location.href = "./?page=inventario";
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

        $('.summernote').summernote({
		        height: 200,
		        toolbar: [
		            [ 'style', [ 'style' ] ],
		            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
		            [ 'fontname', [ 'fontname' ] ],
		            [ 'fontsize', [ 'fontsize' ] ],
		            [ 'color', [ 'color' ] ],
		            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
		            [ 'table', [ 'table' ] ],
		            [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
		        ]
		    })
	})
</script>