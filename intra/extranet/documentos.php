<?php
include "config.php";
$pagina = $_GET["pagina"];
$cod_categoria = $_GET["cat"];
$busca = "SELECT * FROM documento WHERE cod_categoria= $cod_categoria";
$total_reg = "7"; // número de registros por página
if (!$pagina) {
    $pc = "1";
} else {
    $pc = $pagina;
}
$inicio = $pc - 1;
$inicio = $inicio * $total_reg;

$limite = mysql_query("$busca ORDER BY data ASC LIMIT $inicio,$total_reg");
$todos = mysql_query("$busca");

$tr = mysql_num_rows($todos); // verifica o número total de registros
$tp = $tr / $total_reg; // verifica o número total de páginas
// FIM DO CABEÇALHO DA TABELA
// DEFINI OS CRITÉRIOS DE SEU SELECT
//$consulta = "SELECT * FROM evento ORDER BY dt_data DESC";  
//$consulta = "SELECT * FROM inform WHERE idMenu = $idMenu ORDER BY idInform DESC";
$resultado = mysql_query($busca);

print'<div id="txt_titulo_contato">
        <ul><h3>
    <table width="700" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="50"></td>
        <td width="550" align="left"><b></b></td>
        </tr>';

// INICIA A IMPRESSÃO DE CADA LINHA DO LOOP COM A COR ESPECÍFICA
while ($dados = mysql_fetch_array($limite)) {
$cod_documento     = $dados['cod_documento'];
$titulo       = $dados['titulo'];
$documento       = $dados['documento'];
$descricao       = $dados['descricao'];
$data         = $dados['data'];
$data         = "$dt_data";
#Transforma a string em array
$array_data     = explode('-', $data);
$data         = "$array_data[2]/$array_data[1]/$array_data[0]";
    print'<tr>
        <td width="50"><a href="arquivos/'.$documento.'" target="_blank"><img src="images/ico_visualizar.jpg" border="0" width="35" height="34" alt="Visualizar arquivos"></a></td>
        <td width="550" valign="middle">
          <font id="txt_titulo">'.$titulo.'</font>
          <font id="txt_linha">'.$descricao.'</font>
        </td>
        </tr>
        <tr>
        <td>&nbsp;</td>
              <td><img src="images/linha.jpg" width="600" /></td>
        </tr>';  
}
print'</table>
    </h3></ul>
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
  echo "<a href='?menu=documento&src=icone-arquivos.jpg&titulo=$titulo&cat=$cod_categoria&pagina=$anterior' class='preto'> anterior </a>&nbsp;";
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
  echo "<a href='?menu=documento&src=icone-arquivos.jpg&titulo=$titulo&cat=$cod_categoria&pagina=" . $pi . " ' class='preto'>" . $pi . "</a>&nbsp;";
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
  echo "&nbsp;<a href='?menu=documento&src=icone-arquivos.jpg&titulo=$titulo&cat=$cod_categoria&pagina=$proximo' class='preto'>próxima</a>";
  }else{
  echo "&nbsp;<font class='preto' style='color: #FFFFFF'>próxima </font>";
  }
print'</h3>
    </div>';

?>
