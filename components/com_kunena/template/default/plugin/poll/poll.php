<?php
/**
* @version $Id$
* Kunena Component
* @package Kunena
*
* @Copyright (C) 2010 www.kunena.com All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.kunena.com
**/
// Dont allow direct linking
defined( '_JEXEC' ) or die();


$do 			= JRequest::getVar("do", "");
$id 			= intval(JRequest::getVar("id", ""));
$catid 			= JRequest::getInt('catid', 0);
$value_choosed	= JRequest::getInt('radio', '');
CKunenaPolls::call_javascript_vote();
if ($do == 'results')
{
    //Prevent spam from users
    CKunenaPolls::save_results($id,$kunena_my->id,$value_choosed);
}

elseif( $do == 'dbchangevote')
{
	CKunenaPolls::save_changevote($id,$kunena_my->id,$value_choosed);
}
elseif ($do == 'vote')
{
	CKunenaPolls::call_javascript_vote();
	$dataspollresult = CKunenaPolls::get_poll_data($id);
	?>
<div>
            <?php
            if (file_exists(KUNENA_ABSTMPLTPATH . '/pathway.php'))
            {
                require_once (KUNENA_ABSTMPLTPATH . '/pathway.php');
            }
            else
            {
                require_once (KUNENA_PATH_TEMPLATE_DEFAULT .DS. 'pathway.php');
            }
            ?>
  </div>
<div class="k_bt_cvr1">
<div class="k_bt_cvr2">
<div class="k_bt_cvr3">
<div class="k_bt_cvr4">
<div class="k_bt_cvr5">
    <table class = "kblocktable" id = "kpoll" border = "0" cellspacing = "0" cellpadding = "0" width="100%">
        <thead>
            <tr>
                <th align="left">
                    <div class = "ktitle_cover km">
                        <span class = "ktitle kl"><?php echo _KUNENA_POLL_NAME; ?> <?php echo $dataspollresult[0]->title; ?></span>
                    </div>

                    <img id = "BoxSwitch_polls__polls_tbody" class = "hideshow" src = "<?php echo KUNENA_URLIMAGESPATH . 'shrink.gif' ; ?>" alt = ""/>
                </th>
            </tr>
        </thead>
        <tbody id = "polls_tbody">
                <tr class = "ksectiontableentry2">
                    <td class = "td-1 km" align="left">
                        <div class = "polldesc">
                        <div style="font-weight:bold;" id="poll_text_help"></div>
                        <fieldset>
                        <legend style="font-size: 14px;"><?php echo _KUNENA_POLL_OPTIONS; ?></legend>
                        <ul>
	<?php
    for ($i=0; $i < sizeof($dataspollresult);$i++)
    {
       echo "<li><input type=\"radio\" name=\"radio\" id=\"radio_name".$i."\" value=\"".$dataspollresult[$i]->id."\" />".$dataspollresult[$i]->text."</li>";
    }
       ?>
    	</ul></fieldset>
		<div class="poll_center" id="poll_buttons">
       <input id="k_poll_button_vote" type="button" value="<?php echo _KUNENA_POLL_BUTTON_VOTE; ?>" />
       <input type="hidden" id="k_poll_nb_options" name="pollid" value="<?php echo sizeof($dataspollresult); ?>">
       <input type="hidden" id="k_poll_id" name="nb_options" value="<?php echo $id; ?>">
       <input type="hidden" id="k_poll_do" name="nb_options" value="pollvote">
       <?php
       echo '	'.CKunenaLink::GetThreadLink('view',$catid,$id,kunena_htmlspecialchars ( stripslashes ( _KUNENA_POLL_NAME_URL_RESULT ) ), kunena_htmlspecialchars ( stripslashes ( _KUNENA_POLL_NAME_URL_RESULT ) ), 'follow');
    ?>
    	</div>
     </div>
                	  </td>
                 </tr>
        </tbody>
    </table>
    </div>
</div>
</div>
</div>
</div>
    <?php
}
elseif ($do == 'changevote')
{

	CKunenaPolls::call_javascript_vote();
	$dataspollresult = CKunenaPolls::get_poll_data($id);
	//Remove one vote to the user concerned and remove one vote in option
	$id_last_vote = CKunenaPolls::get_last_vote_id($kunena_my->id,$id);
	CKunenaPolls::change_vote($kunena_my->id,$id,$id_last_vote);
	?>
 	<div>
            <?php
            if (file_exists(KUNENA_ABSTMPLTPATH . '/pathway.php'))
            {
                require_once (KUNENA_ABSTMPLTPATH . '/pathway.php');
            }
            else
            {
                require_once (KUNENA_PATH_TEMPLATE_DEFAULT .DS. 'pathway.php');
            }
            ?>
  </div>
<div class="k_bt_cvr1">
<div class="k_bt_cvr2">
<div class="k_bt_cvr3">
<div class="k_bt_cvr4">
<div class="k_bt_cvr5">
    <table class = "kblocktable" id = "kpoll" border = "0" cellspacing = "0" cellpadding = "0" width="100%">
        <thead>
            <tr>
                <th align="left">
                    <div class = "ktitle_cover km">
                        <span class = "ktitle kl"><?php echo _KUNENA_POLL_NAME; ?> <?php echo $dataspollresult[0]->title; ?></span>
                    </div>

                    <img id = "BoxSwitch_polls__polls_tbody" class = "hideshow" src = "<?php echo KUNENA_URLIMAGESPATH . 'shrink.gif' ; ?>" alt = ""/>
                </th>
            </tr>
        </thead>
        <tbody id = "polls_tbody">
                <tr class = "ksectiontableentry2">
                    <td class = "td-1 km" align="left">
                        <div class = "polldesc">
                        <div style="font-weight:bold;" id="poll_text_help"></div>
                        <fieldset><legend style="font-size: 14px;"><?php _KUNENA_POLL_OPTIONS; ?></legend><ul>
	<?php
    for ($i=0; $i < sizeof($dataspollresult);$i++)
    {
    	if($dataspollresult[$i]->id == $id_last_vote){
       		echo "<li><input type=\"radio\" name=\"radio\" id=\"radio_name".$i."\" value=\"".$dataspollresult[$i]->id."\" checked />".$dataspollresult[$i]->text."</li>";
    	}else {
			echo "<li><input type=\"radio\" name=\"radio\" id=\"radio_name".$i."\" value=\"".$dataspollresult[$i]->id."\" />".$dataspollresult[$i]->text."</li>";
    	}
    }
       ?>
    	</ul></fieldset>
		<div class="poll_center" id="poll_buttons">
       <input id="k_poll_button_vote" type="button" value="<?php echo _KUNENA_POLL_BUTTON_VOTE; ?>" />
       <input type="hidden" id="k_poll_nb_options" name="pollid" value="<?php echo sizeof($dataspollresult); ?>">
       <input type="hidden" id="k_poll_id" name="nb_options" value="<?php echo $id; ?>">
       <input type="hidden" id="k_poll_do" name="nb_options" value="pollchangevote">
       <?php
		echo '	'.CKunenaLink::GetThreadLink('view',$catid,$id,kunena_htmlspecialchars ( stripslashes ( _KUNENA_POLL_NAME_URL_RESULT ) ), kunena_htmlspecialchars ( stripslashes ( _KUNENA_POLL_NAME_URL_RESULT ) ), 'follow');
		?>
		</div>
       </div>
                	  </td>
                 </tr>
        </tbody>
    </table>
    </div>
</div>
</div>
</div>
</div>
<?php
}
?>