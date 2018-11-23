<?php if (!defined('FLUX_ROOT')) exit;

$this->loginRequired(Flux::message('LoginToDonate'));
$title = 'Doação PagSeguro';
  
if(Flux::config('PagSeguroEnviroment') == 'sandbox'){
      echo "<script type='text/javascript' src='".Flux::config('PagSeguroSandBoxUrl')."'> </script>";
    } else {
      echo "<script type='text/javascript' src='".Flux::config('PagSeguroUrl')."'> </script>";
    }
?>