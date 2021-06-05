<?php
use Medoo\Medoo;

header('Content-Type: application/xml; charset=utf-8');
set_time_limit(0);

include_once 'lib/medoo.php';
include_once 'lib/bibleConfig.php';
include_once 'lib/redletter_osis.php';

new MySQLtoOSIS();

class MySQLtoOSIS
{

    protected $database, $xml, $current_book_dat = BIB_ALL_BKS;

    function __construct($db_ = null)
    {
        $this->database = new medoo([
            'database_type' => 'mysql',
            'database_name' => 'liturgy_bible',
            'server' => 'localhost',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8'
        ]);

        $this->xml = new DOMDocument('1.0', 'UTF-8');
        $this->xml->formatOutput = true;

        $osis = $this->xml->createElement('osis');
        $osis = $this->xml->appendChild($osis);

        $osis->setAttribute("xmlns", "http://www.bibletechnologies.net/2003/OSIS/namespace");
        $osis->setAttribute("xmlns:osis", "http://www.bibletechnologies.net/2003/OSIS/namespace");
        $osis->setAttribute("xsi:schemaLocation", "http://www.bibletechnologies.net/2003/OSIS/namespace http://www.bibletechnologies.net/osisCore.2.1.1.xsd");
        $osis->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");

        $osisText = $this->xml->createElement('osisText');
        $osisText = $osis->appendChild($osisText);
        $osisText->setAttribute("osisRefWork", "bible");
        $osisText->setAttribute("osisIDWork", "TAMCT-CE");
        $osisText->setAttribute("xml:lang", "ta");
        $osisText->setAttribute("canonical", "true");

        $header = $this->xml->createElement('header');
        $header = $osisText->appendChild($header);

        $work = $this->xml->createElement('work');
        $work = $header->appendChild($work);
        $work->setAttribute("osisWork", "TAMCT-CE");

        $ele = $this->xml->createElement('title', 'திருவிவிலியம் (பொது மொழிபெயர்ப்பு)');
        $ele = $work->appendChild($ele);
        $ele = $this->xml->createElement('type', 'Bible');
        $ele = $work->appendChild($ele);
        $ele->setAttribute("type", "OSIS");

        for ($i = 1; $i <= 75; $i ++) {

            if ($i !== 46) {
                $eleBk = $this->getBook($i);
                $osisText->appendChild($eleBk);
            } else {
                // Additions to Daniel
                $osisID_ = [
                    'PrAzar',
                    'Sus',
                    'Bel'
                ];

                foreach ($osisID_ as $k => $osisID_Bk) {
                    $temp = $this->xml->createElement('temp');

                    $eleCh = $this->getChapter($i, $k + 1);
                    $temp->appendChild($eleCh);
                    $osis->appendChild($temp);

                    $chapText = str_replace('AddDan.' . ($k + 1), $osisID_Bk . '.1', $eleCh->C14N());
                    $chapText = str_replace('xmlns="http://www.bibletechnologies.net/2003/OSIS/namespace"', '', $chapText);

                    $tempXML = new DOMDocument('1.0', 'UTF-8');
                    $tempXML->loadXML($chapText);
                    $chap = $tempXML->getElementsByTagName("chapter")->item(0);
                    $chap->setAttribute("chapterTitle", "அதிகாரம் 1");

                    $title = $tempXML->getElementsByTagName("title")->item(0);
                    $title->parentNode->removeChild($title);

                    $div = $this->xml->createElement('div');
                    $div->setAttribute("type", "book");
                    $div->setAttribute("canonical", "true");
                    $div->setAttribute("osisID", $osisID_Bk);
                    $title = $this->xml->createElement('title', 'தானியேல் (இணைப்புகள்): ' . $title->textContent);
                    $title->setAttribute("type", "main");
                    $div->appendChild($title);

                    $div->appendChild($this->xml->importNode($chap, true));
                    $osisText->appendChild($div);
                    $osis->removeChild($temp);
                }
            }
            // break; // <<---Book Break
        }
        echo $this->xml->saveXML();
    }

