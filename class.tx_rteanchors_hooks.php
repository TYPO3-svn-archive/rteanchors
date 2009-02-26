<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Netcreators BV <info@netcreators.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * $Id: $
 */

require_once(PATH_t3lib . 'interfaces/interface.t3lib_browselinkshook.php');

/**
 * This class
 *
 * @author	Dmitry Dulepov <dmitry@typo3.org>
 * @package	TYPO3
 * @subpackage	tx_rteanchorshooks
 */
class tx_rteanchors_hooks implements t3lib_browseLinksHook {

	/**
	 * Parent object
	 *
	 * @var	tx_rtehtmlarea_browse_links
	 */
	protected $pObj;

	/**
	 * Current anchor in the RTE
	 *
	 * @var	string
	 */
	protected $currentAnchor = '';

	/**
	 * Initializes the hook object
	 *
	 * @param	browse_links	parent browse_links object
	 * @param	array		additional parameters
	 * @return	void
	 */
	public function init($parentObject, $additionalParameters) {
		$this->pObj = $parentObject;

		$GLOBALS['LANG']->includeLLfile('EXT:rteanchors/locallang.xml');
	}

	/**
	 * adds new items to the currently allowed ones and returns them
	 *
	 * @param	array	currently allowed items
	 * @return	array	currently allowed items plus added items
	 */
	public function addAllowedItems($currentlyAllowedItems) {
		$currentlyAllowedItems[] = 'anchor';
		return $currentlyAllowedItems;
	}

	/**
	 * modifies the menu definition and returns it
	 *
	 * @param	array	menu definition
	 * @return	array	modified menu definition
	 */
	public function modifyMenuDefinition($menuDef) {
		if (in_array('anchor', $this->pObj->allowedItems)) {
			$menuDef['anchor']['isActive'] = ($this->pObj->act == 'anchor');
			$menuDef['anchor']['label'] = $GLOBALS['LANG']->getLL('rteanchors.anchor');
			$menuDef['anchor']['url'] = '#';
			$menuDef['anchor']['addParams'] = 'onclick="jumpToUrl(\''.htmlspecialchars('?act=anchor&mode=' . $this->pObj->mode . '&bparams=' . $this->pObj->bparams).'\');return false;"';
		}
		return $menuDef;
	}

	/**
	 * returns a new tab for the browse links wizard
	 *
	 * @param	string		current link selector action
	 * @return	string		a tab for the selected link action
	 */
	public function getTab($linkSelectorAction) {
		$content = '';
		if ($linkSelectorAction == 'anchor') {
			$content = '
				<form action="" name="lurlform" id="lurlform">
					<table border="0" cellpadding="2" cellspacing="1" id="typo3-linkURL">
						<tr>
							<td>' . $GLOBALS['LANG']->getLL('rteanchors.anchor_field_label') . ':</td>
							<td><input type="text" name="lurl"' .
								$this->pObj->doc->formWidth(20) . ' value="'.$this->currentAnchor.'" />
								<input type="button" value="' . $GLOBALS['LANG']->getLL('rteanchors.insert_anchor') . '"
									onclick="document.lurlform.lurl.value=\'anchor:\'+document.lurlform.lurl.value+\'\';browse_links_setHref(document.lurlform.lurl.value);return link_current();" />
							</td>
						</tr>
					</table>
				</form>
				<form action="" name="ltargetform" id="ltargetform"></form>';
		}
		return $content;
	}

	/**
	 * checks the current URL and determines what to do
	 *
	 * @param	string	$href	Link
	 * @param	string	$siteUrl	Site URL
	 * @param	array	$info	Information array
	 * @return	array	Modified information array
	 */
	public function parseCurrentUrl($href, $siteUrl, $info) {
		if (preg_match('/(anchor|anker):/', $href)) {
			list(, $this->currentAnchor) = explode(':', $href, 2);
			$info['anchor'] = $this->currentAnchor;
			$info['act'] = $info['info'] = 'anchor';
		}
		return $info;
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rteanchors/class.tx_rteanchors_hooks.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rteanchors/class.tx_rteanchors_hooks.php']);
}

?>