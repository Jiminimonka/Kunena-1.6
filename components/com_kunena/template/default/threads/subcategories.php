<?php
/**
 * @version $Id$
 * Kunena Component
 * @package Kunena
 *
 * @Copyright (C) 2008 - 2010 Kunena Team All rights reserved
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
defined ( '_JEXEC' ) or die ();

global $kunena_icons;
$kunena_emoticons = smile::getEmoticons ( 0 );
?>
<!--  sub cat -->
<?php

if (sizeof ( $this->subcats ) == 0)
	;
else {
	?>
<!-- B: List Cat -->
<div class="k_bt_cvr1">
<div class="k_bt_cvr2">
<div class="k_bt_cvr3">
<div class="k_bt_cvr4">
<div class="k_bt_cvr5">
<table
	class="kblocktable<?php
	echo isset ( $this->objCatInfo->class_sfx ) ? ' fb_blocktable' . $this->objCatInfo->class_sfx : '';
	?>"
	width="100%" id="kcat<?php
	echo $this->objCatInfo->id;
	?>" border="0"
	cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th colspan="5" align="left">
			<div class="ktitle_cover km"><?php
	echo CKunenaLink::GetCategoryLink ( 'showcat', $this->objCatInfo->id, stripslashes ( $this->objCatInfo->name ), $rel = 'follow', $class = 'fb_title fbl' );
	?>

	<?php
	echo $this->forumdesc;
	?>
			</div>

			<div class="fltrt"><span id="subcat_list"><a class="ktoggler close"
				rel="catid_<?php
	echo $this->objCatInfo->id;
	?>"></a></span></div>

			<!-- <img
				id="BoxSwitch_<?php
	echo $this->objCatInfo->id;
	?>__catid_<?php
	echo $this->objCatInfo->id;
	?>"
				class="hideshow"
				src="<?php
	echo KUNENA_URLIMAGESPATH . 'shrink.gif';
	?>" alt="" /> --></th>
		</tr>
	</thead>

	<tbody id="catid_<?php
	echo $this->objCatInfo->id;
	?>">
		<tr class="ksth ks">
			<th class="th-1 ksectiontableheader" width="1%">&nbsp;</th>
			<th class="th-2 ksectiontableheader" align="left"><?php
	echo _GEN_FORUM;
	?></th>
			<th class="th-3 ksectiontableheader" align="center" width="5%"><?php
	echo _GEN_TOPICS;
	?></th>

			<th class="th-4 ksectiontableheader" align="center" width="5%"><?php
	echo _GEN_REPLIES;
	?></th>

			<th class="th-5 ksectiontableheader" align="left" width="25%"><?php
	echo _GEN_LAST_POST;
	?></th>
		</tr>

		<?php
	foreach ( $this->subcats as $singlerow ) {
		$letPass = 1;

		if ($letPass) {
			//    $k=for alternating row colours:
			$k = 1 - $k;
			//count the number of topics posted in each forum
			$this->db->setQuery ( "SELECT id FROM #__fb_messages WHERE catid='{$singlerow->id}' AND parent='0' AND hold='0'" );
			$num = $this->db->loadObjectList ();
			check_dberror ( "Unable to load messages." );
			$numtopics = count ( $num );
			//count the number of replies posted in each forum
			$this->db->setQuery ( "SELECT id FROM #__fb_messages WHERE catid='{$singlerow->id}' AND parent!='0' AND hold='0'" );
			$num = $this->db->loadObjectList ();
			check_dberror ( "Unable to load messages." );
			$numreplies = count ( $num );
			//Get the last post from each forum
			$this->db->setQuery ( "SELECT MAX(time) FROM #__fb_messages WHERE catid='{$singlerow->id}' AND hold='0' AND moved!='1'" );
			$lastPosttime = $this->db->loadResult ();
			check_dberror ( "Unable to get max time." );

			if ($this->my->id != 0) {
				//    get all threads with posts after the users last visit; don't bother for guests
				$this->db->setQuery ( "SELECT thread FROM #__fb_messages WHERE catid='{$singlerow->id}' AND hold='0' AND time>'{$this->prevCheck}' GROUP BY thread" );
				$newThreadsAll = $this->db->loadObjectList ();
				check_dberror ( "Unable to load messages." );

				if (count ( $newThreadsAll ) == 0)
					$newThreadsAll = array ();
			}

			//    Get the forumdescription
			$this->db->setQuery ( "SELECT description, id FROM #__fb_categories WHERE id='{$singlerow->id}'" );
			$forumDesc = $this->db->loadResult ();
			check_dberror ( "Unable to load categories." );
			//    Get the forumsubparent categories
			$this->db->setQuery ( "SELECT id, name FROM #__fb_categories WHERE parent='{$singlerow->id}' AND published='1'" );
			$forumparents = $this->db->loadObjectList ();
			check_dberror ( "Unable to load categories." );

			//    get latest post info
			$latestname = "";
			$latestcatid = "";
			$latestid = "";

			if ($lastPosttime != 0) {
				$this->db->setQuery ( "SELECT id, thread, catid,name, subject, userid FROM #__fb_messages WHERE time='{$lastPosttime}' AND hold='0' AND moved!='1'", 0, 1 );
				$obj_lp = $this->db->loadObject ();
				$latestname = $obj_lp->name;
				$latestcatid = $obj_lp->catid;
				$latestid = $obj_lp->id;
				$latestsubject = $obj_lp->subject;
				$latestuserid = $obj_lp->userid;
				$latestthread = $obj_lp->thread;
				// count messages in thread
				$this->db->setQuery ( "SELECT COUNT(id) FROM #__fb_messages WHERE thread='{$latestthread}' AND hold='0'" );
				$latestcount = $this->db->loadResult ();
				$latestpage = ceil ( $latestcount / $this->config->messages_per_page );
			}
			?>
		<tr
			class="k<?php
			echo $this->tabclass [$k];
			echo isset ( $singlerow->class_sfx ) ? ' k' . $this->tabclass [$k] . $singlerow->class_sfx : '';
			?>"
			id="kcat<?
			echo $singlerow->id;
			?>">
			<td class="td-1" align="center"><?php
			$categoryicon = '';

			$cxThereisNewInForum = 0;
			if ($this->config->shownew && $this->my->id != 0) {
				//Check if unread threads are in any of the forums topics
				$newPostsAvailable = 0;

				foreach ( $newThreadsAll as $nta ) {
					if (! in_array ( $nta->thread, $this->read_topics )) {
						$newPostsAvailable ++;
					}
				}

				if ($newPostsAvailable > 0 && count ( $newThreadsAll ) != 0) {
					$cxThereisNewInForum = 1;

					// Check Unread    Cat Images
					if (is_file ( KUNENA_ABSCATIMAGESPATH . $singlerow->id . "_on.gif" )) {
						$categoryicon .= "<img src=\"" . KUNENA_URLCATIMAGES . $singlerow->id . "_on.gif\" border=\"0\" class='forum-cat-image' alt=\" \" />";
					} else {
						$categoryicon .= isset ( $kunena_icons ['unreadforum'] ) ? '<img src="' . KUNENA_URLICONSPATH . $kunena_icons ['unreadforum'] . '" border="0" alt="' . _GEN_FORUM_NEWPOST . '" title="' . _GEN_FORUM_NEWPOST . '"/>' : stripslashes ( $this->config->newchar );
					}
				} else {
					// Check Read Cat Images
					if (is_file ( KUNENA_ABSCATIMAGESPATH . $singlerow->id . "_off.gif" )) {
						$categoryicon .= "<img src=\"" . KUNENA_URLCATIMAGES . $singlerow->id . "_off.gif\" border=\"0\" class='forum-cat-image' alt=\" \"  />";
					} else {
						$categoryicon .= isset ( $kunena_icons ['readforum'] ) ? '<img src="' . KUNENA_URLICONSPATH . $kunena_icons ['readforum'] . '" border="0" alt="' . _GEN_FORUM_NOTNEW . '" title="' . _GEN_FORUM_NOTNEW . '"/>' : stripslashes ( $this->config->newchar );
					}
				}
			} // Not Login Cat Images
else {
				if (is_file ( KUNENA_ABSCATIMAGESPATH . $singlerow->id . "_notlogin.gif" )) {
					$categoryicon .= "<img src=\"" . KUNENA_URLCATIMAGES . $singlerow->id . "_notlogin.gif\" border=\"0\" class='forum-cat-image' alt=\" \" />";
				} else {
					$categoryicon .= isset ( $kunena_icons ['notloginforum'] ) ? '<img src="' . KUNENA_URLICONSPATH . $kunena_icons ['notloginforum'] . '" border="0" alt="' . _GEN_FORUM_NOTNEW . '" title="' . _GEN_FORUM_NOTNEW . '"/>' : stripslashes ( $this->config->newchar );
				}
			}
			echo CKunenaLink::GetCategoryLink ( 'listcat', $singlerow->id, $categoryicon, 'follow' );
			echo '</td>';
			echo '<td class="td-2"  align="left"><div class="kthead-title kl">' . CKunenaLink::GetCategoryLink ( 'showcat', $singlerow->id, stripslashes ( $singlerow->name ), 'follow' );

			//new posts available
			if ($cxThereisNewInForum == 1 && $this->my->id > 0) {
				echo '<sup><span class="newchar">&nbsp;(' . $newPostsAvailable . ' ' . stripslashes ( $this->config->newchar ) . ")</span></sup>";
			}

			$cxThereisNewInForum = 0;

			if ($singlerow->locked) {
				echo isset ( $kunena_icons ['forumlocked'] ) ? '&nbsp;&nbsp;<img src="' . KUNENA_URLICONSPATH . $kunena_icons ['forumlocked'] . '" border="0" alt="' . _GEN_LOCKED_FORUM . '" title="' . _GEN_LOCKED_FORUM . '"/>' : '&nbsp;&nbsp;<img src="' . KUNENA_URLEMOTIONSPATH . 'lock.gif"  border="0" alt="' . _GEN_LOCKED_FORUM . '">';
				$lockedForum = 1;
			}

			if ($singlerow->review) {
				echo isset ( $kunena_icons ['forummoderated'] ) ? '&nbsp;&nbsp;<img src="' . KUNENA_URLICONSPATH . $kunena_icons ['forummoderated'] . '" border="0" alt="' . _GEN_MODERATED . '" title="' . _GEN_MODERATED . '"/>' : '&nbsp;&nbsp;<img src="' . KUNENA_URLEMOTIONSPATH . 'review.gif" border="0"  alt="' . _GEN_MODERATED . '"/>';
				$moderatedForum = 1;
			}

			echo '</div>';

			if ($forumDesc != "") {
				$tmpforumdesc = stripslashes ( smile::smileReplace ( $forumDesc, 0, $this->config->disemoticons, $kunena_emoticons ) );
				$tmpforumdesc = nl2br ( $tmpforumdesc );
				echo '<div class="kthead-desc  km">' . $tmpforumdesc . ' </div>';
			}

			if (count ( $forumparents ) > 0) {
				if (count ( $forumparents ) == 1) {
					echo '<div class="kthead-child  ks"><b>' . _KUNENA_CHILD_BOARD . ' </b>';
				} else {
					echo '<div class="kthead-child  ks"><b>' . _KUNENA_CHILD_BOARDS . ' </b>';
				}
				;

				foreach ( $forumparents as $forumparent ) {
					?>

			<?php //Begin: parent read unread iconset
					if ($this->config->showchildcaticon) {
						//
						if ($this->config->shownew && $this->my->id != 0) {
							//    get all threads with posts after the users last visit; don't bother for guests
							$this->db->setQuery ( "SELECT thread FROM #__fb_messages WHERE catid='{$forumparent->id}' AND hold='0' and time>'{$this->prevCheck}' GROUP BY thread" );
							$newPThreadsAll = $this->db->loadObjectList ();
							check_dberror ( "Unable to load messages." );

							if (count ( $newPThreadsAll ) == 0)
								$newPThreadsAll = array ();
							?>

			<?php

							//Check if unread threads are in any of the forums topics
							$newPPostsAvailable = 0;

							foreach ( $newPThreadsAll as $npta ) {
								if (! in_array ( $npta->thread, $this->read_topics )) {
									$newPPostsAvailable ++;
								}
							}

							if ($newPPostsAvailable > 0 && count ( $newPThreadsAll ) != 0) {

								// Check Unread    Cat Images
								if (is_file ( KUNENA_ABSCATIMAGESPATH . $forumparent->id . "_on_childsmall.gif" )) {
									echo "<img src=\"" . KUNENA_URLCATIMAGES . $forumparent->id . "_on_childsmall.gif\" border=\"0\" class='forum-cat-image' alt=\" \" />";
								} else {
									echo isset ( $kunena_icons ['unreadforum'] ) ? '<img src="' . KUNENA_URLICONSPATH . $kunena_icons ['unreadforum_childsmall'] . '" border="0" alt="' . _GEN_FORUM_NEWPOST . '" title="' . _GEN_FORUM_NEWPOST . '" />' : stripslashes ( $this->config->newchar );
								}
							} else {
								// Check Read Cat Images
								if (is_file ( KUNENA_ABSCATIMAGESPATH . $forumparent->id . "_off_childsmall.gif" )) {
									echo "<img src=\"" . KUNENA_URLCATIMAGES . $forumparent->id . "_off_childsmall.gif\" border=\"0\" class='forum-cat-image' alt=\" \" />";
								} else {
									echo isset ( $kunena_icons ['readforum'] ) ? '<img src="' . KUNENA_URLICONSPATH . $kunena_icons ['readforum_childsmall'] . '" border="0" alt="' . _GEN_FORUM_NOTNEW . '" title="' . _GEN_FORUM_NOTNEW . '" />' : stripslashes ( $this->config->newchar );
								}
							}
						}

						// Not Login Cat Images
						else {
							if (is_file ( KUNENA_ABSCATIMAGESPATH . $forumparent->id . "_notlogin_childsmall.gif" )) {
								echo "<img src=\"" . KUNENA_URLCATIMAGES . $forumparent->id . "_notlogin_childsmall.gif\" border=\"0\" class='forum-cat-image' alt=\" \" />";
							} else {
								echo isset ( $kunena_icons ['notloginforum'] ) ? '<img src="' . KUNENA_URLICONSPATH . $kunena_icons ['notloginforum_childsmall'] . '" border="0" alt="' . _GEN_FORUM_NOTNEW . '" title="' . _GEN_FORUM_NOTNEW . '" />' : stripslashes ( $this->config->newchar );
							}
							?>

			<?php
						}
						//
					}
					// end: parent read unread iconset
					?>

			<?php
					echo CKunenaLink::GetCategoryLink ( 'showcat', $forumparent->id, stripslashes ( $forumparent->name ), 'follow' ) . ' &nbsp;';
				}

				echo "</div>";
			}

			//get the Moderator list for display
			$this->db->setQuery ( "SELECT * FROM #__fb_moderation AS m LEFT JOIN #__users AS u ON u.id=m.userid WHERE m.catid='{$singlerow->id}'" );
			$modslist = $this->db->loadObjectList ();
			check_dberror ( "Unable to load moderators." );

			// moderator list
			if (count ( $modslist ) > 0) {
				echo '<div class="kthead-moderators  ks">' . _GEN_MODERATORS . ": ";

				$mod_cnt = 0;
				foreach ( $modslist as $mod ) {
					if ($mod_cnt)
						echo ', ';
					$mod_cnt ++;
					echo CKunenaLink::GetProfileLink ( $this->config, $mod->userid, ($this->config->username ? $mod->username : $mod->name) );
				}

				echo '</div>';
			}

			if (CKunenaTools::isModerator ( $this->my->id, $this->catid )) {
				$this->db->setQuery ( "SELECT COUNT(*) FROM #__fb_messages WHERE catid='{$singlerow->id}' AND hold='1'" );
				$numPending = $this->db->loadResult ();
				if ($numPending > 0) {
					echo '<div class="ks"><font color="red">';
					echo CKunenaLink::GetCategoryReviewListLink ( $singlerow->id, $numPending . ' ' . _SHOWCAT_PENDING, 'nofollow' );
					echo '</font></div>';
				}
			}
			?>
			</td>
			<td class="td-3 km" align="center"><?php
			echo $numtopics;
			?></td>
			<td class="td-4 km" align="center"><?php
			echo $numreplies;
			?></td>
			<?php
			if ($numtopics != 0) {
				?>

			<td class="td-5" align="left">
			<div class="klatest-subject km">
			<?php
				echo CKunenaLink::GetThreadLink ( 'view', $latestcatid, $latestthread, kunena_htmlspecialchars ( stripslashes ( $latestsubject ) ), kunena_htmlspecialchars ( stripslashes ( $latestsubject ) ), $rel = 'nofollow' );
				?>
			</div>

			<div class="klatest-subject-by  ks">
			<?php
				echo _GEN_BY;
				?> <?php
				echo CKunenaLink::GetProfileLink ( $this->config, $latestuserid, $latestname, $rel = 'nofollow' );
				?>

			| <span
				title="<?php
				echo CKunenaTimeformat::showDate ( $lastPosttime, 'config_post_dateformat_hover' );
				?>"><?php
				echo CKunenaTimeformat::showDate ( $lastPosttime, 'config_post_dateformat' );
				?></span> <?php
				echo CKunenaLink::GetThreadPageLink ( $this->config, 'view', $latestcatid, $latestthread, $latestpage, $this->config->messages_per_page, (isset ( $kunena_icons ['latestpost'] ) ? '<img src="' . KUNENA_URLICONSPATH . $kunena_icons ['latestpost'] . '" border="0" alt="' . _SHOW_LAST . '" title="' . _SHOW_LAST . '" />' : '  <img src="' . KUNENA_URLEMOTIONSPATH . 'icon_newest_reply.gif" border="0"   alt="' . _SHOW_LAST . '" />'), $latestid, $rel = 'nofollow' );
				?>
			</div>
			</td>

		</tr>

		<?php
			} else {
				echo ' <td class="td-5"  align="left">' . _NO_POSTS . '</td></tr>';
			}
		}
	}
	?>
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

<!-- F: List Cat -->
<!--  /sub cat   -->