    function getBook($bkID)
    {
        $div = $this->xml->createElement('div');
        $div->setAttribute("type", "book");
        $div->setAttribute("canonical", "true");
        $div->setAttribute("osisID", $this->current_book_dat[$bkID]['osisID']);
        $title = $this->xml->createElement('title', $this->current_book_dat[$bkID]['tn_name']);
        $title->setAttribute("type", "main");
        $div->appendChild($title);

        // Get book introduction
        $introTxt = $this->getBookIntro($bkID);
        $div->appendChild($this->xml->importNode($introTxt, true));

        $startChapter = intval($bkID !== 44);
        //$startChapter = 6;
        $endChapter = $this->current_book_dat[$bkID]['totalChapters'];

        for ($i = $startChapter; $i <= $endChapter; $i ++) {
            $eleCh = $this->getChapter($bkID, $i);
            $div->appendChild($this->xml->importNode($eleCh, true));
            //break; // <<---Chapter Break
        }
        return $div;
    }

    function getBookIntro($bkID)
    {
        $intro = $this->database->select(BLIB_INDEX, "intro", [
            "bn" => $bkID
        ]);

        $introTxt = (isset($intro[0])) ? $intro[0] : '';

        $div = $this->xml->createElement('div');

        $title = $this->xml->createElement('title', "முன்னுரை");
        $div->appendChild($title);

        $template = $this->xml->createDocumentFragment();
        $template->appendXML($introTxt);
        $div->appendChild($this->xml->importNode($template, true));

        return $div;
    }

    function getChapter($bkID, $chID)
    {
        $chapter = $this->xml->createElement('chapter');
        $chapter->setAttribute("osisID", $this->current_book_dat[$bkID]['osisID'] . '.' . $chID);
        $chapter->setAttribute("chapterTitle", "அதிகாரம் $chID");

        $current_para_ele = $this->xml->createElement('p');
        $current_para_txt = '';

        $vd = $this->convertBkCh2Code($bkID, $chID);

        // Process and save titles
        $titles = $this->database->select(BLIB_VIEW, [
            'verse_id' => [
                'txt'
            ]
        ], [
            'AND' => [
                'verse_id[~]' => $vd . '%',
                'type' => 'T'
            ],
            'ORDER' => [
                "verse_id" => "ASC",
                "type" => "DESC"
            ]
        ]);
        foreach ($titles as $key => $title) {
            $titles[$key] = $this->formatTitle($title['txt']);
        }
        // if there is a title before verse 1
        if (isset($titles[$vd . '000'])) {
            $current_para_txt = $titles[$vd . '000'];
        }

        // Process verses
        $verses = $this->database->select(BLIB_VIEW, [
            'verse_id' => [
                'txt'
            ]
        ], [
            'AND' => [
                'verse_id[~]' => $vd . '%',
                'type' => 'V'
            ],
            'ORDER' => [
                "verse_id" => "ASC",
                "type" => "DESC"
            ]
        ]);

        // RED LETTER Instructions
        $inst = $this->database->select(BLIB_REDLTR, '*', [
            'id_from[~]' => $vd . '%'
        ]);
        $redLetter = new RedLetter($inst);

        foreach ($verses as $key => $verse) {
            $ver_txt = $verse['txt'];

            if ($ver_txt === 'Same as above') {
                continue;
            }

            /* RED LETTER */
            $ver_txt = $redLetter->colorRedLetter($ver_txt, $key);

            $this->SwapConsecutiveCharacters(BLIB_RED_LTR_START, BLIB_POEM1_START, $ver_txt);
            $this->SwapConsecutiveCharacters(BLIB_RED_LTR_START, BLIB_POEM2_START, $ver_txt);
            $this->SwapConsecutiveCharacters(BLIB_POEM1_END, BLIB_RED_LTR_END, $ver_txt);
            $this->SwapConsecutiveCharacters(BLIB_POEM2_END, BLIB_RED_LTR_END, $ver_txt);

            /* VERSE TAG */
            $ver_txt = $this->setVerseTag($ver_txt, $key);

            /* POEM FORMATING */
            $ver_txt = $this->formatPoemVerse($ver_txt, BLIB_POEM1_START, BLIB_POEM1_END, false);
            $ver_txt = $this->formatPoemVerse($ver_txt, BLIB_POEM2_START, BLIB_POEM2_END, true);
            if (substr_count($ver_txt, BLIB_INDENT_START) == substr_count($ver_txt, BLIB_INDENT_END) && substr_count($ver_txt, BLIB_INDENT_START) != 0) {
                $ver_txt = $this->formatPoemVerse($ver_txt, BLIB_INDENT_START, BLIB_INDENT_END, true);
            }

            if ($key == 67001005) { // single exception for Heb 1:1 as there are two quotes with break points in it
                $ver_txt = str_replace(BLIB_BREAK_PT, "</l><l level='1'>", $ver_txt);
            }

            if (isset($titles[$key])) {
                // Check whether there is a title in the middle of the verse
                $paraT = explode(BLIB_TITLE_PT, $ver_txt . BLIB_TITLE_PT, 2);
                $paraT[1] = rtrim($paraT[1], BLIB_TITLE_PT);

                $current_para_txt .= $paraT[0];
                $current_para_txt = $this->finalizeParaTxt($current_para_txt, $key);

                // Append current text and close para
                $frag = $this->xml->createDocumentFragment();
                $frag->appendXML($current_para_txt);
                $current_para_ele->appendChild($frag);
                $chapter->appendChild($current_para_ele);
                $current_para_ele = $this->xml->createElement('p');

                // Add title
                $frag = $this->xml->createDocumentFragment();
                $frag->appendXML($titles[$key]);
                $current_para_ele->appendChild($frag);

                //
                $current_para_txt = '';
                $ver_txt = trim($paraT[1]);
            }

            if (strpos($ver_txt, BLIB_PARA_BK) !== false) {
                // New Para

                $paraT = explode(BLIB_PARA_BK, $ver_txt);

                $cnt = 0;
                do {
                    $current_para_txt .= $paraT[$cnt];
                    $current_para_txt = $this->finalizeParaTxt($current_para_txt, $key);
                    $frag = $this->xml->createDocumentFragment();
                    $frag->appendXML($current_para_txt);
                    $current_para_ele->appendChild($frag);
                    $chapter->appendChild($current_para_ele);

                    $current_para_ele = $this->xml->createElement('p');
                    $current_para_txt = '';

                    $cnt ++;
                    if (! isset($paraT[$cnt + 1])) {
                        break;
                    }
                } while (true);
                $ver_txt = $paraT[$cnt];
            }

            $current_para_txt .= $ver_txt;
        }

        if (! empty($current_para_txt)) {

            $current_para_txt = $this->finalizeParaTxt($current_para_txt, $key);
            $frag = $this->xml->createDocumentFragment();
            $frag->appendXML($current_para_txt);
            $current_para_ele->appendChild($frag);
            $chapter->appendChild($current_para_ele);
        }

        return $chapter;
    }

