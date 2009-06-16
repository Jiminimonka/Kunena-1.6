<?php
/**
* @version $Id$
* Kunena Component
* @package Kunena
*
* @Copyright (C) 2008 - 2009 Kunena Team All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.kunena.com
*
* Based on FireBoard Component
* @Copyright (C) 2006 - 2007 Best Of Joomla All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.bestofjoomla.com
*
* Based on Joomlaboard Component
* @copyright (C) 2000 - 2004 TSMF / Jan de Graaff / All Rights Reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @author TSMF & Jan de Graaff
**/

// Dont allow direct linking
defined( '_JEXEC' ) or die('Restricted access');

$kunenaConfig =& CKunenaConfig::getInstance();
$kunena_my = &JFactory::getUser();
$kunena_db = &JFactory::getDBO();
//first we gather some information about this person
$kunena_db->setQuery("SELECT su.view, u.name, su.moderator, su.avatar FROM #__kunena_users AS su"
                    . " LEFT JOIN #__users AS u on u.id=su.userid WHERE su.userid={$kunena_my->id}", 0, 1);

$_user = $kunena_db->loadObject();

$kunenaavatar = NULL;
if ($_user != NULL)
{
	$prefview = $_user->view;
	$username = $_user->name; // externally used  by kunena_pathway, myprofile_menu
	$moderator = $_user->moderator;
	$kunenaavatar = $_user->avatar;
	$jr_username = $_user->name;
}

$jr_avatar = '';
if ($kunenaConfig->avatar_src == "jomsocial")
{
	// Get CUser object
	$jsuser =& CFactory::getUser($kunena_my->id);
    $jr_avatar = '<img src="' . $jsuser->getThumbAvatar() . '" alt=" " />';
}
else if ($kunenaConfig->avatar_src == "clexuspm")
{
    $jr_avatar = '<img src="' . MyPMSTools::getAvatarLinkWithID($kunena_my->id) . '" alt=" " />';
}
else if ($kunenaConfig->avatar_src == "cb")
{
	$jr_avatar = $kunenaProfile->showAvatar($kunena_my->id);
}
else
{
    if ($kunenaavatar != "") {
		if(!file_exists(KUNENA_PATH_UPLOADED .DS. 'avatars/s_' . $kunenaavatar)) {
            $jr_avatar = '<img src="'.KUNENA_LIVEUPLOADEDPATH.'/avatars/' . $kunenaavatar . '" alt=" " style="max-width: '.$kunenaConfig->avatarsmallwidth.'px; max-height: '.$kunenaConfig->avatarsmallheight.'px;" />';
		} else {
		  $jr_avatar = '<img src="'.KUNENA_LIVEUPLOADEDPATH.'/avatars/s_' . $kunenaavatar . '" alt=" " />';
		}
    }
    else {
 		$jr_avatar = '<img src="'.KUNENA_LIVEUPLOADEDPATH.'/avatars/s_nophoto.jpg" alt=" " />';
        $jr_profilelink = '<a href="' . JRoute::_(KUNENA_LIVEURLREL . '&amp;func=myprofile') . '" >' . _PROFILEBOX_MYPROFILE . '</a>';
    }

}

if ($kunenaConfig->kunena_profile == "cb" || $kunenaConfig->kunena_profile == "jomsocial")
{
    $jr_profilelink = CKunenaLink::GetProfileLink($kunenaConfig, $kunena_my->id, _PROFILEBOX_MYPROFILE);
}
else if ($kunenaConfig->kunena_profile == "clexuspm") {
    $jr_profilelink = '<a href="' . JRoute::_(KUNENA_LIVEURLREL . '&amp;func=myprofile') . '" >' . _PROFILEBOX_MYPROFILE . '</a>';
}
else
{
    $jr_profilelink = '<a href="' . JRoute::_(KUNENA_LIVEURLREL . '&amp;func=myprofile') . '" >' . _PROFILEBOX_MYPROFILE . '</a>';
}

$jr_myposts = '<a href="' . JRoute::_(KUNENA_LIVEURLREL .  '&amp;func=showauthor&amp;task=showmsg&amp;auth=' . $kunena_my->id . '') . '" >' . _PROFILEBOX_SHOW_MYPOSTS . '</a>';
$jr_latestpost = JRoute::_(KUNENA_LIVEURLREL . '&amp;func=latest');
?>

