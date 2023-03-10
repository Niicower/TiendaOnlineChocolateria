<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php if($_settings->chk_flashdata('error')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('error') ?>",'error')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Lista de orders</h3>
		<!-- <div class="card-tools">
			<a href="?page=order/manage_order" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div> -->
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="25%">
					<col width="20%">
					<col width="10%">
					<col width="10%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Fecha de pedido</th>
						<th>Cliente</th>
						<th>Cantidad total</th>
						<th>Pagado</th>
						<th>Estado</th>
						<th>Accion</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT o.*,concat(c.firstname,' ',c.apellido) as client from `pedidos` o inner join clientes c on c.id = o.cliente_id order by unix_timestamp(o.fecha_de_creacion) desc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("Y-m-d H:i",strtotime($row['fecha_de_creacion'])) ?></td>
							<td><?php echo $row['client'] ?></td>
							<td class="text-right"><?php echo number_format($row['Monto']) ?></td>
							<td class="text-center">
                                <?php if($row['pagado'] == 0): ?>
                                    <span class="badge badge-light">No</span>
                                <?php else: ?>
                                    <span class="badge badge-success">si</span>
                                <?php endif; ?>
                            </td>
							<td class="text-center">
                                <?php if($row['status'] == 0): ?>
                                    <span class="badge badge-light">Pendiente</span>
                                <?php elseif($row['status'] == 1): ?>
                                    <span class="badge badge-primary">Lleno</span>
								<?php elseif($row['status'] == 2): ?>
                                    <span class="badge badge-warning">Fuera para entrega</span>
								<?php elseif($row['status'] == 3): ?>
                                    <span class="badge badge-success">Entregado</span>
								<?php elseif($row['status'] == 5): ?>
                                    <span class="badge badge-success">Recogido</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Cancelado</span>
                                <?php endif; ?>
                            </td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Accion
				                    <span class="sr-only">Alternar men?? desplegable</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item" href="?page=pedidos/view_order&id=<?php echo $row['id'] ?>">Ver pedido</a>
									<?php if($row['pagado'] == 0 && $row['status'] != 4): ?>
				                    <a class="dropdown-item pay_order" href="javascript:void(0)"  data-id="<?php echo $row['id'] ?>">Marcar como pagado</a>
									<?php endif; ?>
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
			_conf("??Est??s seguro de eliminar este pedido de forma permanente?","delete_order",[$(this).attr('data-id')])
		})
		$('.pay_order').click(function(){
			_conf("??Est??s seguro de marcar este pedido como pagado??","pay_order",[$(this).attr('data-id')])
		})
		$('.table').dataTable();
	})
	function pay_order($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=pay_order",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("ha ocurrido un error.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("ha ocurrido un error.",'error');
					end_loader();
				}
			}
		})
	}
	function delete_order($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_order",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("ha ocurrido un error.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("ha ocurrido un error.",'error');
					end_loader();
				}
			}
		})
	}
</script>