    function finalizeParaTxt($verseTxt, $key)
    {
        // TODO remove unsupported formating tags.
        // All these are defined in bibleConfig.php. Should remove them as soon as support for them is given
        $formatingTags = array(
            '⁽',
            '⁾',
            '₍',
            '₎',
            '␢',
            '⦃',
            '⦄',
            '⦅',
            '⦆',
            '§'
        );
        $formatingTags = [
            BLIB_POEM1_START,
            BLIB_POEM1_END,
            BLIB_POEM2_START,
            BLIB_POEM2_END,
            BLIB_POEM_BREAK,
            BLIB_INDENT_START,
            BLIB_INDENT_END,
            BLIB_BREAK_PT
        ];

        $verseTxt = str_replace($formatingTags, " ", $verseTxt);

        $verseTxt = str_replace(BLIB_RED_LTR_END . BLIB_RED_LTR_START, "", $verseTxt);
        $verseTxt = str_replace("</lg><lg>", "", $verseTxt);

        $verseTxt = str_replace(BLIB_RED_LTR_START, "<q who='Jesus'  marker='' sID='red-$key' />", $verseTxt);
        $verseTxt = str_replace(BLIB_RED_LTR_END, "<q eID='red-$key'/>", $verseTxt);

        return $verseTxt;
    }

