<!doctype html>
<html lang="en">

<head>
    <title>Editar</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-3">
        <div class="row">
            <div class="col-6">
                <?php
                include_once "class/factura.php";
                $result;
                if (isset($_GET["guardar"])) {
                    $datos_facturas = file_get_contents('persistencia/facturas.json');
                    $facturas = empty(json_decode($datos_facturas,true)) ? []:json_decode($datos_facturas,true);
                    
                    $factura = new factura($_GET["id"], $_GET["transporte"], $_GET["cantidad"], $_GET["costoDia"], $_GET["descuento"]);
                    $factura->calcular_totales();
                    //array_push($facturas,$factura);//llamar una forma de actualizar array
                    $facturas[array_search($_GET['id'],array_column($facturas,'id'))]=$factura;
                    //creamos o actualizamos el json
                    $json = json_encode($facturas);
                    $file = 'persistencia/facturas.json';
                    $result = file_put_contents($file,$json);
                    if(!$result)
                    {
                ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong><?php echo "Error al actualizar "?></strong>
                        </div>
                <?php
                    }
                    else
                    {
                ?>
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong>Actualización exitosa</strong>
                        </div>
                <?php
                    }
                }
                $datos_facturas = file_get_contents('persistencia/facturas.json');
                $facturas = empty(json_decode($datos_facturas,true)) ? []:json_decode($datos_facturas,true);
                $row=$facturas[array_search($_GET['id'],array_column($facturas,'id'))];
                ?>
                <form>
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="id" value="<?php echo $row['id'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Transporte</label>
                        <input type="text" class="form-control" name="transporte" value="<?php echo $row['transporte'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Precio compra</label>
                        <input type="number" class="form-control" name="costoDia" value="<?php echo $row['costo_unidad'] ?>">
                        <small id="precioHelp" class="form-text text-muted">Precio pagado por cada unidad.</small>
                    </div>
                    <div class="form-group">
                        <label>Cantidad (<?php echo $row['tipo_unidad']?>)</label>
                        <input type="number" class="form-control" name="cantidad" value="<?php echo $row['cantidad_unidades'] ?>">
                        <small id="cantidadHelp" class="form-text text-muted">Cantidad de unidades recibidas.</small>
                    </div>
                    <div class="form-group">
                        <label>Descuento: </label>
                        <input type="number" class="form-control" name="descuento" value="<?php echo $row['descuento'] ?>" >
                        <small id="descuentoHelp" class="form-text text-muted">Porcentaje de descuento para las entregas en barco por cada 20 contenedores.</small>
                    </div>
                    <div class="form-group">
                        <label>Descuento total: </label>
                        <input type="text" class="form-control" name="descuentoTotal" value="<?php echo $row['descuento_total'] ?>" disabled>
                        <small id="descuentoTotalHelp" class="form-text text-muted">Descuento calculado.</small>
                    </div>
                    <div class="form-group">
                        <label>Total: </label>
                        <input type="text" class="form-control" name="total" value="<?php echo $row['total'] ?>" disabled>
                        <small id="descuentoTotalHelp" class="form-text text-muted">Total a pagar.</small>
                    </div>
                    <!-- <div class="form-group">
                        <label>Categoria</label>
                        <input type="text" class="form-control" name="categoria" value="<?php echo $row['categoria'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Existencia</label>
                        <input type="text" class="form-control" name="unidadesEnExistencia" value="<?php echo $row['unidadesEnExistencia'] ?>">
                    </div> -->
                    <button type="submit" name="guardar" class="btn btn-primary">Guardar</button>
                    <a href="index.php">Regresar</a>
                </form>
            </div>
        </div>        
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>