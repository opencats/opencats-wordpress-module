﻿<?php
/**
 * $Id: default.php 10094 2008-03-02 04:35:10Z instance $
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<form action="index.php?option=com_catsone" method="post" name="adminForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<thead>
		
	</thead>
<table cellpadding=0 cellspacing=0 width=100%>
   <tr>
     <td width=100% bgcolor='#efefef' height=30>
       <center>
          <b>Lista aktualnych ofert pracy : </b>
       </center>
     </td>
   </tr>
   <tr>
			<td align="right" colspan="6">
			<?php 
				echo JText::_('Display Num') .'&nbsp;';
				echo $this->pagination->getLimitBox();
		    ?>
			</td>
		</tr>
   <tr>
      <td width=100% style='padding:10px;'>
         <table cellpadding=0 cellspacing=0 width=100% style='border:1px solid #efefef;padding:10px;'>
           <tr>
             <td width=5% style='text-align:center;font-weight:bold;border-right:1px solid white;height:25px;' bgcolor='#efefef'>
             	<b>#</b>
             </td>
             <td style='text-align:center;border-right:1px solid white;' bgcolor='#efefef'>
                 <b>Stanowisko</b>
             </td>
             <td style='text-align:center;border-right:1px solid white;' bgcolor='#efefef'>
                 <b>Data rozpoczęcia</b>
             </td>
             <td style='text-align:center;border-right:1px solid white;' bgcolor='#efefef'>
                 <b>Miejscowość</b>
             </td>      
             <td style='text-align:center;border-right:1px solid white;' bgcolor='#efefef'>
                 <b>Województwo</b>
             </td>       
             <td style='text-align:center;border-right:1px solid white;' bgcolor='#efefef'>
                 <b>Firma</b>
             </td>
             <td style='text-align:center;border-right:1px solid white;' bgcolor='#efefef'>
                 <b>Autor wpisu</b>
             </td>    
             <td style='text-align:center;' bgcolor='#efefef' width=7%>
                 <b>Aplikuj</b>
             </td>         
           </tr>
           <?php
           $catsone = $this->catsone;
           for($i=0;$i<count($catsone);$i++)
           {
           	if($i % 2 ==0)
           	{
           		$bgcolor = "white";
           	}
           	else {
           		$bgcolor = "#eeeeee";
           	}
           	?>
           	  <tr>
           	    <td bgcolor='<?=$bgcolor?>' height=20 style='border-right:1px solid white;'>
           	      <center>
           	       <?=$i+1?>
           	      </center>
           	    </td>
           	    <td bgcolor='<?=$bgcolor?>' style='padding-left:10px;border-right:1px solid white;'>
           	      <a href='<? echo Jroute::_('index.php?option=com_catsone&task=viewDetails&id='.$catsone[$i]->joborder_id);?>'><?=$catsone[$i]->title?></a>
           	    </td>
           	    <td bgcolor='<?=$bgcolor?>' style='padding-left:20px;border-right:1px solid white;'>
           	      <?=$catsone[$i]->start_date?>
           	    </td>
           	    <td bgcolor='<?=$bgcolor?>' style='text-align:center;border-right:1px solid white;'>
           	      <?=$catsone[$i]->city?>
           	    </td>
           	    <td bgcolor='<?=$bgcolor?>' style='text-align:center;border-right:1px solid white;'>
           	      <?=$catsone[$i]->state?>
           	    </td>
           	    <td bgcolor='<?=$bgcolor?>' style='text-align:center;border-right:1px solid white;'>
           	      <?=$catsone[$i]->name?>
           	    </td>
           	    <td bgcolor='<?=$bgcolor?>' style='text-align:center;border-right:1px solid white;'>
           	      <?=$catsone[$i]->last_name?>
           	    </td>
           	    <td bgcolor='<?=$bgcolor?>' style='text-align:center;'>
           	  	  <center><a href='#'>Aplikuj</a></center>
           	    </td>
           	  </tr>
           	<?
           }
           ?>
         </table>
      </td>
   </tr>
		<tr>
			<td align="center" colspan="6" class="sectiontablefooter" height=20 width=100%>
				<?php
					echo $this->pagination->getPagesLinks(); 
				?>
			</td>
		</tr>
		<tr>
			<td colspan="6" align="right" height=20 width=100%>
				<?php echo $this->pagination->getPagesCounter(); ?>
			</td>
		</tr>
</table>
<input type="hidden" name="option" value="com_catsone" />
<input type="hidden" name="jobType" value="<?php echo $this->jobType;?>" />
</form>
</div>
