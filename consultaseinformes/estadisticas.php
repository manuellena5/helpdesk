<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$nombres = "M,I,E"; 
$num = explode(",", $nombres); 
$contador = count($num); 
$arreglo= array();

if ($contador > 0)
{
  for($i = 0; $i < $contador; $i++){
    $sql =mysqli_query($con, "SELECT COUNT(*)cantidad FROM `fil01mail` WHERE `tipo_ingreso`='$num[$i]'");
    $res = mysqli_fetch_array($sql);
    $arreglo[$i] = $res["cantidad"];
  }
  $valor = json_encode($arreglo);
  
}

 ?>
<script type="text/javascript" src="js/chart/Chart.js"></script>
<h3>Estadisticas</h3>
<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <canvas id="oilChart" width="300" height="100"></canvas>
    </div>
  </div>
</div>
<script>
  var oilCanvas = document.getElementById("oilChart");

Chart.defaults.global.defaultFontFamily = "Lato";
Chart.defaults.global.defaultFontSize = 18;

var oilData = {
    labels: [
        'Mail<?php echo "[".$arreglo[0]."]"; ?>',
        'Interno<?php echo "[".$arreglo[1]."]"; ?>',
        'Entrada<?php echo "[".$arreglo[2]."]"; ?>'
    ],
    datasets: [
        {
            data: <?php echo $valor; ?>,
            backgroundColor: [
                "#FF6384",
                "#63FF84",
                "#2E2EFE"
            ]
        }]
};

var pieChart = new Chart(oilCanvas, {
  type: 'pie',
  data: oilData
});
</script>
