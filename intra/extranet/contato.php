<?php
include "config.php";
$pagina = $_GET["pagina"];
$busca = "SELECT * FROM usuario";
$total_reg = "25"; // número de registros por página
if (!$pagina) {
    $pc = "1";
} else {
    $pc = $pagina;
}
$inicio = $pc - 1;
$inicio = $inicio * $total_reg;

$limite = mysql_query("$busca ORDER BY nome ASC LIMIT $inicio,$total_reg");
$todos = mysql_query("$busca");

$tr = mysql_num_rows($todos); // verifica o número total de registros
$tp = $tr / $total_reg; // verifica o número total de páginas
// FIM DO CABEÇALHO DA TABELA
// DEFINI OS CRITÉRIOS DE SEU SELECT
//$consulta = "SELECT * FROM evento ORDER BY dt_data DESC";  
//$consulta = "SELECT * FROM inform WHERE idMenu = $idMenu ORDER BY idInform DESC";
$resultado = mysql_query($busca);

print'<div id="txt_titulo_contato">
        <ul>
    <table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="280"><b>Nome</b></td>
        <td width="220" align="left"><b>e-mail</b></td>
        <td width="100" align="left"><b>Telefone</b></td>
        </tr>';
while ($dados = mysql_fetch_array($limite)) {
$cod_usuario = $dados['cod_usuario'];
$nome = $dados['nome'];
$email = $dados['email'];
$fone = $dados['fone'];
    print' <tr>
        <td width="280"><font id="txt_titulo_contato">'.$nome.'</font></td>
        <td width="220" align="left"><div id="txt_linha_contato">'.$email.'</div></td>
        <td width="100" align="left"><div id="txt_linha_contato">'.$fone.'</div></td>
        </tr>';  

       // print'<td><li class="texto_contato">'.$nome.'</td><td><b>'.$email.'</b></td><td>'.$fone.'</li></td>';
}
print'</table>
    </ul>
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
  echo "<a href='?menu=contato&src=icone-profissionais.jpg&titulo=Profissionais&pagina=$anterior' class='preto'> anterior </a>&nbsp;";
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
  echo "<a href='?menu=contato&src=icone-profissionais.jpg&titulo=Profissionais&pagina=" . $pi . " ' class='preto'>" . $pi . "</a>&nbsp;";
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
  echo "&nbsp;<a href='?menu=contato&src=icone-profissionais.jpg&titulo=Profissionais&pagina=$proximo' class='preto'>próxima</a>";
  }else{
  echo "&nbsp;<font class='preto' style='color: #FFFFFF'>próxima </font>";
  }
     
print'</h3>
    </div>';

?>
