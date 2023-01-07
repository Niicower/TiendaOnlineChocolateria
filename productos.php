<?php 
$title = "";
$sub_title = "";
if(isset($_GET['c']) && isset($_GET['s'])){
    $cat_qry = $conn->query("SELECT * FROM categorias where md5(id) = '{$_GET['c']}'");
    if($cat_qry->num_rows > 0){
        $result =$cat_qry->fetch_assoc();
        $title = $result['categoria'];
        $cat_description = $result['description'];
    }
 $sub_cat_qry = $conn->query("SELECT * FROM sub_categorias where md5(id) = '{$_GET['s']}'");
    if($sub_cat_qry->num_rows > 0){
        $result =$sub_cat_qry->fetch_assoc();
        $sub_title = $result['sub_categoria'];
        $sub_cat_description = $result['description'];
    }
}
elseif(isset($_GET['c'])){
    $cat_qry = $conn->query("SELECT * FROM categorias where md5(id) = '{$_GET['c']}'");
    if($cat_qry->num_rows > 0){
        $result =$cat_qry->fetch_assoc();
        $title = $result['categoria'];
        $cat_description = $result['description'];
    }
}
elseif(isset($_GET['s'])){
    $sub_cat_qry = $conn->query("SELECT * FROM sub_categorias where md5(id) = '{$_GET['s']}'");
    if($sub_cat_qry->num_rows > 0){
        $result =$sub_cat_qry->fetch_assoc();
        $sub_title = $result['sub_categoria'];
        $sub_cat_description = $result['description'];
    }
}
?>
<!-- Header-->
<header class="bg-dark py-5" id="main-header">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
            <h1 class="display-4 fw-bolder"><?php echo $title ?></h1>
            <p class="lead fw-normal text-white-50 mb-0"><?php echo $sub_title ?></p>
        </div>
    </div>
</header>
<!-- Section-->
<section class="py-5">
    <div class="container-fluid row">
        <?php if(isset($_GET['c'])): ?>
        <div class="col-md-3 border-right mb-2 pb-3">
            <h3><b>Sub Categories</b></h3>
            <div class="list-group">
                <a href="./?p=productos&c=<?php echo $_GET['c'] ?>" class="list-group-item <?php echo !isset($_GET['s']) ? "active" : "" ?>">Todos</a>
                <?php 
                $sub_cat = $conn->query("SELECT * FROM `sub_categorias` where md5(identificador_id) =  '{$_GET['c']}' ");
                while($row = $sub_cat->fetch_assoc()):
                ?>
                    <a href="./?p=productos&c=<?php echo $_GET['c'] ?>&s=<?php echo md5($row['id']) ?>" class="list-group-item  <?php echo isset($_GET['s']) && $_GET['s'] == md5($row['id']) ? "active" : "" ?>"><?php echo $row['sub_categoria'] ?></a>
                <?php endwhile; ?>
            </div>
            <hr>
        </div>
        <?php endif; ?>
        <div class="<?php echo isset($_GET['c'])? 'col-md-9': 'col-lg-10 offset-md-1' ?>">
            <div class="container-fluid p-0">
                <?php if(isset($_GET['search'])): ?>
                    <h4 class="text-center py-5"><b>Resultados de la búsqueda de '<?php echo $_GET['search'] ?>'</b></h4>
                <?php endif; ?>
            <div class="row gx-2 gx-lg-2 row-cols-1 row-cols-md-3 row-cols-xl-4">
                    
                    <?php 
                        $whereData = "";
                        if(isset($_GET['search']))
                            $whereData = " and (p.name LIKE '%{$_GET['search']}%' or b.name LIKE '%{$_GET['search']}%' or p.especificaciones LIKE '%{$_GET['search']}%')";
                        elseif(isset($_GET['c']) && isset($_GET['s']))
                            $whereData = " and (md5(categorias_id) = '{$_GET['c']}' and md5(sub_categoria_id) = '{$_GET['s']}')";
                        elseif(isset($_GET['c']) && !isset($_GET['s']))
                            $whereData = " and md5(categorias_id) = '{$_GET['c']}' ";
                        elseif(isset($_GET['s']) && !isset($_GET['c']))
                            $whereData = " and md5(sub_categoria_id) = '{$_GET['s']}' ";
                        $productos = $conn->query("SELECT p.*,b.name as bname FROM `productos` p inner join Categoria b on p.categoria_id = b.id where p.status = 1 {$whereData} order by rand() ");
                        while($row = $productos->fetch_assoc()):
                            $upload_path = base_app.'/uploads/product_'.$row['id'];
                            $img = "";
                            if(is_dir($upload_path)){
                                $fileO = scandir($upload_path);
                                if(isset($fileO[2]))
                                    $img = "uploads/product_".$row['id']."/".$fileO[2];
                                // var_dump($fileO);
                            }
                            foreach($row as $k=> $v){
                                $row[$k] = trim(stripslashes($v));
                            }
                            $inventario = $conn->query("SELECT * FROM inventario where producto_id = ".$row['id']);
                            $inv = array();
                            while($ir = $inventario->fetch_assoc()){
                                $inv[] = number_format($ir['precio']);
                            }
                    ?>
                    <div class="col-md-12 mb-5">
                        <a class="card product-item text-dark" href=".?p=ver_producto&id=<?php echo md5($row['id']) ?>">
                            <!-- Product image-->
                            <img class="card-img-top w-100" src="<?php echo validate_image($img) ?>" loading="lazy" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder"><?php echo $row['name'] ?></h5>
                                    <!-- Product precio-->
                                    <?php foreach($inv as $k=> $v): ?>
                                        <span><b>Precio: </b><?php echo $v ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <p class="m-0"><small>Categoria: <?php echo $row['bname'] ?></small></p>
                            </div>
                        </a>
                    </div>
                    <?php endwhile; ?>
                    <?php 
                        if($productos->num_rows <= 0){
                            echo "<h4 class='text-center'><b>No Product Listed.</b></h4>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>