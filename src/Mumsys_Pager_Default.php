<?php

/**
 * Mumsys_Pager_Default
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2013 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Pager
 * Created: 2007-07-14
 * $Id: Mumsys_Pager.php 2820 2013-11-12 12:11:46Z flobee $
 */


/**
 * Pagination in html context.
 *
 * Example:
 * <code>
 * $opts = array(
 *   'cntitems' => $cntitems,
 *   'pagestart' => $pagestart,
 *   'limit' => $limit,
 *   'basiclink' => $basiclink, // "http://myhomepage/cms/xyz.php?way=here"
 *   'pagestartVarname' => $pagestartVarname,
 *   'showPageNumbers' => $showPageNumbers,
 *   'showSummary' => $showSummary,
 * );
 * $oPager = new Mumsys_Pager($opts);
 * $html = $oPager->getHtml();
 * </code>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Pager
 */
class Mumsys_Pager_Default
    extends Mumsys_Abstract
{
    /**
     * Version ID information.
     */
    const VERSION = '3.0.0';

    /**
     * Message for: "next page"
     */
    const PAGER_PAGENEXT = 'PAGER_PAGENEXT';

    /**
     * Message for: "prev page"
     */
    const PAGER_PAGEPREV = 'PAGER_PAGEPREV';

    /**
     * Message for summary of results: "results: "
     */
    const PAGER_RESULTS = 'PAGER_RESULTS';

    /**
     * Message for: "pages" eg. 15 pages
     */
    const PAGER_RESULTPAGES = 'PAGER_RESULTPAGES';

    /**
     * Message for: "results per page"
     */
    const PAGER_RESULTSPERPAGE = 'PAGER_RESULTSPERPAGE';

    /**
     * Replacement on the left of the current activ number to higlight.
     */
    const PAGER_HIGHLIGHTLEFT = 'PAGER_HIGHLIGHTLEFT';

    /**
     * Replacement on the right of the current activ number to higlight.
     */
    const PAGER_HIGHLIGHTRIGHT = 'PAGER_HIGHLIGHTRIGHT';
    /**
     * Replacement for " | " when split the output for pages e.g.: 1 | 2 | 3
     */
    const PAGER_SLIDERDELIMITER = 'PAGER_SLIDERDELIMITER';
    /**
     * Replacement for "&raquo;&raquo;&raquo;" (>>>) link text for the last page
     */
    const PAGER_PAGELAST = 'PAGER_PAGELAST';
    /**
     * Replacement for "&laquo;&laquo;&laquo;" (<<<) link text for the first page
     */
    const PAGER_PAGEFIRST = 'PAGER_PAGEFIRST';

    /**
     * Total number of items exists to build the pagination.
     * Note: Not the number of items of data e.g: currently loaded
     * @var integer
     */
    private $_cntitems;

    /**
     * Number of page currently shown (default 0)
     * @var integer
     */
    private $_pagestart = 0;

    /**
     * Variable name for the page start. E.g: "start" or "pagestart" (default)
     * @var string
     */
    private $_pagestartVarname = 'pagestart';

    /**
     * Number of items per page.
     * @var integer
     */
    private $_limit;

    /**
     * Basic link/ location to start the pagination.
     * E.g.:
     * http://mysite/cms/index.php?a=b&limit=25
     * http://mysite/cms/module/controller/action/?a=b&limit=25
     * @var string
     */
    private $_basiclink;

    /**
     * Flag to show/ create pagenumbers html or not. Otherwise only "first,
     * prev, next, last" page links will be generated.
     * @var boolean
     */
    private $_showPageNumbers = true;

    /**
     * Flag to show/ create a summary line after the slider (prev - next page)
     * @var boolean
     */
    private $_showSummary = true;

    /**
     * Generated summary line if enabled.
     * @var string|null
     */
    private $_summary;

    /**
     * Extends the page number creation for hugh lists of data by 2. Default
     * is true. More features to come.
     * @var boolean
     */
    private $_dynamic = true;

    /**
     * Slideing steps in both directions
     * @var integer
     */
    private $_slidersteps = 8;

    /**
     * Css class name for the slider and the summary div container. Default:
     * "pnnavi"
     * @var string
     */
    private $_cssClassName = 'pnnavi';

    /**
     * Complete html code for the pagination based on the setting
     * @var string
     */
    private $_html;

    /**
     * Html code for the first page
     * @var string
     */
    private $_pageFirst;

    /**
     * Html code for the last page
     * @var string
     */
    private $_pageLast;

    /**
     * Html code for the previous page
     * @var type
     */
    private $_pagePrev;

    /**
     * Html code for the next page
     * @var string
     */
    private $_pageNext;

    /**
     * Html code for the silder
     * @var string
     */
    private $_slider;

    /**
     * List of messages to display and to be translated
     * @var array
     */
    private $_messageTemplates = array(
        self::PAGER_PAGEFIRST => '&laquo;&laquo;&laquo;',
        self::PAGER_PAGENEXT => '&raquo;',
        self::PAGER_PAGEPREV => '&laquo;',
        self::PAGER_PAGELAST => '&raquo;&raquo;&raquo;',

        self::PAGER_RESULTS => 'results: ',
        self::PAGER_RESULTPAGES => 'pages: ',
        self::PAGER_RESULTSPERPAGE => 'results per page: ',
        // current/ activ item to hightlight
        self::PAGER_HIGHLIGHTLEFT => ' &gt;',    // or: <span class="nicefeature">
        self::PAGER_HIGHLIGHTRIGHT => '&lt; ',   // or: </span>
        self::PAGER_SLIDERDELIMITER => ' | ',
    );


    /**
     * Initialise the pager object.
     *
     * Example:
     * <code>
     * array(
     *  'cntitems' => 1123,
     *  'pagestart' => 0,
     *  'pagestartVarname' => 'page',
     *  'limit' => 25,
     *  'basiclink' => 'http://site/index.php?a=b&limit=15',
     *  'showPageNumbers' => true,
     *  'showSummary' => true,
     *  'dynamic' => true,
     *  'slidersteps' => 8,
     *  'cssClassName' => 'pnnavi',
     * );
     * </code>
     *
     * @param array $params Parameters to be set on initialisation:
     * - 'cntitems' integer Number of items to generate the sliding mecanism
     * - 'pagestart' integer Number of page currently shown (default 0)
     * - 'pagestartVarname' string Identifier variable name to detect the
     * current page or first page when sliding
     * - 'limit' integer Limit of entrys/items to show
     * - 'basiclink' string Params to be set for need of the application
     * - 'showPageNumbers' boolean True to show page numbers
     * - 'showSummary' boolean If true generate a html summary
     * - 'dynamic' integer Dynamic stepwise for prev<->next navigation
     * - 'cssClassName' string Css class name for the slider and the summary
     * div container
     *
     * @return string Html code for the pagination based on the setting.
     */
    public function __construct( array $params = array() )
    {
        if ( $params ) {
            $defaults = array(
                'cntitems', 'pagestart', 'pagestartVarname', 'limit',
                'basiclink', 'showPageNumbers', 'showSummary', 'dynamic',
                'slidersteps', 'cssClassName'
            );
            while ( list($key, $val) = each($params) ) {
                if ( in_array($key, $defaults) ) {
                    $this->{'_' . $key } = $val;
                } else {
                    $message = 'Invalid parameter "' . $key . '" found';
                    throw new Mumsys_Pager_Exception($message);
                }
            }
        }

        return $this->render();
    }


    /**
     * Returns all pagination parts as array.
     *
     * @return array Retuns a list of of html items to create your own slider
     * design.
     * - [summary] string|null The summary informations
     * - [pageFirst] string Link to the first page
     * - [pageLast] string  Link to the last page
     * - [pagePrev] string Link to the previous page
     * - [pageNext] string Link to the next page
     * - [slider] string Link to pagenumber previous and next to the current one.
     * Depending on slidersteps
     */
    public function getParts()
    {
        $result = array(
            'summary' => $this->_summary,
            'pageFirst' => $this->_pageFirst,
            'pageLast' => $this->_pageLast,
            'pagePrev' => $this->_pagePrev,
            'pageNext' => $this->_pageNext,
            'slider' => $this->_slider,
        );
        return $result;
    }


    /**
     * Returns the complete html code for the navigation based on the settings.
     *
     * @return string Returns the complete html
     */
    public function getHtml()
    {
        return $this->_html;
    }


    /**
     * Returns the summary of the pageination/ navigation.
     *
     * @return string Returns the summary html code
     */
    public function getSummary()
    {
        return $this->_summary;
    }


    /**
     * Returns the list of message templates.
     *
     * @return array List of key/value pairs for the messages.
     */
    public function getMessageTemplates()
    {
        return $this->_messageTemplates;
    }

    /**
     * Renders the pagination.
     *
     * @return string Returns the generated prev-next navigation based on
     * initial setting.
     */
    public function render()
    {
        $cntitems = $this->_cntitems;
        $pagestart = $this->_pagestart;
        $limit = $this->_limit;
        $basiclink = $this->_basiclink;
        $pagestartVarname = $this->_pagestartVarname;
        $showPageNumbers = $this->_showPageNumbers;
        $showSummary = $this->_showSummary;
        $dynamic = $this->_dynamic;

        $steps = $this->_slidersteps;

        $html = '<div class="' . $this->_cssClassName . '">' . _NL;
        $slider = '';

        $cnt = 0;
        $cnt = ceil($cntitems / $limit);
        if ( $showPageNumbers ) {
            //$cnt = ceil($cntitems / $limit);
            $selected = $pagestart / $limit;
        }

        if ( $pagestart <= 0 ) {
            // first page (<<<)
            $this->pageFirst = $this->_messageTemplates[self::PAGER_PAGEFIRST];
            $html .= '[ ';
            $html .= $this->pageFirst;
            $html .= $this->_messageTemplates[self::PAGER_SLIDERDELIMITER];

            // prev page
            $this->_pagePrev = $this->_messageTemplates[self::PAGER_PAGEPREV];
            $html .= $this->_pagePrev;
            $html .= ' | ';
        } else {
            // first page
            $html .= '[ ';
            $this->_pageFirst = '<a href="' . $basiclink . '&amp;'
                . $pagestartVarname . '=0">'
                . $this->_messageTemplates[self::PAGER_PAGEFIRST]
                . '</a>';
            $html .= $this->_pageFirst;
            $html .= $this->_messageTemplates[self::PAGER_SLIDERDELIMITER];

            // prev page
            $ps = $pagestart - $limit;
            if ( $ps < 0 ) {
                $ps = 0;
            }
            $this->_pagePrev = '<a href="' . $basiclink . '&amp;'
                . $pagestartVarname . '=' . $ps . '">' . $this->_messageTemplates[self::PAGER_PAGEPREV] . '</a>';
            $html .= $this->_pagePrev;
            $html .= $this->_messageTemplates[self::PAGER_SLIDERDELIMITER];
        }

        if ( $showPageNumbers ) {
            $x = 1;
            for ( $i = 0; $i < $cnt; $i++ ) {
                if ( $dynamic && $cnt > $steps * 2 ) {
                    if ( $pagestart <= 0 || $limit * $i <= $cntitems ) {
                        if ( $selected == $i ) {
                            $slider .= $this->_messageTemplates[self::PAGER_HIGHLIGHTLEFT] . '<strong>'
                                . $x . '</strong>'
                                . $this->_messageTemplates[self::PAGER_HIGHLIGHTRIGHT]
                                . $this->_messageTemplates[self::PAGER_SLIDERDELIMITER];
                        } else {
                            // zeige 10 ($steps) vor und 10 ($steps) nach dem
                            // aktuellen eintrag,
                            if ( ($selected - $steps <= $i) && ($selected + $steps >= $i) ) {
                                $slider .= sprintf(
                                    '<a href="%1$s&amp;%2$s=%3$s">%4$s</a>',
                                    $basiclink,
                                    $pagestartVarname,
                                    ($limit * $i),
                                    $x
                                );

                                $slider .= $this->_messageTemplates[self::PAGER_SLIDERDELIMITER];
                            }
                        }
                        $x++;
                    }
                } else {
                    if ( $pagestart <= 0 || $limit * $i <= $cntitems ) {
                        // highlight current
                        if ( $pagestart / $limit == $i ) {
                            $slider .= $this->_messageTemplates[self::PAGER_HIGHLIGHTLEFT]
                                . '<strong>' . $x . '</strong>'
                                . $this->_messageTemplates[self::PAGER_HIGHLIGHTRIGHT]
                                . $this->_messageTemplates[self::PAGER_SLIDERDELIMITER];
                        } else {
                            $ps = $limit * $i;
                            if ( $limit * $i > $cntitems ) {
                                $ps = $cntitems - 1;
                            }
                            $slider .= sprintf(
                                '<a href="%1$s&amp;%2$s=%3$s">%4$s</a>',
                                $basiclink,
                                $pagestartVarname,
                                $ps,
                                $x
                            );
                            $slider .= $this->_messageTemplates[self::PAGER_SLIDERDELIMITER];
                        }
                        $x++;
                    }
                }
            }
            $html .= $slider;
            $this->_slider = $slider;
        }


        if ( ($pagestart + $limit) >= $cntitems ) {
            // next page
            $this->_pageNext = '' . $this->_messageTemplates[self::PAGER_PAGENEXT];
            $html .= '';
            $html .= $this->_pageNext;

            // last page (>>>)
            $this->_pageLast = $this->_messageTemplates[self::PAGER_PAGELAST];
            $html .= $this->_messageTemplates[self::PAGER_SLIDERDELIMITER];
            $html .= $this->_pageLast;
            $html .= ' ]';
        } else {
            // next page
            $this->_pageNext = sprintf(
                ' <a href="%1$s&amp;%2$s=%3$s">%4$s</a>%5$s',
                $basiclink,
                $pagestartVarname,
                ($pagestart + $limit),
                $this->_messageTemplates[self::PAGER_PAGENEXT],
                ''
            );
            $html .= $this->_pageNext;

            // last page
            $this->_pageLast = sprintf(
                '<a href="%1$s&amp;%2$s=%3$s">%4$s</a>%5$s',
                $basiclink,
                $pagestartVarname,
                ($cnt * $limit - $limit),
                $this->_messageTemplates[self::PAGER_PAGELAST],
                ''
            );
            $html .= $this->_messageTemplates[self::PAGER_SLIDERDELIMITER];
            $html .= $this->_pageLast;
            $html .= ' ]';
        }
        $html .= '</div>' . _NL;

        if ( $showSummary ) {
            $html .= '' . _NL
                . '<div class="' . $this->_cssClassName . ' summary">' . _NL;
            $this->_summary = sprintf(
                '%1$s <b>%2$s</b>, %3$s<b>%4$s</b>, %5$s<b>%6$s</b>' . _NL,
                $this->_messageTemplates[self::PAGER_RESULTS],
                $cntitems,
                $this->_messageTemplates[self::PAGER_RESULTSPERPAGE],
                $limit,
                $this->_messageTemplates[self::PAGER_RESULTPAGES],
                $cnt
            );
            $html .= $this->_summary;
            $html .= '</div>' . _NL;
        }


        $this->_html = $html;

        return $html;
    }

}
