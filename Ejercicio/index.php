<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script> 
        $(document).ready( function () {
            $('#tblFacturas').DataTable();
        } );
    </script>
    <title>Facturación</title>
</head>

<body>
    <?php
        include_once "class/factura.php";
        $facturas=[];
        $factura;


    ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-6">
            <?php
                if (isset($_GET['idBorrar'])) {
                    $datos_facturas = file_get_contents('persistencia/facturas.json');
                    $facturas = empty(json_decode($datos_facturas,true)) ? []:json_decode($datos_facturas,true);
                    $factura_aux=[];
                    if(count($facturas) > 0)
                    {
                        $fact_aux = $facturas[array_search($_GET['idBorrar'],array_column($facturas,'id'))];
                        foreach($facturas as &$valor) 
                        {   

                            if( $fact_aux["id"] != $valor["id"])
                            {
                                array_push($factura_aux,$valor);
                            }
                        }
                        $facturas=$factura_aux;
                        
                    }
                    $json = json_encode($facturas);
                    $file = 'persistencia/facturas.json';
                    file_put_contents($file,$json);
                }
            ?>
                <form>
                    <div class="form-group">
                        <label class="form-check-label" for="">Medio de transporte:    </label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="medioEntrega" id="barco" value="barco" checked>
                            <label class="form-check-label" for="medioEntrega1">Barco</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="medioEntrega" id="ferrocarril" value="ferrocarril">
                            <label class="form-check-label" for="medioEntrega2">Ferrocarril</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="costoDia">Costo del día</label>
                        <input name="costoDia" type="number" class="form-control" id="costoDia"  placeholder="Precio por unidad" value="100">
                        <small id="precioHelp" class="form-text text-muted">Precio pagado por cada unidad para el día en curso.</small>
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad de unidades</label>
                        <input name="cantidad" type="number" class="form-control" id="cantidad"  placeholder="Ingrese unidades" value="0">
                        <small id="cantidadHelp" class="form-text text-muted">Cantidad de unidades recibidas.</small>
                    </div>
                    <div class="form-group">
                        <label for="descuento">Descuento</label>
                        <input name="descuento" type="number" class="form-control" id="descuento" placeholder="Descuento" value="5">
                        <small id="descuentoHelp" class="form-text text-muted">Porcentaje de descuento para las entregas en barco por cada 20 contenedores.</small>
                    </div>

                    <button name="enviar" type="submit" class="btn btn-primary">Crear factura</button>
                </form>

            </div>
        </div>

        <?php
            if (isset($_GET["enviar"])) {
                $datos_facturas = file_get_contents('persistencia/facturas.json');
                $facturas = empty(json_decode($datos_facturas,true)) ? []:json_decode($datos_facturas,true);
                if(count($facturas) == 0)
                {
                    $id = 1;
                }else{
                    $id = intval($facturas[array_key_last($facturas)]["id"])+1;
                }
                $factura = new factura($id, $_GET["medioEntrega"], $_GET["cantidad"], $_GET["costoDia"], $_GET["descuento"]);
                $factura->calcular_totales();
                array_push($facturas,$factura);
                //creamos o actualizamos el json
                $json = json_encode($facturas);
                $file = 'persistencia/facturas.json';
                file_put_contents($file,$json);
            }
        ?>
    </div>

    <div class="container mt-5">
        <table class="table table-striped" id="tblFacturas">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Cantidad Unidades</th>
                <th scope="col">Transporte</th>
                <th scope="col">Costo</th>
                <th scope="col">Porcentaje de descuento</th>
                <th scope="col">Descuento calculado</th>
                <th scope="col">Total</th>
                <th scope="col">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php 
                $datos_facturas = file_get_contents('persistencia/facturas.json');
                $facturas = empty(json_decode($datos_facturas,true)) ? []:json_decode($datos_facturas,true);
                foreach($facturas as &$valor) {
            ?>
                <tr>
                    <th scope="row"><?php echo $valor["id"]?></th>
                    <td><?php echo $valor["cantidad_unidades"]." ".$valor["tipo_unidad"]?></td>
                    <td><?php echo $valor["transporte"]?></td>
                    <td><?php echo $valor["costo_unidad"]?></td>
                    <td><?php echo $valor["descuento"]?></td>
                    <td><?php echo $valor["descuento_total"]?></td>
                    <td><?php echo $valor["total"]?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $valor['id'] ?>"><i class="fa fa-edit mr-2"></i></a>
                        <a href="index.php?idBorrar=<?php echo $valor['id'] ?>"><i class="fa fa-trash text-danger"></i></a>
                    </td>
                </tr>
            <?php 
                }
            ?>
            </tbody>
        </table>
    <?php
        
    ?>
    </div>
</body>

</html>