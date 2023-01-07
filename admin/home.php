<h1>Bienvenido <?php echo $_settings->info('name') ?></h1>
<hr>
<div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-maroon elevation-1"><i class="fas fa-mobile-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Stock total</span>
                <span class="info-box-number">
                  <?php 
                    $inv = $conn->query("SELECT sum(cantidad) as total FROM inventario ")->fetch_assoc()['total'];
                    $Ventas = $conn->query("SELECT sum(cantidad) as total FROM lista_de_orden where pedidos_id in (SELECT pedidos_id FROM Ventas) ")->fetch_assoc()['total'];
                    echo number_format($inv - $Ventas);
                  ?>
                  <?php ?>
                </span>
              </div>
           
            </div>
           
          </div>
         
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-th-list"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Ordenes pendientes</span>
                <span class="info-box-number">
                  <?php 
                    $pending = $conn->query("SELECT sum(id) as total FROM `pedidos` where status = '0' ")->fetch_assoc()['total'];
                    echo number_format($pending);
                  ?>
                </span>
              </div>
            </div>
          </div>
          <div class="clearfix hidden-md-up"></div>
        </div>
<div class="container">
  <?php 
    $files = array();
      $fopen = scandir(base_app.'uploads/banner');
      foreach($fopen as $fname){
        if(in_array($fname,array('.','..')))
          continue;
        $files[]= validate_image('uploads/banner/'.$fname);
      }
  ?>
  <div id="tourCarousel"  class="carousel slide" data-ride="carousel" data-interval="3000">
      <div class="carousel-inner h-100">
          <?php foreach($files as $k => $img): ?>
          <div class="carousel-item  h-100 <?php echo $k == 0? 'active': '' ?>">
              <img class="d-block w-100  h-100" style="object-fit:contain" src="<?php echo $img ?>" alt="">
          </div>
          <?php endforeach; ?>
      </div>
      <a class="carousel-control-prev" href="#tourCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Anterior</span>
      </a>
      <a class="carousel-control-next" href="#tourCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Siguente</span>
      </a>
  </div>
</div>
