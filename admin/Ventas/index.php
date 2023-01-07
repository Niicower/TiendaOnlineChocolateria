<style>
    table td,table th{
        padding: 3px !important;
    }
</style>
<?php 
$date_start = isset($_GET['date_start']) ? $_GET['date_start'] :  date("Y-m-d",strtotime(date("Y-m-d")." -7 days")) ;
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] :  date("Y-m-d") ;
?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h5 class="card-title">Reporte de ventas</h5>
    </div>
    <div class="card-body">
        <form id="filter-form">
            <div class="row align-items-end">
                <div class="form-group col-md-3">
                    <label for="date_start">Fecha de inicio</label>
                    <input type="date" class="form-control form-control-sm" name="date_start" value="<?php echo date("Y-m-d",strtotime($date_start)) ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="date_start">Fecha de finalización</label>
                    <input type="date" class="form-control form-control-sm" name="date_end" value="<?php echo date("Y-m-d",strtotime($date_end)) ?>">
                </div>
                <div class="form-group col-md-1">
                    <button class="btn btn-flat btn-block btn-primary btn-sm"><i class="fa fa-filter"></i> Filtrar</button>
                </div>
                <div class="form-group col-md-1">
                    <button class="btn btn-flat btn-block btn-success btn-sm" type="button" id="printBTN"><i class="fa fa-print"></i> Imprimir</button>
                </div>
            </div>
        </form>
        <hr>
        <div id="printable">
            <div class="row row-cols-2 justify-content-center align-items-center" id="print_header" style="display:none">
                <div class="col-1">
                    <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="<?php echo $_settings->info('short_name') ?>" width="75px" heigth="75px">
                </div>
                <div class="col-7">
                    <h4 class="text-center m-0"><?php echo $_settings->info('name') ?></h4>
                    <h3 class="text-center m-0"><b>Reporte de ventas</b></h3>
                    <p class="text-center m-0">Fecha entre <?php echo $date_start ?> y <?php echo $date_end ?></p>
                </div>
            </div>
            <hr>

            <table class="table table-bordered">
                <colgroup>
                    <col width="5">
                    <col width="10">
                    <col width="10">
                    <col width="10">
                    <col width="10">
                    <col width="10">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Cliente</th>
                        <th>cantidad</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                        $qry = $conn->query("SELECT * FROM `ventas` where date(fecha_de_creacion) between '{$date_start}' and '{$date_end}' order by unix_timestamp(fecha_de_creacion) desc ");
                        while($row = $qry->fetch_assoc()):
                            $olist = $conn->query("SELECT ol.*,p.name,b.name as bname,concat(c.firstname,' ',c.apellido) as name,c.email,o.fecha_de_creacion FROM lista_de_orden ol inner join pedidos o on o.id = ol.pedidos_id inner join `productos` p on p.id = ol.producto_id inner join clientes c on c.id = o.cliente_id inner join Categoria b on p.categoria_id = b.id  where ol.pedidos_id = '{$row['pedidos_id']}' ");
                            while($roww = $olist->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++ ?></td>
                        <td><?php echo $row['fecha_de_creacion'] ?></td>
                        <td>
                            <p class="m-0"><?php echo $roww['name'] ?></p>
                            <p class="m-0"><small>Categoria: <?php echo $roww['bname'] ?></small></p>
                        </td>
                        <td>
                            <p class="m-0"><?php echo $roww['name'] ?></p>
                            <p class="m-0"><small>Email: <?php echo $roww['email'] ?></small></p>
                        </td>
                        <td class="text-center"><?php echo $roww['cantidad'] ?></td>
                        <td class="text-right"><?php echo number_format($roww['cantidad'] * $roww['precio']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <?php endwhile; ?>
                    <?php if($qry->num_rows <= 0): ?>
                    <tr>
                        <td class="text-center" colspan="6">Sin datos...</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<noscript>
    <style>
        .m-0{
            margin:0;
        }
        .text-center{
            text-align:center;
        }
        .text-right{
            text-align:right;
        }
        .table{
            border-collapse:collapse;
            width: 100%
        }
        .table tr,.table td,.table th{
            border:1px solid gray;
        }
    </style>
</noscript>
<script>
    $(function(){
        $('#filter-form').submit(function(e){
            e.preventDefault()
            location.href = "./?page=Ventas&date_start="+$('[name="date_start"]').val()+"&date_end="+$('[name="date_end"]').val()
        })

        $('#printBTN').click(function(){
            var head = $('head').clone();
            var rep = $('#printable').clone();
            var ns = $('noscript').clone().html();
            start_loader()
            rep.prepend(ns)
            rep.prepend(head)
            rep.find('#print_header').show()
            var nw = window.document.open('','_blank','width=900,height=600')
                nw.document.write(rep.html())
                nw.document.close()
                setTimeout(function(){
                    nw.print()
                    setTimeout(function(){
                        nw.close()
                        end_loader()
                    },200)
                },300)
        })
    })
</script>