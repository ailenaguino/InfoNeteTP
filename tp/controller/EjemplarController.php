<?php


class EjemplarController
{
    private $renderer;
    private $model;
    private $modelCategoria;

    public function __construct($model, $renderer, $categoria){
        $this->renderer = $renderer;
        $this->model = $model;
        $this->modelCategoria = $categoria;
    }

    public function index(){
        $categorias["categorias"]=$this->modelCategoria->obtenerCategorias();
        echo $this->renderer->render("view/agregarEjemplar.php", $categorias);
    }
    public function listar(){
        $data["ejemplares"] = $this->model->obtenerEjemplares();
        echo $this->renderer->render("view/listarEjemplares.php", $data);
    }

    public function editar(){
        $id=$_POST["id"];
        $data["ejemplar"] = $this->model->obtenerEjemplarPorId($id);
        $data["categorias"]=$this->modelCategoria->obtenerCategorias();
        echo $this->renderer->render("view/editarEjemplar.php", $data);
    }

    public function validarEdicion(){
        $data["id"]=$_POST["id"];
        $data["nombre"]=ucfirst($_POST["nombre"]);
        $data["id_categoria"] = $_POST["categoria"];
        $data["precio"] = $_POST["precio"];

        $this->model->update($data);
        $data2["mensaje"] = "Ejemplar editado correctamente";

        $data2["ejemplar"] = $this->model->obtenerEjemplarPorId($data["id"]);
        $data2["categorias"]=$this->modelCategoria->obtenerCategorias();
        echo $this->renderer->render("view/editarEjemplar.php", $data2);
    }


    public function validar(){
        $data = array();
        $data["nombre"] = ucfirst($_POST["nombre"]);
        $data["id_categoria"] = $_POST["categoria"];
        $data["precio"] = $_POST["precio"];
        $resultado = $this->validarNombre($data["nombre"], $data["id_categoria"]);

        if($resultado==true) {
            $value=$this->model->insertar($data);
            if($value){
                $mensaje["mensaje"] = "Ejemplar agregado correctamente";
            }else{
                $mensaje["mensaje"] = "Algo salio mal";
            }

        }else{
            $mensaje["mensaje"] = "El ejemplar ya existe";
        }
        $data["categorias"]=$this->modelCategoria->obtenerCategorias();
        $data["mensaje"]=$mensaje;
        echo $this->renderer->render("view/agregarEjemplar.php", $data);
    }

    public function validarNombre($nombre, $categoria){
        $resultado = $this->model->buscarEjemplarDeCategoria(ucfirst($nombre), $categoria);

        if($resultado!=null) {
            return false;
        }else {
            return true;
        }
    }

    public function listaAdmin(){
        $data["ejemplares"] = $this->model->obtenerEjemplaresConCategoria();
        echo $this->renderer->render("view/listaEjemplaresAdmin.php", $data);
    }
    public function listaConte(){
        $data["ejemplares"] = $this->model->obtenerEjemplaresConCategoria();
        echo $this->renderer->render("view/listaEjemplaresConte.php", $data);
    }
    public function cambiarEstado()
    {
        $id = $_POST["id"];
        $estado = $_POST["estado"];
        if ($estado == 1) {
            $estado = 0;
        } else {
            $estado = 1;
        }
        $this->model->cambiarEstado($id, $estado);
        $data["ejemplares"] = $this->model->obtenerEjemplaresConCategoria();
        echo $this->renderer->render("view/listaEjemplaresAdmin.php", $data);
    }

    public function generarGraficoSuscripciones(){
        $data = array();
        $data["inicio"]=$_POST["inicio"];
        $data["fin"]=$_POST["fin"];
        $resultado = $this->model->generarGraficoSuscripciones($data);

        echo $this->renderer->render("view/generarGraficoSuscripciones.php");
        ?>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages':['bar']});
            google.charts.setOnLoadCallback(drawStuff);

            function drawStuff() {
                var data = new google.visualization.arrayToDataTable([
                    ['Fecha', 'Cantidad de Suscripciones'],
                <?php
                while($fila = $resultado->fetch_assoc()){
                    echo "['".$fila["dia"]."',".$fila["cantidad"]."],";
                }
                    ?>
                ]);

                var options = {
                    width: 800,
                    legend: { position: 'none' },
                    chart: {
                        title: 'Cantidad de suscripciones por día'},
                    axes: {
                        x: {
                            0: { side: 'top', label: 'Fecha'} // Top x-axis.
                        }
                    },
                    bar: { groupWidth: "90%" }
                };

                var chart = new google.charts.Bar(document.getElementById('top_x_div'));
                // Convert the Classic options to Material options.
                chart.draw(data, google.charts.Bar.convertOptions(options));
            };
        </script>
        <?php
    }

    public function formularioGraficoSuscripciones(){
        echo $this->renderer->render("view/generarGraficoSuscripciones.php");
    }

    public function pagarSuscripcion(){
        if(isset($_SESSION['name']) && isset($_POST['id'])){
            $id = $_POST["id"];
            $data['data']=$id;
            echo $this->renderer->render("view/pagarSuscripcion.php",$data);
        }else{
            header("Location: /usuario/index");
        }
    }
    public function procesarPagoEjemplar(){
        $idEjemplar = $_POST["idEjemplar"];
        $nombreTarjeta=$_POST["username"];
        $numeroTajerta=$_POST["cardNumber"];
        $cvv=$_POST["cvv"];
        $mes=$_POST["mes"];
        $anio=$_POST["anio"];
        $idUsuario=$_SESSION['id'];
        $hoy =date("Y"). "-" . date("m") . "-" .date("d");
        $vencimiento=date("Y-m-d",strtotime($hoy."+ 1 month"));
        try{
            $this->validarTarjeta($numeroTajerta,$cvv,$mes,$anio);
            $this->model->insertarCompra($idEjemplar,$idUsuario,$hoy,$vencimiento);
            echo $this->renderer->render("view/compraEdicionExitosa.php");
        }catch (Exception $e){
            $data["error"] = $e->getMessage();
            $data['data']=$idEjemplar;
            echo $this->renderer->render("view/pagarSuscripcion.php", $data);
        }
    }
    public function validarTarjeta($numeroTajerta,$cvv,$mes,$anio){
        if(strlen($numeroTajerta)!=16){
            throw new Exception("Ingrese una tarjeta valida");
        }
        if(strlen($cvv)!=3){
            throw new Exception("Ingrese una tarjeta valida");
        }
        if($mes==date("m")&&$anio==date("Y")){
            throw new Exception("Ingrese una tarjeta valida");
        }

    }


}