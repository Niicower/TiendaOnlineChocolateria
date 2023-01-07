<?php if(isset($_GET['view'])): 
require_once('../../config.php');
endif;?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php 
if(!isset($_GET['id'])){
    $_settings->set_flashdata('error','No se proporcionó ID de pedido.');
    redirect('admin/?page=pedidos');
}
$order = $conn->query("SELECT o.*,concat(c.firstname,' ',c.apellido) as client FROM `pedidos` o inner join clientes c on c.id = o.cliente_id where o.id = '{$_GET['id']}' ");
if($order->num_rows > 0){
    foreach($order->fetch_assoc() as $k => $v){
        $$k = $v;
    }
}else{
    $_settings->set_flashdata('error','El ID de pedido proporcionado es desconocido');
    redirect('admin/?page=pedidos');
}
?>
<div class="card card-outline card-primary">
    <div class="card-body">
        <div class="conitaner-fluid">
            <p><b>Nombre del cliente: <?php echo $client ?></b></p>
            <?php if($tipo_de_orden == 1): ?>
            <p><b>Dirección de entrega: <?php echo $delivery_address ?></b></p>
            <?php endif; ?>
            <table class="table-striped table table-bordered" id="list">
                <colgroup>
                    <col width="15%">
                    <col width="35%">
                    <col width="25%">
                    <col width="25%">
                </colgroup>
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $olist = $conn->query("SELECT o.*,p.name,b.name as bname FROM lista_de_orden o inner join productos p on o.producto_id = p.id inner join Categoria b on p.categoria_id = b.id where o.pedidos_id = '{$id}' ");
                        while($row = $olist->fetch_assoc()):
                        foreach($row as $k => $v){
                            $row[$k] = trim(stripslashes($v));
                        }
                    ?>
                    <tr>
                        <td><?php echo $row['cantidad'] ?></td>
                        <td>
                            <p class="m-0"><?php echo $row['name']?></p>
                            <p class="m-0"><small>categoria: <?php echo $row['bname']?></small></p>
                           
                        </td>
                        <td class="text-right"><?php echo number_format($row['precio']) ?></td>
                        <td class="text-right"><?php echo number_format($row['precio'] * $row['cantidad']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan='3'  class="text-right">Total</th>
                        <th class="text-right"><?php echo number_format($Monto) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="row">
            <div class="col-6">
                <p>Método de pago: <?php echo $metodo_de_pago ?></p>
                <p>Estado de pago: <?php echo $pagado == 0 ? '<span class="badge badge-light text-dark">No pagado</span>' : '<span class="badge badge-success">Pagado</span>' ?></p>
                <p>Tipo de orden: <?php echo $tipo_de_orden == 1 ? '<span class="badge badge-light text-dark">Para envio</span>' : '<span class="badge badge-light text-dark">Para retiro</span>' ?></p>
            </div>
            <div class="col-6 row row-cols-2">
                <div class="col-3">Estado de la orden:</div>
                <div class="col-9">
                <?php 
                    switch($status){
                        case '0':
                            echo '<span class="badge badge-light text-dark">Pendiente</span>';
	                    break;
                        case '1':
                            echo '<span class="badge badge-primary">Lleno</span>';
	                    break;
                        case '2':
                            echo '<span class="badge badge-warning">Para envio</span>';
	                    break;
                        case '3':
                            echo '<span class="badge badge-success">Envio</span>';
	                    break;
                        case '5':
                            echo '<span class="badge badge-success">Retiro Up</span>';
	                    break;
                        default:
                            echo '<span class="badge badge-danger">Cancelado</span>';
	                    break;
                    }
                ?>
                </div>
                <?php if(!isset($_GET['view'])): ?>
                <div class="col-3"></div>
                <div class="col">
                    <button type="button" id="update_status" class="btn btn-sm btn-flat btn-primary">Estado de actualización</button>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</div>
<?php if(isset($_GET['view'])): ?>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
</div>
<style>
    #uni_modal>.modal-dialog>.modal-content>.modal-footer{
        display:none;
    }
    #uni_modal .modal-body{
        padding:0;
    }
</style>
<?php endif; ?>
<script>
    $(function(){
        $('#list td,#list th').addClass('py-1 px-2 align-middle')
        $('#update_status').click(function(){
            uni_modal("Actualizar Estado", "./pedidos/update_status.php?oid=<?php echo $id ?>&status=<?php echo $status ?>")
        })
    })
</script>