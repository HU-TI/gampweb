<?php     
   print'<div id="titulo">
      <h1><img alt="'.$titulo.'" src="images/'.$src.'">'.$titulo.'</h1>
       </div>';
    
     switch ($menu){
    
    case "evento":
      include("evento.php");
    break;
    
    case "contato":
      include("contato.php");
    break;  

    case "documento":
      include("documentos.php");
    break;      
}
?>