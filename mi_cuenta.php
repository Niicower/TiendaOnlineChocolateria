    
<section class="py-2">
    <div class="container">
        <div class="card rounded-0">
            <div class="card-body">
                <div class="w-100 justify-content-between d-flex">
                    <h4><b>Pedidos</b></h4>
                    
                </div>
                    <hr class="border-warning">
                    <table class="table table-stripped text-dark">
                        <colgroup>
                            <col width="10%">
                            <col width="15">
                            <col width="25">
                            <col width="25">
                            <col width="15">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha y hora</th>
                                <th>ID de transacción</th>
                                <th>Monto</th>
                                <th>Estado del pedido</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i = 1;
                                $qry = $conn->query("SELECT o.*,concat(c.firstname,' ',c.apellido) as client from `pedidos` o inner join clientes c on c.id = o.cliente_id where o.cliente_id = '".$_settings->userdata('id')."' order by unix_timestamp(o.fecha_de_creacion) desc ");
                                while($row = $qry->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $i++ ?></td>
                                    <td><?php echo date("Y-m-d H:i",strtotime($row['fecha_de_creacion'])) ?></td>
                                    <td><a href="javascript:void(0)" class="view_order" data-id="<?php echo $row['id'] ?>"><?php echo md5($row['id']); ?></a></td>
                                    <td><?php echo number_format($row['Monto']) ?> </td>
                                    <td class="text-center">
                                            <?php if($row['status'] == 0): ?>
                                                <span class="badge badge-light text-dark">Pendiente</span>
                                            <?php elseif($row['status'] == 1): ?>
                                                <span class="badge badge-primary">Lleno</span>
                                            <?php elseif($row['status'] == 2): ?>
                                                <span class="badge badge-warning">para entrega</span>
                                            <?php elseif($row['status'] == 3): ?>
                                                <span class="badge badge-success">Entregado</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Cancelado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</section>
<script>
    function cancel_book($id){
        start_loader()
        $.ajax({
            url:_base_url_+"classes/Master.php?f=update_book_status",
            method:"POST",
            data:{id:$id,status:2},
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("ocurrió un error",'error')
                end_loader()
            },
            success:function(resp){
                if(typeof resp == 'object' && resp.status == 'success'){
                    alert_toast("cancelled successfully",'success')
                    setTimeout(function(){
                        location.reload()
                    },2000)
                }else{
                    console.log(resp)
                    alert_toast("ocurrió un error",'error')
                }
                end_loader()
            }
        })
    }
    $(function(){
        $('.view_order').click(function(){
            uni_modal("Detalle de ordenes","./admin/pedidos/view_order.php?view=user&id="+$(this).attr('data-id'),'large')
        })
        $('table').dataTable();

    })
</script>