<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Lista de inventario</h3>
		<div class="card-tools">
			<a href="?page=inventario/gestionar_inventario" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Crear Nuevo</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-hover table-strip">
				<colgroup>
					<col width="5%">
					<col width="35%">
					<col width="25%">
					<col width="25%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Producto</th>
						<th>Precio</th>
						<th>Stock</th>
						<th>Accion</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT i.*,p.name as productos,b.name as bname from `inventario` i inner join `productos` p on p.id = i.producto_id inner join Categoria b on p.categoria_id = b.id order by unix_timestamp(i.fecha_de_creacion) desc ");
						while($row = $qry->fetch_assoc()):
						$sold = $conn->query("SELECT SUM(ol.cantidad) as sold FROM lista_de_orden ol inner join pedidos o on o.id = ol.pedidos_id where ol.producto_id='{$row['id']}' and o.`status` != 4 ");
						$sold = $sold->num_rows > 0 ? $sold->fetch_assoc()['sold'] : 0;
						$avail = $row['cantidad'] - $sold;
						foreach($row as $k=> $v){
							$row[$k] = trim(stripslashes($v));
						}
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td>
								<b><?php echo $row['productos'] ?></b> <br>
								<small><b>Categoria:</b> <?php echo $row['bname'] ?></small>
							</td>
							<td class="text-right"><?php echo number_format($row['precio']) ?></td>
							<td class="text-right"><?php echo $avail ?></td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Accion
				                    <span class="sr-only">Alternar menú desplegable</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item" href="?page=inventario/gestionar_inventario&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Eliminar</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("¿Estás seguro de eliminar este inventario de forma permanente?","delete_inventory",[$(this).attr('data-id')])
		})
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [4] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
	function delete_inventory($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_inventory",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("Ocurrió un error.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("Ocurrió un error.",'error');
					end_loader();
				}
			}
		})
	}
</script>