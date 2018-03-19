<?php
$num_layers = 4;
$desired_error = 0.01;
$max_epochs = 300000;
$epochs_between_reports = 100;
$filename = "triquiData.data";

$ann = fann_create_standard_array ( $num_layers , $layers = [9,9,9,9]);
if (fann_train_on_file($ann, $filename, $max_epochs, $epochs_between_reports, $desired_error))
        fann_save($ann, dirname(__FILE__) . "/triqui.net");
echo "IA entrenada";
fann_destroy($ann);
