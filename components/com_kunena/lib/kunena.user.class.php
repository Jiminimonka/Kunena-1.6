<?php
/**
* @version $Id$
* Kunena Component - CKunenaUser class
* @package Kunena
*
* @Copyright (C) 2009 www.kunena.com All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.kunena.com
**/

// Dont allow direct linking
defined( '_JEXEC' ) or die('Restricted access');

require_once (JPATH_ROOT  .DS. 'components' .DS. 'com_kunena' .DS. 'lib' .DS. 'kunena.defines.php');
require_once (KUNENA_PATH_LIB . DS . 'kunena.config.class.php');

/**
* Kunena Users Table Class
* Provides access to the #__fb_users table
*/
class CKunenaUserprofile extends JTable
{
	/**
	* User ID
	* @var int
	**/
	var $userid = null;
	var $view = null;
	/**
	* Signature
	* @var string
	**/
	var $signature = null;
	/**
	* Is moderator?
	* @var int
	**/
	var $moderator = null;
	/**
	* Ordering of posts
	* @var int
	**/
	var $ordering = null;
	/**
	* User post count
	* @var int
	**/
	var $posts = null;
	/**
	* Avatar image file
	* @var string
	**/
	var $avatar = null;
	/**
	* User karma
	* @var int
	**/
	var $karma = null;
	var $karma_time = null;
	/**
	* Kunena Group ID
	* @var int
	**/
	var $group_id = null;
	/**
	* Kunena Profile hits
	* @var int
	**/
	var $uhits = null;
	/**
	* Personal text
	* @var string
	**/
	var $personalText = null;
	/**
	* Gender
	* @var int
	**/
	var $gender = null;
	/**
	* Birthdate
	* @var string
	**/
	var $birthdate = null;
	/**
	* User Location
	* @var string
	**/
	var $location = null;
	/**
	* ICQ ID
	* @var string
	**/
	var $ICQ = null;
	/**
	* AIM ID
	* @var string
	**/
	var $AIM = null;
	/**
	* YIM ID
	* @var string
	**/
	var $YIM = null;
	/**
	* MSN ID
	* @var string
	**/
	var $MSN = null;
	/**
	* SKYPE ID
	* @var string
	**/
	var $SKYPE = null;
	/**
	* TWITTER ID
	* @var string
	**/
	var $TWITTER = null;
	/**
	* FACEBOOK ID
	* @var string
	**/
	var $FACEBOOK = null;
	/**
	* GTALK ID
	* @var string
	**/
	var $GTALK = null;
	/**
	* Name of web site
	* @var string
	**/
	var $websitename = null;
	/**
	* URL to web site
	* @var string
	**/
	var $websiteurl = null;
	/**
	* User rank
	* @var int
	**/
	var $rank = null;
	/**
	* Hide Email address
	* @var int
	**/
	var $hideEmail = null;
	/**
	* Show online
	* @var int
	**/
	var $showOnline = null;
	/**
	* MYSPACE ID
	* @var string
	**/
	var $MYSPACE = null;
	/**
	* LINKEDIN ID
	* @var string
	**/
	var $LINKEDIN = null;
	/**
	* DELICIOUS ID
	* @var string
	**/
	var $DELICIOUS = null;
	/**
	* FRIENDFEED ID
	* @var string
	**/
	var $FRIENDFEED = null;
	/**
	* $DIGG ID
	* @var string
	**/
	var $DIGG = null;
	/**
	* BLOGSPOT ID
	* @var string
	**/
	var $BLOGSPOT = null;
	/**
	* FLICKR ID
	* @var string
	**/
	var $FLICKR = null;
	/**
	* BEBO ID
	* @var string
	**/
	var $BEBO = null;
	/**
	* @param userid NULL=current user
	*/
	function CKunenaUserprofile($userid)
	{
		$kunena_db = &JFactory::getDBO();
		parent::__construct('#__fb_users', 'userid', $kunena_db);
		if ($userid) $this->load($userid);
	}

	function &getInstance($userid=null, $reload=false)
	{
		return CKunenaUserHelper::getInstance($userid, $reload);
	}
}

class CKunenaUserHelper {
	static $instances = array();
	function &getInstance($userid=null, $reload=false)
	{
		if ($userid === null) {
			$user =& JFactory::getUser();
			$userid = $user->get('id');
		}
		if ($reload || !isset(self::$instances[$userid])) {
			self::$instances[$userid] = new CKunenaUserprofile($userid);
		}
		return self::$instances[$userid];
	}

}