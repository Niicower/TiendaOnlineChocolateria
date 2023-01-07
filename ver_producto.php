<?php 
 $productos = $conn->query("SELECT p.*,b.name as bname FROM `productos` p inner join Categoria b on p.categoria_id = b.id where md5(p.id) = '{$_GET['id']}' ");
 if($productos->num_rows > 0){
     foreach($productos->fetch_assoc() as $k => $v){
         $$k= stripslashes($v);
     }
    $upload_path = base_app.'/uploads/product_'.$id;
    $img = "";
    if(is_dir($upload_path)){
        $fileO = scandir($upload_path);
        if(isset($fileO[2]))
            $img = "uploads/product_".$id."/".$fileO[2];
        
    }
    $inventario = $conn->query("SELECT * FROM inventario where producto_id = ".$id);
    $inv = array();
    while($ir = $inventario->fetch_assoc()){
        $inv[] = $ir;
    }
 }
?>
<section class="py-5">
    <div class="container px-4 px-lg-5 my-5">
        
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6">
                <img class="card-img-top mb-5 mb-md-0 border border-dark" loading="lazy" id="display-img" src="<?php echo validate_image($img) ?>" alt="..." />
                <div class="mt-2 row gx-2 gx-lg-3 row-cols-4 row-cols-md-3 row-cols-xl-4 justify-content-start">
                    <?php 
                        foreach($fileO as $k => $img):
                            if(in_array($img,array('.','..')))
                                continue;
                    ?>
                    <div class="col">
                        <a href="javascript:void(0)" class="view-image <?php echo $k == 2 ? "active":'' ?>"><img src="<?php echo validate_image('uploads/product_'.$id.'/'.$img) ?>" loading="lazy"  class="img-thumbnail" alt=""></a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-6">
                <!-- <div class="small mb-1">SKU: BST-498</div> -->
                <h1 class="display-5 fw-bolder border-bottom border-primary pb-1"><?php echo $name ?></h1>
                <p class="m-0"><small>Catetegoria: <?php echo $bname ?></small></p>
                <div class="fs-5 mb-5">
                $<span id="precio"><?php echo number_format($inv[0]['precio']) ?></span>
                <br>
                <span><small><b>stock disponible:</b> <span id="avail"><?php echo $inv[0]['cantidad'] ?></span></small></span>
                </div>
                <form action="" id="add-cart">
                <div class="d-flex">
                    <input type="hidden" name="precio" value="<?php echo $inv[0]['precio'] ?>">
                    <input type="hidden" name="inventario_id" value="<?php echo $inv[0]['id'] ?>">
                    <input class="form-control text-center me-3" id="inputQuantity" type="num" value="1" style="max-width: 3rem" name="cantidad" />
                    <button class="btn btn-outline-dark flex-shrink-0" type="submit">
                        <i class="bi-cart-fill me-1"></i>
                        Agregar al carrito
                    </button>
                </div>
                </form>
                <p class="lead"><?php echo stripslashes(html_entity_decode($especificaciones)) ?></p>
                
            </div>
        </div>
    </div>
</section>
<!-- Related items section-->
<section class="py-5 bg-light">
    <div class="container px-4 px-lg-5 mt-5">
        <h2 class="fw-bolder mb-4">Productos relacionados</h2>
        <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-3 row-cols-xl-4 justify-content-center">
        <?php 
            $productos = $conn->query("SELECT p.*,b.name as bname FROM `productos` p inner join Categoria b on p.categoria_id = b.id where p.status = 1 and (p.categorias_id = '{$categorias_id}' or p.sub_categoria_id = '{$sub_categoria_id}') and p.id !='{$id}' order by rand() limit 4 ");
            while($row = $productos->fetch_assoc()):
                $upload_path = base_app.'/uploads/product_'.$row['id'];
                $img = "";
                if(is_dir($upload_path)){
                    $fileO = scandir($upload_path);
                    if(isset($fileO[2]))
                        $img = "uploads/product_".$row['id']."/".$fileO[2];
                    // var_dump($fileO);
                }
                $inventario = $conn->query("SELECT * FROM inventario where producto_id = ".$row['id']);
                $_inv = array();
                foreach($row as $k=> $v){
                    $row[$k] = trim(stripslashes($v));
                }
                while($ir = $inventario->fetch_assoc()){
                    $_inv[] = number_format($ir['precio']);
                }
        ?>
            <div class="col mb-5">
                <a class="card h-100 product-item text-dark" href=".?p=ver_producto&id=<?php echo md5($row['id']) ?>">
                    <!-- Product image-->
                    <img class="card-img-top w-100" src="<?php echo validate_image($img) ?>" alt="..." />
                    <!-- Product details-->
                    <div class="card-body p-4">
                        <div class="">
                            <!-- Product name-->
                            <h5 class="fw-bolder"><?php echo $row['name'] ?></h5>
                            <!-- Product precio-->
                            <?php foreach($_inv as $k=> $v): ?>
                                <span><b>Precio: </b><?php echo $v ?></span>
                            <?php endforeach; ?>
                            <p class="m-0"><small>Categoria: <?php echo $row['bname'] ?></small></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<script>
    var inv = $.parseJSON('<?php echo json_encode($inv) ?>');
    $(function(){
        $('.view-image').click(function(){
            var _img = $(this).find('img').attr('src');
            $('#display-img').attr('src',_img);
            $('.view-image').removeClass("active")
            $(this).addClass("active")
        })
        $('.p-size').click(function(){
            var k = $(this).attr('data-id');
            $('.p-size').removeClass("active")
            $(this).addClass("active")
            $('#precio').text(inv[k].precio)
            $('[name="precio"]').val(inv[k].precio)
            $('#avail').text(inv[k].cantidad)
            $('[name="inventario_id"]').val(inv[k].id)

        })

        $('#add-cart').submit(function(e){
            e.preventDefault();
            if('<?php echo $_settings->userdata('id') ?>' <= 0){
                uni_modal("","login.php");
                return false;
            }
            start_loader();
            $.ajax({
                url:'classes/Master.php?f=add_to_cart',
                data:$(this).serialize(),
                method:'POST',
                dataType:"json",
                error:err=>{
                    console.log(err)
                    alert_toast("ocurrió un error",'error')
                    end_loader()
                },
                success:function(resp){
                    if(typeof resp == 'object' && resp.status=='success'){
                        alert_toast("Producto añadido al carrito.",'success')
                        $('#cart-count').text(resp.cart_count)
                    }else{
                        console.log(resp)
                        alert_toast("ocurrió un error",'error')
                    }
                    end_loader();
                }
            })
        })
    })
</script>