    /**
     * Sets verse start and end tags at appropriate locations.
     * It also adds a note to text if there is a continous verses.
     *
     * @param string $verseTxt
     * @param string $verseID
     * @return string
     */
    function setVerseTag($verseTxt, $verseID)
    {
        $verse_num = $this->convertCode2BkCh($verseID);
        $book_name = $this->current_book_dat[$verse_num[0]]['osisID'];
        $chapter_num = $verse_num[1];
        $num_range = $verse_num = $verse_num[2];

        $osisID = "$book_name.$chapter_num.$verse_num";
        $s_e_ID = "$book_name.$chapter_num.$verse_num";

        $noteTxt = '';

        if (0 === strpos($verseTxt, BLIB_VERSE_NUMBER_START)) { // Continuous Verses
                                                                // TODO Support for running verses or continuous verses.
                                                                // Eg. 1-2 This is not currently implemented because of the lack of implementaion in SWORD Engine.
                                                                // The engine duplicates the same verse again and again for the full range.
                                                                // Will Add it when support is rendered by SWORD engine.
                                                                // Currently a footnote alone is added.

            $num_range = explode(BLIB_VERSE_NUMBER_END, $verseTxt); // Extract verse numbers
            $num_range[0] = ltrim($num_range[0], BLIB_VERSE_NUMBER_START); // Remove versenumber begining tag

            // add a note saying this is a merged verse. Will be removed when SWORD Engine supports verse range
            $noteTxt = "<note osisRef='$osisID' osisID='$osisID' n='வ $num_range[0]'>வசனங்கள் $num_range[0]</note>";
            $verseTxt = $num_range[1];

            $num_range = $num_range[0];
        }

        $verseTxt = BLIB_VRS_START . $verseTxt . BLIB_VRS_END;

        $this->SwapConsecutiveCharacters(BLIB_PARA_BK, BLIB_VRS_END, $verseTxt);

        $this->SwapConsecutiveCharacters(BLIB_RED_LTR_START, BLIB_VRS_START, $verseTxt);
        $this->SwapConsecutiveCharacters(BLIB_VRS_START, BLIB_POEM1_START, $verseTxt);
        $this->SwapConsecutiveCharacters(BLIB_VRS_START, BLIB_POEM2_START, $verseTxt);

        $this->SwapConsecutiveCharacters(BLIB_VRS_END, BLIB_RED_LTR_END, $verseTxt);
        $this->SwapConsecutiveCharacters(BLIB_POEM1_END, BLIB_VRS_END, $verseTxt);
        $this->SwapConsecutiveCharacters(BLIB_POEM2_END, BLIB_VRS_END, $verseTxt);

        $verse_start = "<verse osisID='$osisID' sID='$s_e_ID' n='$num_range' />";
        $verse_end = "<verse eID='$s_e_ID' n='$num_range' />";

        $verseTxt = str_replace(BLIB_VRS_START, $verse_start, $verseTxt);
        $verseTxt = str_replace(BLIB_VRS_END, $verse_end, $verseTxt);

        return $verseTxt;
    }

    /**
     * Swaps the positions of two consecutive Characters ab -> ba within a string.
     *
     * @param string $first
     * @param string $second
     * @param string $chap
     *            - The output is stored in this variable
     * @return string The parameter $chap with modified string
     */
    private function SwapConsecutiveCharacters($first, $second, &$chap)
    {
        $chap = str_replace($first . $second, $second . $first, $chap);
    }

    /**
     * Formats the string given into title tags.
     *
     * @todo Subtitles are not yet supported.
     *      
     * @param string $titleTxt
     * @return string
     */
    function formatTitle($titleTxt)
    {
        $hdr = preg_split("/[" . BLIB_HEADER_PT . BLIB_BREAK_PT . "]/um", $titleTxt);

        $returnTitle = '';
        foreach ($hdr as $title) {
            $match = [];
            if (preg_match('/\(([^\)]+)\)/u', $title, $match)) {
                // if there is a bracket, it is probably bible reference.
                $title = '(' . $this->getReferenceTag($match[1]) . ')';
                $returnTitle .= "<title type='parallel'>$title</title>";
            } else {
                $returnTitle .= "<title type='sub'>$title</title>";
            }
        }
        return $returnTitle;
    }

