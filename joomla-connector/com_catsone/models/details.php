<?php
/**
 * @version		$Id: contact.php 10094 2008-03-02 04:35:10Z instance $
 * @package		Joomla
 * @subpackage	Contact
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * @package		Joomla
 * @subpackage	Contact
 */
class catsoneModeldetails extends JModel
{
	function getDetails( $option )
	{
		jimport('joomla.database.database');
		jimport( 'joomla.database.table' );
		$conf =& JFactory::getConfig();
		// TODO: Cache on the fingerprint of the arguments
		//$db			=& JFactory::getDBO();
		/*
		$user 		= $conf->getValue('config.user');
		$password 	= $conf->getValue('config.password');
		$database	= $conf->getValue('config.db');
		$prefix 	= $conf->getValue('config.dbprefix');

		$debuzg 		= $conf->getValue('config.debug');
		*/
		$host 		= $conf->getValue('config.host');
		$driver 	= $conf->getValue('config.dbtype');
		//$options	= array ( 'driver' => $driver, 'host' => $host, 'user' => $user, 'password' => $password, 'database' => 'chasejob_cats', 'prefix' => "" );
	$options	= array ( 'driver' => $driver, 'host' => 'yourcatsservername', 'user' => 'yourcatsdbusername', 'password' => '$yourcatsdbpassword', 'database' => 'yourcatsdbname', 'prefix' => "" );
		$db =& JDatabase::getInstance( $options );
		
		//Query database
		$query = "Select joborder.*,joborder.city as scity,joborder.state as sstate,joborder.title as titlejob,extra_field.*,user.*,user.last_name,company.company_id,company.name from joborder,extra_field,user,company where extra_field.data_item_id = joborder.joborder_id and joborder.status = 'active' and joborder.entered_by = user.user_id  and joborder.company_id = company.company_id ";
		if($option['joborder_id']!="")
		{
			$query.=" and joborder.joborder_id like '".$option['joborder_id']."'";
		}
		$db->setQuery($query);
		//echo $db->getQuery();
		$result = $db->loadObjectList();
		$result = $result[0];
		return $result;
	}
}