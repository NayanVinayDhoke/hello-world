<?
if ($_REQUEST["purchasing"] == "yes")
{
	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");
}
?>
<script language="javascript">

    function add_comm_item(comm_id, comp_id)
	{
		if (window.XMLHttpRequest)
		{
		  xmlhttp=new XMLHttpRequest();
		}
		else
		{
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		  xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
			  document.getElementById("table_commodity_id").innerHTML = xmlhttp.responseText;
			}
		}

		xmlhttp.open("GET","loop_recycling_add_item.php?comm_id="+ comm_id + "&comp_id=" + comp_id, true);
		xmlhttp.send();
	}	

    function remove_comm_item(comm_id, comp_id)
	{
		if (window.XMLHttpRequest)
		{
		  xmlhttp=new XMLHttpRequest();
		}
		else
		{
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		  xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
			  document.getElementById("table_commodity_id").innerHTML = xmlhttp.responseText;
			}
		}

		xmlhttp.open("GET","loop_recycling_add_item.php?removeflg=1&comm_id="+ comm_id + "&comp_id=" + comp_id, true);
		xmlhttp.send();
	}	
	
    function recycling_notes_update(comp_id)
	{
		if (window.XMLHttpRequest)
		{
		  xmlhttp=new XMLHttpRequest();
		}
		else
		{
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		  xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
			  //document.getElementById("table_commodity_id").innerHTML = xmlhttp.responseText;
			  alert("Notes updated");
			}
		}

		xmlhttp.open("GET","loop_recycling_update_notes.php?notes_data="+ encodeURIComponent(document.getElementById("recycling_notes").value) + "&comp_id=" + comp_id, true);
		xmlhttp.send();
	}	
	
	
</script>
<?
if  ($_REQUEST["show"] == "recycling") 
{

	$loop_rec_found = "no"; $sales_flg = "no"; $rescue_flg = "no";
	$res_totcnt = db_query("Select loopid, haveNeed from companyInfo where ID = " . $_REQUEST["ID"] . " and loopid > 0", db_b2b() );
	while ($row_totcnt = array_shift($res_totcnt)) 
	{
		$loop_rec_found = "yes";
		if ($row_totcnt["haveNeed"] == "Need Boxes"){
			$sales_flg = "yes";
		}	
		if ($row_totcnt["haveNeed"] == "Have Boxes"){
			$rescue_flg = "yes";
		}	
	}
	?>

	


<table id="table_commodity_id" cellSpacing="1" cellPadding="1" style="width: 550px" border="0">
	<tr>
		<td>
			<font Face='arial' size='4' color='#333333'><b> Commodity Master </b></font>
	    </td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td align="center" colspan="3" height="13" class="style1">
			<font size="1" ><a style="color:#0000FF;" target="_blank" href="commodity_master_details.php?proc=listing&compid=<? echo $_REQUEST["ID"];?>">Select/View/Edit Commodity Master Record</a></font>
			&nbsp;&nbsp;&nbsp;
			<font size="1"><a href="commodity_master_details.php?proc=New&compid=<? echo $_REQUEST["ID"];?>" target="_blank">Add New Commodity Master Record</a></font>
		</td>
	</tr>
</table>
<br><br>

<?	
echo "<Font Face='arial' size='4' color='#333333'><b>Commodity</b><br><br>";
?>

<table id="table_commodity_id" cellSpacing="1" cellPadding="1" width="800" border="0">
	<tr>
		<td bgcolor="#c0cdda">
			<font size="1">COMMODITY MASTER</font>
	    </td>
		<td >
			&nbsp;
	    </td>
		<td bgcolor="#c0cdda">
			<? if ($sales_flg == "yes") {?>
				<font size="1">ACCEPTED COMMODITIES</font>
			<? }
			
			if ($rescue_flg == "yes") {?>	
				<font size="1">COMMODITIES GENERATED</font>
			<? }?>
			
	    </td>
    </tr>
	

	<tr>
		<td valign="top">
			<table>
	<?
		$res_totcnt = db_query("Select * from commodity_master where commodity_id not in (select commodity_id from commodity_trans where comp_id_b2b = " . $_REQUEST["ID"] . ") order by commodity", db() );
		while ($row_totcnt = array_shift($res_totcnt)) 
		{?>
			<tr>
				<td bgcolor="#E4E4E4">
					<font size="1" color="#333333"><? echo $row_totcnt["commodity"]; ?></font>
				</td>
				<td bgcolor="#E4E4E4">
					<input type="button" id="btn_add" name="btn_add" value=">>" onclick="add_comm_item(<? echo $row_totcnt["commodity_id"];?>, <? echo $_REQUEST["ID"];?>)" />
				</td>
			</tr>				
		<?
		}?>	
			</table>		
		<td >
			&nbsp;
	    </td>
		<td valign="top">
			<table>
				<?
				$res_totcnt = db_query("Select * from commodity_trans inner join commodity_master on commodity_master.commodity_id = commodity_trans.commodity_id where comp_id_b2b = " . $_REQUEST["ID"] . " order by unqid", db() );
				while ($row_totcnt = array_shift($res_totcnt)) 
				{?>
					<tr>
						<td bgcolor="#E4E4E4">
							<font size="1" color="#333333"><? echo $row_totcnt["commodity"]; ?></font>
						</td>
						<td bgcolor="#E4E4E4">
							<input type="button" id="btn_remove" name="btn_remove" value="<<" onclick="remove_comm_item(<? echo $row_totcnt["commodity_id"];?>, <? echo $_REQUEST["ID"];?>)" />
						</td>
					</tr>				
				<?
				}
				?>		
			</table>
	    </td>
    </tr>
	
</table>


<br><br>

<?
	$res_totcnt = db_query("Select recycling_notes from companyInfo where ID = " . $_REQUEST["ID"], db_b2b() );
	while ($row_totcnt = array_shift($res_totcnt)) 
	{
		$recycling_notes = $row_totcnt["recycling_notes"];
	}

?>

<form method="POST" action="#" id="RecyclingNotes" name="RecyclingNotes">
  <input type=hidden name="RecyclingNotes_comp_id" value="<?=$_REQUEST["ID"];?>">
  <table border="0" width="600" cellspacing="0" cellpadding="0">
    <tr>
      <td width="100%" align="center"><b>Recycling Notes </b><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><br/>
(These notes apply to Recycling Commodities related topics. For notes related to the account in general please use the 'Internal Notes' box available at the Company main page)
	</font><br><textarea rows="16" name="recycling_notes" id="recycling_notes" cols="3" style="width:90%"><?=$recycling_notes?></textarea> 
	<br/><input style="cursor:pointer;" type="button" value="Update" name="B112" onclick="recycling_notes_update(<? echo $_REQUEST["ID"];?>)"></td>
    </tr>
  </table>
</form>
<br/>

 
<?

 
}?>