<?php // AFTER LOGIN AREA
if ($kunenaConfig->kunena_profile == 'cb')
{
	$loginlink = CKunenaCBProfile::getLoginURL();
	$logoutlink = CKunenaCBProfile::getLogoutURL();
	$registerlink = CKunenaCBProfile::getRegisterURL();
	$lostpasslink = CKunenaCBProfile::getLostPasswordURL();
}
else
{
	$loginlink = JRoute::_('index.php?option=com_user&amp;view=login');
	$logoutlink = JRoute::_('index.php?option=com_user&amp;view=login');
	$registerlink = JRoute::_('index.php?option=com_user&amp;task=register&amp;Itemid=' . $Itemid);
	$lostpasslink = JRoute::_('index.php?option=com_user&amp;view=reset&amp;Itemid=' . $Itemid);
}

if ($kunena_my->id)
{
?>

    <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0" class = "kunena_profilebox" >
        <tbody id = "topprofilebox_tbody">
            <tr class = "<?php echo $boardclass ;?>sectiontableentry1">
                <td  class = "td-1  kunenam" align="left" width="5%">
<?php echo CKunenaLink::GetProfileLink($kunenaConfig, $kunena_my->id, $jr_avatar);?>
                </td>

                <td valign = "top" class = "td-2  kunenam kunena_profileboxcnt" align="left">
<?php echo _PROFILEBOX_WELCOME; ?>, <b><?php echo $jr_username; ?></b>

                <br />

                <a href = "<?php echo $jr_latestpost ; ?>"><?php
    echo _PROFILEBOX_SHOW_LATEST_POSTS; ?> </a> | <?php echo $jr_profilelink; ?> |  <a href = "<?php echo $logoutlink;?>"><?php echo _PROFILEBOX_LOGOUT; ?></a>
<?php
$user_fields = @explode(',', $kunenaConfig->annmodid);

if (in_array($kunena_my->id, $user_fields) || $kunena_my->usertype == 'Administrator' || $kunena_my->usertype == 'Super Administrator') {
    $is_editor = true;
}
else {
    $is_editor = false;
}

if ($is_editor) {
?>
| <a href = "<?php echo CKunenaLink::GetAnnouncementURL($kunenaConfig, 'show');?>"><?php echo _ANN_ANNOUNCEMENTS; ?> </a>
<?php } ?>
| <?php echo CKunenaLink::GetSearchLink($kunenaConfig, 'search', '', 0, 0, _KUNENA_SEARCH_ADVSEARCH);?>

</td>
			<?php
			if (JDocumentHTML::countModules('kunena_profilebox'))
			{
			?>

			<td>
				<div class = "kunena_profilebox_modul">
				<?php
					$document	= &JFactory::getDocument();
					$renderer	= $document->loadRenderer('modules');
					$options	= array('style' => 'xhtml');
					$position	= 'kunena_profilebox';
					echo $renderer->render($position, $options, null);
				?>
				</div>
			</td>
			<?php
			}
			?>

            </tr>
        </tbody>
    </table>

    <?php
}
else
{
    // LOGOUT AREA
    ?>

    <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0"  class = "kunena_profilebox">
        <tbody id = "topprofilebox_tbody">
            <tr class = "<?php echo $boardclass ;?>sectiontableentry1">
                <td valign = "top" class = "td-1  kunenam kunena_profileboxcnt" align="left">
<?php echo _PROFILEBOX_WELCOME; ?>, <b><?php echo _PROFILEBOX_GUEST; ?></b>

                <br/> <?php echo _PROFILEBOX_PLEASE; ?>

                <a href = "<?php echo $loginlink;?>"><?php echo _PROFILEBOX_LOGIN; ?></a> <?php echo _PROFILEBOX_OR; ?> <a href = "<?php echo $registerlink;?>"><?php echo _PROFILEBOX_REGISTER; ?></a>.

                &nbsp;&nbsp;

                <a href = "<?php echo $lostpasslink;?>"><?php echo _PROFILEBOX_LOST_PASSWORD; ?></a>

</td>
			<?php
			if (JDocumentHTML::countModules('kunena_profilebox'))
			{
			?>

			<td>
				<div class = "kunena_profilebox_modul">
				<?php
					$document	= &JFactory::getDocument();
					$renderer	= $document->loadRenderer('modules');
					$options	= array('style' => 'xhtml');
					$position	= 'kunena_profilebox';
					echo $renderer->render($position, $options, null);
				?>
				</div>
			</td>

			<?php
			}
			?>

            </tr>
        </tbody>
    </table>

<?php
}
?>
