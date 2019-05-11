<?php 

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
?>

<?php 
$form = ActiveForm::begin([
    'action' => ['home'],
    'options' => [
        'class' => 'comment-form'
    ]
]); 
?>
<div id="error"></div>
    <?= $form->field($model, 'name', ['inputOptions' => ['id' => 'city']])->textInput(['min'=>1,'max' => 2 ])->input('name', ['placeholder' => "Enter Your Email"]); ?>

    <?= Html::button('Search City', ['class' => 'btn']) ?>

<div id="show"></div>

<?php ActiveForm::end(); ?>

<?php
$script = <<< JS

$(document).ready(function() {
       $(".btn").click(function() {
         	     var city = $("#city").val();

         	     if(city != '')
         	     {
         	     	$.ajax({
         	     		url:'http://api.openweathermap.org/data/2.5/weather?q=' + city + "&appid=c438b94e9064ccfbb26538fc3701cb26",
         	     		type: "GET",
         	     		dataType: "json",
         	     		success: function(data){
         	     			var widget = show(data);

         	     			$("#show").html(widget);

         	     			$("#city").val('');
         	     		}
         	     	});
         	     }
  	       	     else
         	     {
         	     	$("#error").html('Field cannot be empty');
         	     }
         });
    });

    function show(data)
    {
    	return "<h5 style='font-size:20px; font-weight:bold;' class='text-center'>Current Weather for " + data.name + ", " + data.sys.country + "</h5>" + 
    		"<h5 style='font-size:20px;' class='text-center'><strong>Weather</strong>: <img src='http://openweathermap.org/img/w/"+data.weather[0].icon+".png'> " + data.weather[0].main + "</h5>" +
    		"<h5 style='font-size:20px;' class='text-center'><strong>Description</strong>: " + data.weather[0].description + "</h5>" +
    		"<h5 style='font-size:20px;' class='text-center'><strong>Temperature</strong>: " + data.main.temp + " &deg;C</h5>" +
    		"<h5 style='font-size:20px;' class='text-center'><strong>Pressure</strong>: " + data.main.pressure + " hPa</h5>" +
    		"<h5 style='font-size:20px;' class='text-center'><strong>Humidity</strong>: " + data.main.humidity + " %</h5>" +
    		"<h5 style='font-size:20px;' class='text-center'><strong>Min Temperature</strong>: " + data.main.temp_min + " &deg;C</h5>" +
    		"<h5 style='font-size:20px;' class='text-center'><strong>Max Temperature</strong>: " + data.main.temp_max + " &deg;C</h5>" + 
    		"<h5 style='font-size:20px;' class='text-center'><strong>Wind Speed</strong>: " + data.wind.speed + " m/s</h5>" + 
    		"<h5 style='font-size:20px;' class='text-center'><strong>Wind Direction</strong>: " + data.wind.deg + " &deg;C</h5>";;
    }

JS;
$this->registerJs($script);

?>

