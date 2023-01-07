
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col d-flex justify-content-end mb-2">
                <button class="btn btn-outline-dark btn-flat btn-sm" type="button" id="empty_cart">Vaciar Carrito</button>
            </div>
        </div>
        <div class="card rounded-0">
            <div class="card-body">
                <h3><b>Carrito de Compra</b></h3>
                <hr class="border-dark">
                <?php 
                    $qry = $conn->query("SELECT c.*,p.name,i.precio,p.id as pid from `carrito_compras` c inner join `inventario` i on i.id=c.inventario_id inner join productos p on p.id = i.producto_id where c.cliente_id = ".$_settings->userdata('id'));
                    while($row= $qry->fetch_assoc()):
                        $upload_path = base_app.'/uploads/product_'.$row['pid'];
                        $img = "";
                        foreach($row as $k=> $v){
                            $row[$k] = trim(stripslashes($v));
                        }
                        if(is_dir($upload_path)){
                            $fileO = scandir($upload_path);
                            if(isset($fileO[2]))
                                $img = "uploads/product_".$row['pid']."/".$fileO[2];
                            // var_dump($fileO);
                        }
                ?>
                    <div class="d-flex w-100 justify-content-between  mb-2 py-2 border-bottom cart-item">
                        <div class="d-flex align-items-center col-8">
                            <span class="mr-2"><a href="javascript:void(0)" class="btn btn-sm btn-outline-danger rem_item" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></a></span>
                            <img src="<?php echo validate_image($img) ?>" loading="lazy" class="cart-prod-img mr-2 mr-sm-2" alt="">
                            <div>
                                <p class="mb-1 mb-sm-1"><?php echo $row['name'] ?></p>
                                
                                <p class="mb-1 mb-sm-1"><small><b>Price:</b> <span class="precio"><?php echo number_format($row['precio']) ?></span></small></p>
                                <div>
                                <div class="input-group" style="width:130px !important">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-sm btn-outline-secondary min-qty" type="button" id="button-addon1"><i class="fa fa-minus"></i></button>
                                    </div>
                                    <input type="number" class="form-control form-control-sm qty text-center cart-qty" placeholder="" aria-label="Example text with button addon" value="<?php echo $row['cantidad'] ?>" aria-describedby="button-addon1" data-id="<?php echo $row['id'] ?>" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-outline-secondary plus-qty" type="button" id="button-addon1"><i class="fa fa-plus"></i></button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col text-right align-items-center d-flex justify-content-end">
                            <h4><b class="total-Monto"><?php echo number_format($row['precio'] * $row['cantidad']) ?></b></h4>
                        </div>
                    </div>
                <?php endwhile; ?>
                <div class="d-flex w-100 justify-content-between mb-2 py-2 border-bottom">
                    <div class="col-8 d-flex justify-content-end"><h4>Total: $</h4></div>
                    <div class="col d-flex justify-content-end"><h4 id="grand-total">-</h4></div>
                </div>
            </div>
        </div>
        <div class="d-flex w-100 justify-content-end">
            <a href="./?p=Pagar" class="btn btn-sm btn-flat btn-dark">Pagar</a>
        </div>
    </div>
</section>
<script>
    function calc_total(){
        var total  = 0

        $('.total-Monto').each(function(){
            Monto = $(this).text()
            Monto = Monto.replace(/\,/g,'')
            Monto = parseFloat(Monto)
            total += Monto
        })
        $('#grand-total').text(parseFloat(total).toLocaleString('en-US'))
    }
    function qty_change($type,_this){
        var qty = _this.closest('.cart-item').find('.cart-qty').val()
        var precio = _this.closest('.cart-item').find('.precio').text()
            precio = precio.replace(/,/g,'')
            console.log(precio)
        var cart_id = _this.closest('.cart-item').find('.cart-qty').attr('data-id')
        var new_total = 0
        start_loader();
        if($type == 'minus'){
            qty = parseInt(qty) - 1
        }else{
            qty = parseInt(qty) + 1
        }
        precio = parseFloat(precio)
        // console.log(qty,precio)
        new_total = parseFloat(qty * precio).toLocaleString('en-US')
        _this.closest('.cart-item').find('.cart-qty').val(qty)
        _this.closest('.cart-item').find('.total-Monto').text(new_total)
        calc_total()

        $.ajax({
            url:'classes/Master.php?f=update_cart_qty',
            method:'POST',
            data:{id:cart_id, cantidad: qty},
            dataType:'json',
            error:err=>{
                console.log(err)
                alert_toast("an error occured", 'error');
                end_loader()
            },
            success:function(resp){
                if(!!resp.status && resp.status == 'success'){
                    end_loader()
                }else{
                    alert_toast("an error occured", 'error');
                    end_loader()
                }
            }

        })
    }
    function rem_item(id){
        $('.modal').modal('hide')
        var _this = $('.rem_item[data-id="'+id+'"]')
        var id = _this.attr('data-id')
        var item = _this.closest('.cart-item')
        start_loader();
        $.ajax({
            url:'classes/Master.php?f=delete_cart',
            method:'POST',
            data:{id:id},
            dataType:'json',
            error:err=>{
                console.log(err)
                alert_toast("an error occured", 'error');
                end_loader()
            },
            success:function(resp){
                if(!!resp.status && resp.status == 'success'){
                    item.hide('slow',function(){ item.remove() })
                    calc_total()
                    end_loader()
                }else{
                    alert_toast("an error occured", 'error');
                    end_loader()
                }
            }

        })
    }
    function empty_cart(){
        start_loader();
        $.ajax({
            url:'classes/Master.php?f=empty_cart',
            method:'POST',
            data:{},
            dataType:'json',
            error:err=>{
                console.log(err)
                alert_toast("an error occured", 'error');
                end_loader()
            },
            success:function(resp){
                if(!!resp.status && resp.status == 'success'){
                   location.reload()
                }else{
                    alert_toast("an error occured", 'error');
                    end_loader()
                }
            }

        })
    }
    $(function(){
        calc_total()
        $('.min-qty').click(function(){
            qty_change('minus',$(this))
        })
        $('.plus-qty').click(function(){
            qty_change('plus',$(this))
        })
        $('#empty_cart').click(function(){
            // empty_cart()
            _conf("Seguro que quiere vaciar el carrito?",'empty_cart',[])
        })
        $('.rem_item').click(function(){
            _conf("¿Estás seguro de eliminar el artículo de la lista del carrito?",'rem_item',[$(this).attr('data-id')])
        })
    })
</script>