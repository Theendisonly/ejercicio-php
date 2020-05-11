<?php
    class Factura{
        var $id;
        var $transporte;
        var $cantidad_unidades;
        var $tipo_unidad;
        var $costo_unidad;
        var $descuento;//porcentaje de descuento por cada 20 contenedores
        var $descuento_total;//solo para barco
        var $total;
        
        function __construct($id, $transporte, $cantidad_unidades, $costo_unidad, $descuento)
        {
            $this->id = $id;
            $this->transporte = $transporte;
            $this->cantidad_unidades = $cantidad_unidades;
            $this->costo_unidad = $costo_unidad;
            $this->descuento = $descuento;
        }

        function calcular_totales()   
        {
            if($this->transporte == "barco")
            {
                $this->tipo_unidad = "contenedores";
                $descuentos_aplicados = 0;
                $descuento_total = 0;
                $aux = $this->cantidad_unidades;

                $descuentos_aplicados = intval($aux/20);
                $descuento_total = ((($this->descuento*$this->costo_unidad)/100)*20)*$descuentos_aplicados;
                $this->descuento_total = $descuento_total;
                $this->total = ($this->costo_unidad*$this->cantidad_unidades)-$this->descuento_total;
            }else{
                $this->tipo_unidad = "vagones";
                $this->descuento = 0;
                $this->descuento_total = 0;
                $this->total = ($this->costo_unidad*$this->cantidad_unidades);
            }
        }
    }
?>