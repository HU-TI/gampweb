<?
if (empty($mes)) {
	$mes = date("n");
	$ano = date("Y");
}

$aMes = array("nulo","Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
$mesext = $aMes[intval($mes)];

$next = mktime(0,0,0,$mes + 1,1,$ano);
$nextano = date("Y",$next);
$nextmes = date("n",$next);

$prev = mktime(0,0,0,$mes - 1,1,$ano);
$prevano = date("Y",$prev);
$prevmes = date("n",$prev);

$d = mktime(0,0,0,$mes,1,$ano);
$diaSem = date('w',$d);
?>
<table class="FormTABLE" bgcolor="#DDDDDD" width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
	<tr>
		<td class="ColumnTD"><div align="center"><a class="ColumnFontLink" href="<? echo $_SERVER['PHP_SELF']; ?>?mes=<? echo $prevmes; ?>&ano=<? echo $prevano; ?>">&laquo;</a></div></td>
		<td class="ColumnTD" colspan="5"><div align="center"><font class="ColumnFONT"><? echo $mesext." &middot; ". $ano?></font></div></td>
		<td class="ColumnTD"><div align="center"><a class="ColumnFontLink" href="<? echo $_SERVER['PHP_SELF']; ?>?mes=<? echo $nextmes; ?>&ano=<? echo $nextano; ?>">&raquo;</a></div></td>
	</tr>
	<tr>
		<td class="ColumnTD" width="14%"><div align="center"><b><font class="ColumnFONT">Do</font></b></div></td>
		<td class="ColumnTD" width="14%"><div align="center"><b><font class="ColumnFONT">Se</font></b></div></td>
		<td class="ColumnTD" width="14%"><div align="center"><b><font class="ColumnFONT">Te</font></b></div></td>
		<td class="ColumnTD" width="14%"><div align="center"><b><font class="ColumnFONT">Qu</font></b></div></td>
		<td class="ColumnTD" width="14%"><div align="center"><b><font class="ColumnFONT">Qu</font></b></div></td>
		<td class="ColumnTD" width="14%"><div align="center"><b><font class="ColumnFONT">Se</font></b></div></td>
		<td class="ColumnTD" width="14%"><div align="center"><b><font class="ColumnFONT">Sa</font></b></div></td>
	</tr>
	<?
	echo "<tr>";
	for ($i = 0; $i < $diaSem; $i++) echo "<td width='14%'>&nbsp;</td>";
	for ($i = 2; $i < 33; $i++) {
		$linha = date('d',$d);
		if ($i > 3) { }
		$bold = "";
		$bold_end = "";
		
		// sábado ou domingo? troca cor
		if ((date("w",$d)==6)||(date("w",$d)==0)) {
			$bold = "<font style='color: #cc3333'>";
			$bold_end = "</font>";
		}
		
		// hoje? troca cor
		if (date("dmY")==$linha.$mes.$ano) {
			$bold = "<b><font style='color: #30655B;'>";
			$bold_end = "</font></b>";
		}
		echo "<td width='14%' onmouseover=\"this.style.backgroundColor='#EEEEEE'\" onmouseout=\"this.style.backgroundColor='#DDDDDD'\"><div align=center><a class='DataFONT' href='../content/objeto_lista.php?rodou=s&pesq_data=".$linha."/".$mes."/".$ano."' target='content'>".$bold.$linha.$bold_end."</a></div></td>";
		if (date('w',$d) == 6) echo "</tr>";
		$d = mktime(0,0,0,$mes ,$i, $ano);
		if (date('d',$d) == "01") break;
	}
	?>
</table>