    /**
     * Function adds reference tags to cross references.
     * Use in footnotes and title
     *
     * @param string $refs
     *            - Well formated references. As seen in printed bible.
     */
    function getReferenceTag($refString)
    {
        if (preg_match("/\d/u", $refString) === 0) {
            // No digit is present, probably it is a subtitle
            return $refString;
        }

        // initialize
        $returnVal = [];
        $abbrList = array_column($this->current_book_dat, 'tn_abbr');
        $CurrentBook_osisID = '';

        // Seperate References
        $refStringList = preg_split('/\s*;\s*/', $refString);
        foreach ($refStringList as $refStringFrag) {
            $abbrKey = 0;
            // Get the Book; First part of the string
            $matches = [];
            if (1 === preg_match('/^([1-3 ]*[\p{Tamil}]+)\s*/u', $refStringFrag, $matches)) {
                $abbrKey = array_search(trim($matches[0]), $abbrList);

                if (FALSE === $abbrKey) { // Not a known book
                    $CurrentBook_osisID = '';
                    die('Not a known book' . $refString);
                    continue;
                }
                // TODO Will not work for danial additions
                $CurrentBook_osisID = $this->current_book_dat[$abbrKey + 1]['osisID'];
            } elseif (empty($CurrentBook_osisID)) {
                // if no previous book available, then Probably not a bible verse
                // so, insert the text without formating
                array_push($returnVal, $refStringFrag);
                continue;
            } // if previous book available, then it belongs to previous reference; eg. 1 கொரி 6:16; 7:10.

            // remove book name
            $frag_withoutBook = preg_replace('/^([1-3 ]*[\p{Tamil}]+)\s*/u', '', $refStringFrag);

            // initialize
            $CurrentChapter_no = 0;
            $CurrentVerse_no = [];
            $refFragment = '';

            // explode to get chapter number
            $frag_withoutBook = preg_split('/\s*:\s*/', $frag_withoutBook);

            if (sizeof($frag_withoutBook) === 1 && $this->current_book_dat[$abbrKey + 1]['totalChapters'] > 1) {
                // no chapter number specified and there is more than one chapters in the book
                // then references are for chapters not verses
                $CurrentChapter_no = [];
                $CurrentChapters = preg_split('/\s*,\s*/', $frag_withoutBook[0]);
                foreach ($CurrentChapters as $CurrentChapters_) {
                    array_push($CurrentChapter_no, preg_split('/\s*\-\s*/', $CurrentChapters_));
                }

                $refStringFrag_ = preg_split('/\s*,\s*/', $refStringFrag);

                foreach ($CurrentChapter_no as $k => $CurrentVrs) {
                    $refFragment_ = "$CurrentBook_osisID.$CurrentVrs[0]";
                    $refFragment .= ", <reference type='parallel' osisRef='$refFragment_'>$refStringFrag_[$k]</reference>";
                }
            } else {
                if (sizeof($frag_withoutBook) === 1) {
                    // if only one chapter in the book. eg. பில
                    $frag_withoutBook[1] = $frag_withoutBook[0];
                    $frag_withoutBook[0] = 1;
                    die('one chapter in the book. eg. பில - Did it work???');
                }

                $CurrentVerse_no = [];
                $CurrentChapter_no = $frag_withoutBook[0];

                $CurrentVerses = preg_split('/\s*,\s*/', $frag_withoutBook[1]);
                foreach ($CurrentVerses as $CurrentVerses_) {
                    array_push($CurrentVerse_no, preg_split('/\s*\-\s*/', $CurrentVerses_));
                }
                $refStringFrag_ = preg_split('/\s*,\s*/', $refStringFrag);

                foreach ($CurrentVerse_no as $k => $CurrentVrs) {
                    foreach ($CurrentVrs as &$CurrentVr) {
                        // removing sub references 9:37-43அ
                        $CurrentVr = preg_replace('/[\p{Tamil}]+/u', '', $CurrentVr);
                    }

                    $refFragment_ = "$CurrentBook_osisID.$CurrentChapter_no.$CurrentVrs[0]";
                    if (isset($CurrentVrs[1])) {
                        $refFragment_ .= "-$CurrentBook_osisID.$CurrentChapter_no.$CurrentVrs[1]";
                    }
                    $refFragment .= ", <reference type='parallel' osisRef='$refFragment_'>$refStringFrag_[$k]</reference>";
                }
            }
            $refFragment = ltrim($refFragment, ',');
            array_push($returnVal, $refFragment);
        }
        return trim(implode('; ', $returnVal));
    }

