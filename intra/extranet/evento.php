<?php
include "config.php";
$pagina = $_GET["pagina"];
$busca = "SELECT * FROM evento";
$total_reg = "4"; // número de registros por página
if (!$pagina) {
    $pc = "1";
} else {
    $pc = $pagina;
}
$inicio = $pc - 1;
$inicio = $inicio * $total_reg;

$limite = mysql_query("$busca ORDER BY dt_data DESC LIMIT $inicio,$total_reg");
$todos = mysql_query("$busca");

$tr = mysql_num_rows($todos); // verifica o número total de registros
$tp = $tr / $total_reg; // verifica o número total de páginas
// FIM DO CABEÇALHO DA TABELA
// DEFINI OS CRITÉRIOS DE SEU SELECT
//$consulta = "SELECT * FROM evento ORDER BY dt_data DESC";  
//$consulta = "SELECT * FROM inform WHERE idMenu = $idMenu ORDER BY idInform DESC";
$resultado = mysql_query($busca);

print'<div id="texto">
        <ul>';
while ($dados = mysql_fetch_array($limite)) {
$cod_evento = $dados['cod_evento'];
$txt_titulo = $dados['txt_titulo'];
$txt_texto = $dados['txt_texto'];
$dt_data = $dados['dt_data'];
$data = "$dt_data";
#Transforma a string em array
$array_data = explode('-', $data);
$dt_data = "$array_data[2]/$array_data[1]/$array_data[0]";
#exibe 28/03/2016    
        print'<li class="texto"> <b>'.$dt_data.'<a href="#"> '.$txt_titulo.'</a></b><br>'.$txt_texto.'</li>';
}
print'</ul>
      </div>     
      </div>
    <div id="paginas">
     <h3>';
  // agora vamos criar os botões "Anterior e próximo"
  $intervalo = 10;
  $anterior = $pc -1;
  $proximo = $pc +1;
  $flag1 = floor($pc/$intervalo);
  $pi = ($flag1 * $intervalo );
  $pf = $pi + $intervalo;

  if ($pc > 1) {
  echo "<a href='?menu=evento&src=icone-QA.jpg&titulo=Quadro de Avisos&&pagina=$anterior' class='preto'> anterior </a>&nbsp;";
  //echo "&nbsp;<a href='?id_menu=prodcientifica&&pagina=$proximo' class='preto'>Próxima &#187;</a>";
  }else{
  echo "<font class='preto' style='color: #FFFFFF'> anterior&nbsp;</font>&nbsp;";
  }
  if ($pc > 1) {
  echo "<font class='preto'>/&nbsp;</font>";
  }else{
  echo "";
  }

  for ($pi; $pi < $pf; $pi++) {
  // Se número da página for menor que total de páginas
  if ($pi <= $tp) {
  if ($pc == $pi) {
  // se página atual for igual a página selecionada
  if ($pi > "0") {
  print '<b style="color:#009C9F">' . $pi . '</b>&nbsp;';
  }
  } else {
  // se for diferente, aparece o link para a página
  if ($pi > "0") {
  echo "<a href='?menu=evento&src=icone-QA.jpg&titulo=Quadro de Avisos&&pagina=" . $pi . " ' class='preto'>" . $pi . "</a>&nbsp;";
  }
  }
  }
  }
  if ($pc < $tp) {
  echo "<font class='preto'>/</font>";
  }else{
  echo "";
  }
  if ($pc < $tp) {
  echo "&nbsp;<a href='?menu=evento&src=icone-QA.jpg&titulo=Quadro de Avisos&&pagina=$proximo' class='preto'>próxima</a>";
  }else{
  echo "&nbsp;<font class='preto' style='color: #FFFFFF'>próxima </font>";
  }
     
print'</h3>
    </div>';

?>
