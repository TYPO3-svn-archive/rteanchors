<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006 Troels Kjær Rasmussen (troels@linkfactory.dk)
*  (c) 2008-2009 Netcreators BV <info@netcreators.com>
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

require_once(PATH_t3lib . 'class.t3lib_parsehtml_proc.php');

/**
 * This class contains post processing code for the "anchor:" hrefs in the
 * RTE. It is based on the tkr_anchors code of T.K.Rasmussen but contains
 * a lot of changes and bug fixes to properly follow w# HTML specifications.
 * Updates are done by Dmitry Dulepov, Netcreators BV.
 *
 * @author	Troels Kjær Rasmussen <troels@linkfactory.dk>
 * @author	Dmitry Dulepov <info@netcreators.com>
 */
class ux_t3lib_parsehtml_proc extends t3lib_parsehtml_proc {

	/** Recursion counter for processing function */
	protected $toDbEntryCount = 0;

	/**
	 * Transformation handler: 'ts_links' / direction: "db"
	 * Converting <A>-tags to <link tags>
	 *
	 * @param	string		Content input
	 * @return	string		Content output
	 * @see TS_links_rte()
	 */
	function TS_links_db($value) {
		// This function is called recursively. But we do our processing only once.
		if ($this->toDbEntryCount == 0) {
			$value = $this->convertAnchorsToNames($value);
		}
		$this->toDbEntryCount++;
		$value = parent::TS_links_db($value);
		$this->toDbEntryCount--;
		return $value;
	}


	/**
	 * Transformation handler: 'ts_links' / direction: "rte"
	 * Converting <LINK tags> to <A>-tags
	 *
	 * @param	string		Content input
	 * @return	string		Content output
	 * @see TS_links_rte()
	 */
	function TS_links_rte($value) {
		return $this->convertNamesToAchors(parent::TS_links_rte($value));
	}

	/**
	 * Converts href="anchor:xxx" to name="xxx". This function takes possible
	 * name duplicates into account.
	 *
	 * @param	string	$content	Content
	 * @return	string	Processed content
	 */
	function convertAnchorsToNames($content) {
		$blockSplit = $this->splitIntoBlock('a', $content, true);
		$result = '';
		foreach($blockSplit as $k => $v) {
			if ($k % 2) {
				$attribArray = $this->get_tag_attributes_classic($this->getFirstTag($v), 1);
				if (isset($attribArray['href']) && preg_match('/(anchor|anker):/', $attribArray['href'])) {
					if (isset($attribArray['name'])) {
						// We already have "name" attribute and we must keep it.
						// So we make another tag.
						$result .= '<a name="' .
									htmlspecialchars($attribArray['name']) .
									'" rtekeep="1"></a>';
						unset($attribArray['rtekeep']);
					}
					list(, $attribArray['name']) = explode(':', $attribArray['href'], 2);
					unset($attribArray['href']);
					$result .= '<a ' . $this->compileTagAttribs($attribArray) . '></a>' .
								$this->removeFirstAndLastTag($v);
				}
				else {
					$result .= $v;
				}
			}
			else {
				$result .= $v;
			}
		}
		return $result;
	}

	/**
	 * Converts <a name="xxx"> to <a href="anchor:xxx". This function considers
	 * the fact that tag already can have "href" attrbute. See
	 * http://www.w3.org/TR/REC-html40/struct/links.html#h-12.1.3
	 *
	 * @param	string	$content	Current content
	 * @return	strong	Post-processed content
	 */
	function convertNamesToAchors($content) {
		$blockSplit = $this->splitIntoBlock('a', $content, true);
		$result = '';
		foreach($blockSplit as $k => $v) {
			if ($k % 2) {
				$attribArray = $this->get_tag_attributes_classic($this->getFirstTag($v), 1);
				if (isset($attribArray['name'])) {
					if (isset($attribArray['href'])) {
						// We cannot set href on this tag because there is href already!
						// Therefore we add a new tag. Nested tags are illegal, so we have
						// to make this new tag empty. See
						// http://www.w3.org/TR/REC-html40/struct/links.html#h-12.2.2
						$result .= '<a href="anchor:' .
									htmlspecialchars($attribArray['name']) .
									'" rtekeep="1"></a>';
						unset($attribArray['name']);
						unset($attribArray['rtekeep']);
						$result .= '<a ' . $this->compileTagAttribs($attribArray) . '>' .
									$this->removeFirstAndLastTag($v) .
									'</a>';
					}
					else {
						$attribArray['href'] = 'anchor:' . $attribArray['name'];
						unset($attribArray['name']);
						$result .= '<a ' . $this->compileTagAttribs($attribArray) . '>' .
									$this->removeFirstAndLastTag($v) .
									'</a>';
					}
				}
				else {
					$result .= $v;
				}
			}
			else {
				$result .= $v;
			}
		}
		return $result;
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tkr_rteanchors/class.ux_t3lib_parsehtml_proc.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tkr_rteanchors/class.ux_t3lib_parsehtml_proc.php']);
}

?>