    /**
     *
     * @param $bk -
     *            Book Number
     * @param $ch -
     *            Chapter Number
     * @return string - Formated book and chapter code
     */
    function convertBkCh2Code($bk, $ch)
    {
        $code = str_pad($bk, 2, '0', STR_PAD_LEFT) . str_pad($ch, 3, '0', STR_PAD_LEFT);
        return str_replace('00i', 'i', $code);
    }

    /**
     *
     * @param $vrs -
     *            Properly formated/padded verse code;
     * @return string - Array with book, chapter, verse number. <br/> Note: Chapter will be 'i' if it is introduction of a book.
     */
    function convertCode2BkCh($vrs)
    {
        if (empty($vrs))
            return $vrs;

        $vrs = strtolower($vrs);
        $rt = [];

        if (substr($vrs, - 1) == 'i') {
            $vrs = rtrim($vrs, "i");
            $rt[0] = intval($vrs);
            $rt[1] = 'i';
        } elseif (strlen($vrs) <= 5) {
            $vrs = str_pad($vrs, 5, '0', STR_PAD_LEFT); // Minimum 5 chars should be available
            $rt = [
                0 => intval(substr($vrs, 0, 2)),
                1 => intval(substr($vrs, 2, 3))
            ];
        } elseif (strlen($vrs) == 8) {
            $vrs = str_pad($vrs, 8, '0', STR_PAD_LEFT); // Minimum 5 chars should be available
            $rt = [
                0 => intval(substr($vrs, 0, 2)),
                1 => intval(substr($vrs, 2, 3)),
                2 => intval(substr($vrs, 5, 3))
            ];
        }
        return $rt;
    }

    /**
     * Replace only the first occurence of the given string
     *
     * @param string $haystack
     *            - The string being searched and replaced on
     * @param string $needle
     *            - The value being searched for, otherwise known as the needle.
     * @param string $replace
     *            - The replacement value that replaces found search value.
     * @return string
     */
    function replaceFirstOccurence($haystack, $needle, $replace)
    {
        $pos = strpos($haystack, $needle);
        if ($pos !== false) {
            $haystack = substr_replace($haystack, $replace, $pos, strlen($needle));
        }
        return $haystack;
    }

    /**
     * Adds `lg` and `l` tags
     *
     * @param string $vrsTxt
     *            - Input string
     * @param string $startTag
     *            - Poem start tag (BLIB_POEM1_START or BLIB_POEM2_START)
     * @param string $endTag
     *            - Poem end tag (BLIB_POEM1_END or BLIB_POEM2_END)
     * @param boolean $indentLevel
     *            - `false` if First line out and second line in. else `true`
     * @return string
     */
    function formatPoemVerse($vrsTxt, $startTag, $endTag, $indentLevel)
    {
        $count = 0;

        $vrsTxt = str_replace($startTag, "<lg><l level='" . (int) $indentLevel . "'>", $vrsTxt, $count);
        $vrsTxt = str_replace($endTag, "</l></lg>", $vrsTxt);

        if ($count > 0) {
            while (strpos($vrsTxt, BLIB_POEM_BREAK) !== false) {
                // two lines in same level indent
                if (strpos($vrsTxt, BLIB_POEM_BREAK) > strpos($vrsTxt, BLIB_BREAK_PT))
                    $vrsTxt = $this->replaceFirstOccurence($vrsTxt, BLIB_BREAK_PT, "</l><l level='" . (int) $indentLevel . "'>");

                $indentLevel = ! $indentLevel;
                $vrsTxt = $this->replaceFirstOccurence($vrsTxt, BLIB_POEM_BREAK, "</l><l level='" . (int) $indentLevel . "'>");
            }

            // For last lines in the same level (there can be more than two lines, so while loop)
            while (strpos($vrsTxt, BLIB_BREAK_PT) !== false && strpos($vrsTxt, '</lg>') > strpos($vrsTxt, BLIB_BREAK_PT)) {
                $vrsTxt = $this->replaceFirstOccurence($vrsTxt, BLIB_BREAK_PT, "</l><l level='" . (int) $indentLevel . "'>");
            }
        }
        return $vrsTxt;
    }
}