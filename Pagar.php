
<?php 
$total = 0;
    $qry = $conn->query("SELECT c.*,p.name,i.precio,p.id as pid from `carrito_compras` c inner join `inventario` i on i.id=c.inventario_id inner join productos p on p.id = i.producto_id where c.cliente_id = ".$_settings->userdata('id'));
    while($row= $qry->fetch_assoc()):
        $total += $row['precio'] * $row['cantidad'];
    endwhile;
?>
<section class="py-5">
    <div class="container">
        <div class="card rounded-0">
            <div class="card-body"></div>
            <h3 class="text-center"><b>PAGAR</b></h3>
            <hr class="border-dark">
            <form action="" id="place_order">
                <input type="hidden" name="Monto" value="<?php echo $total ?>">
                <input type="hidden" name="metodo_de_pago" value="efectivo">
                <input type="hidden" name="pagado" value="0">
                <div class="row row-col-1 justify-content-center">
                    <div class="col-6">
                    <div class="form-group col mb-0">
                    <label for="" class="control-label">Tipo de orden</label>
                    </div>
                    <div class="form-group d-flex pl-2">
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input custom-control-input-primary" type="radio" id="customRadio4" name="tipo_de_orden" value="1" checked="">
                          <label for="customRadio4" class="custom-control-label">Para envio</label>
                        </div>
                        <div class="custom-control custom-radio ml-3">
                          <input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="radio" id="customRadio5" name="tipo_de_orden" value="2">
                          <label for="customRadio5" class="custom-control-label">Para retiro</label>
                        </div>
                      </div>
                        <div class="form-group col address-holder">
                            <label for="" class="control-label">Direccion de envio</label>
                            <textarea id="" cols="30" rows="3" name="delivery_address" class="form-control" style="resize:none"><?php echo $_settings->userdata('default_delivery_address') ?></textarea>
                        </div>
                        <div class="col">
                            <span><h4><b>Total:</b> <?php echo number_format($total) ?></h4></span>
                        </div>
                        <hr>
                        <div class="col my-3">
                        <h4 class="text-muted">Método de pago</h4>
                            <div class="d-flex w-100 justify-content-between">
                                <button class="btn btn-flat btn-dark">Efectivo </button>
                                <span id="paypal-button"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>

$(function(){
    $('[name="tipo_de_orden"]').change(function(){
        if($(this).val() ==2){
            $('.address-holder').hide('slow')
        }else{
            $('.address-holder').show('slow')
        }
    })
    $('#place_order').submit(function(e){
        e.preventDefault()
        start_loader();
        $.ajax({
            url:'classes/Master.php?f=place_order',
            method:'POST',
            data:$(this).serialize(),
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("Ocurrió un error","error")
                end_loader();
            },
            success:function(resp){
                if(!!resp.status && resp.status == 'success'){
                    alert_toast("Pedido realizado con éxito.","success")
                    setTimeout(function(){
                        location.replace('./')
                    },2000)
                }else{
                    console.log(resp)
                    alert_toast("Ocurrió un error","error")
                    end_loader();
                }
            }
        })
    })
})
</script>