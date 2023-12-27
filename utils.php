<?php
use Medoo\Medoo;

include_once 'lib/medoo.php';

class Utils{
    protected $database, $current_book_dat = BIB_ALL_BKS;
    function __construct()
    {
        $this->database = new medoo([
            'database_type' => 'mysql',
            'database_name' => 'liturgy_bible',
            'server' => 'localhost',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8'
        ]);
    }

    /**
     *
     * @param $bk -
     *            Book Number
     * @param $ch -
     *            Chapter Number
     * @return string - Formated book and chapter code
     */
    public static function convertBkCh2Code($bk, $ch)
    {
        $code = str_pad($bk, 2, '0', STR_PAD_LEFT) . str_pad($ch, 3, '0', STR_PAD_LEFT);
        return str_replace('00i', 'i', $code);
    }

    /**
     * Function adds reference tags to cross references.
     * Used in footnotes and title
     * 
     * Known issues:
     * - Verse sub divisions dont work: Eg. எபி 4:4அ will be converted to எபி 4:4
     * - References accross books not supported. For example Mat 5 - Mark 3
     *
     * @param string $refs
     *            - Well formated references. As seen in printed bible.
     */
    public function getReferenceTag($refString)
    {
        // No digit is present, probably it is a subtitle
        if (preg_match("/\d/u", $refString) === 0 ) {
            return $refString;//No digit is found. So not a reference.
        }
        
        //Get Tamil Bible Book Abbreviations
        $abbrList = array_column($this->current_book_dat, 'tn_abbr');
        $abbrList = array_filter($abbrList);//Remove blank elements

        // initialize book id - Required as subsequent verse reference may not mention book name. 
        $CurrentBook_osisID = '';
        $returnVal = [];//Store Output

        // Seperate References based on semicolon
        $refStringList = preg_split('/;/', $refString);
        $refStringList = array_map('trim', $refStringList);


        foreach ($refStringList as $refStringFrag) {
            $final_StringFrag = ''; //Stores the formated fragment for the current iteration
            $toTag_StringFrag = ''; //Stores the content that is to be tagged

            if(str_starts_with(  $refStringFrag, 'காண். ' )){//"காண்." should be the only non reference text within the cross reference
                $final_StringFrag .= 'காண். ';
                $refStringFrag = str_replace('காண். ', '', $refStringFrag);
            }

            //Process Book Name
            if(preg_match('/^([1-3 ]*[\p{Tamil}\(\) ]+)\s*(.*)/u', $refStringFrag, $matches) === 1){
                
                $toTag_StringFrag .= $matches[1];
                $abbrKey = array_search(trim($matches[1]), $abbrList);
                if ($abbrKey !== FALSE) {
                    $CurrentBook_osisID = $this->current_book_dat[$abbrKey + 1]['osisID'];
                }
                //Remove Book name from string
                $refStringFrag = str_replace($matches[1], '', $refStringFrag);
            }//if no matches found then retain previous book

            $refStringFrag = preg_replace('/\s+/', '', $refStringFrag);//Remove all spaces

            preg_match_all("/(\d+)([,:-]?)/u", $refStringFrag, $versesMatch, PREG_SET_ORDER);

            $osisRefTemp = '';
            $CurrentChapter = '';
            foreach ($versesMatch as $value) {
                //print_r($value);
                switch ($value[2]) {
                    case ':':
                        $toTag_StringFrag .= $value[0];
                        $CurrentChapter = $value[1];
                        break;

                    case '-':
                        $toTag_StringFrag .= $value[0];
                        $osisRefTemp .= "$CurrentBook_osisID.$CurrentChapter.$value[0]";
                        break;

                    case ',':
                    case '':
                        $toTag_StringFrag .= $value[1];

                        $osisRefTemp .= "$CurrentBook_osisID.$CurrentChapter.$value[1]";

                        if($CurrentChapter == ''){
                            if($this->current_book_dat[$abbrKey + 1]['totalChapters'] == 1){
                                $osisRefTemp = str_replace('..', '.1.', $osisRefTemp);
                            }else{
                                $osisRefTemp = str_replace('..', '.', $osisRefTemp);
                            }
                        }

                        $final_StringFrag .= "<reference osisRef='$osisRefTemp'>$toTag_StringFrag</reference>$value[2] ";

                        $osisRefTemp = $toTag_StringFrag ='';
                        break;

                    default:
                        die("What Symbol is this? $value[2]");
                        break;
                }
            }
            $returnVal [] = trim($final_StringFrag);
        }
        return trim(implode('; ', $returnVal));
    }
}