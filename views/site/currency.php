<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'from') ?>

    <?= $form->field($model, 'to') ?>

    <?= $form->field($model, 'amount') ?>

    <!-- <div class="alert-success">
    	<?php echo "$model->amount $model->from = $total $model->to"; ?>
    </div> -->
    <div class="alert alert-primary" role="alert">
  		<?php echo "$model->amount $model->from = $total $model->to"; ?>
	</div>


    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>