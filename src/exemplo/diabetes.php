<?php

    $arquivo = file_get_contents("diabetes.csv");
    $tabela = explode("\n",$arquivo);

    $k_folds = 10;

    $tam_tabela = count($tabela);

    $p = 0;
    $n = 0;
    $minimos = [];
    $maximos = [];



    for($i=1;$i<($tam_tabela-1); $i++){
        $instancia[$i] = explode(",",$tabela[$i]);
        $tam_instancia = count($instancia[$i]);
        for($j = 0; $j< $tam_instancia; $j++ )
        {

            if  ((isset($minimos[$j])==false)||($instancia[$i][$j] < $minimos[$j])){
                $minimos[$j] = $instancia[$i][$j];
            }
            if ((isset($maximos[$j])==false)||($instancia[$i][$j] > $maximos[$j])){
                $maximos[$j] = $instancia[$i][$j];
            }
        }

        if ($instancia[$i][$tam_instancia-1] == 1){
            $classe_positivo[$p] = $instancia[$i];
            $p++;
        }
        else{
            $classe_negativo[$n] =  $instancia[$i];
            $n++;
        }
    }

    $tam_classe_positivo = count($classe_positivo);
    $tam_classe_negativo = count($classe_negativo);
    $tam_total = $tam_classe_positivo + $tam_classe_negativo;

    for ($i=0;$i<$tam_classe_positivo;$i++){
        for($j=0; $j< $tam_instancia;$j++){
            //$positivo_normalizado[$i][$j] = ($classe_positivo[$i][$j] - $minimos[$j]) / ($maximos[$j] - $minimos[$j]);
            $positivo_normalizado[$i][$j] = $classe_positivo[$i][$j];
        }
    }
    for ($i=0;$i<$tam_classe_negativo;$i++){
        for($j=0; $j< $tam_instancia;$j++){
            //$negativo_normalizado[$i][$j] = ($classe_negativo[$i][$j] - $minimos[$j]) / ($maximos[$j] - $minimos[$j]);
            $negativo_normalizado[$i][$j] = $classe_negativo[$i][$j];
        }
    }

    for($i=0;$i<$tam_total;$i++){
        for ($j=0;$j<$k_folds;$j++){
            if(count($positivo_normalizado)>0){
                $folds[$j][]=array_pop($positivo_normalizado);
            }
            if(count($negativo_normalizado)>0){
                $folds[$j][]=array_pop($negativo_normalizado);
            }

        }
    }






echo "<pre>";
var_dump($negativo_normalizado);





