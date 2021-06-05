<?php

// Names of the tables where tables are retrived
const BLIB_INDEX = "t_bookkey";
const BLIB_CROSSREF = "t_crossref";
const BLIB_FOOTNOTE = "t_footnotes";
const BLIB_REDLTR = "t_redletter";
const BLIB_HDR = "t_verseheaders";
const BLIB_VRS = "t_verses";
const BLIB_VIEW = "mybibleview";

// Tags in Database
const BLIB_BREAK_PT = '§';
const BLIB_PARA_BK = '⒫';
const BLIB_TITLE_PT = '⒯';
const BLIB_HEADER_PT = '⒣';

//
const BLIB_VERSE_NUMBER_START = '❮';
const BLIB_VERSE_NUMBER_END = '❯';
const BLIB_POEM1_START = '⁽';
const BLIB_POEM1_END = '⁾';
const BLIB_POEM2_START = '₍';
const BLIB_POEM2_END = '₎';
const BLIB_POEM_BREAK = '␢';

const BLIB_RED_LTR_START = '⦓';
const BLIB_RED_LTR_END = '⦔';

const BLIB_INDENT_START = '⦃';
const BLIB_INDENT_END = '⦄';

const BLIB_VRS_START = '❰';
const BLIB_VRS_END = '❱';

// OSIS Bible Constants
const BIB_ALL_BKS = [
    1 => [
        'totalChapters' => '50',
        'osisID' => 'Gen',
        'tn_abbr' => 'தொநூ',
        'tn_name' => 'தொடக்க நூல்'
    ],
    2 => [
        'totalChapters' => '40',
        'osisID' => 'Exod',
        'tn_abbr' => 'விப',
        'tn_name' => 'விடுதலைப் பயணம்'
    ],
    3 => [
        'totalChapters' => '27',
        'osisID' => 'Lev',
        'tn_abbr' => 'லேவி',
        'tn_name' => 'லேவியர்'
    ],
    4 => [
        'totalChapters' => '36',
        'osisID' => 'Num',
        'tn_abbr' => 'எண்',
        'tn_name' => 'எண்ணிக்கை'
    ],
    5 => [
        'totalChapters' => '34',
        'osisID' => 'Deut',
        'tn_abbr' => 'இச',
        'tn_name' => 'இணைச் சட்டம்'
    ],
    6 => [
        'totalChapters' => '24',
        'osisID' => 'Josh',
        'tn_abbr' => 'யோசு',
        'tn_name' => 'யோசுவா'
    ],
    7 => [
        'totalChapters' => '21',
        'osisID' => 'Judg',
        'tn_abbr' => 'நீத',
        'tn_name' => 'நீதித் தலைவர்கள்'
    ],
    8 => [
        'totalChapters' => '4',
        'osisID' => 'Ruth',
        'tn_abbr' => 'ரூத்',
        'tn_name' => 'ரூத்து'
    ],
    9 => [
        'totalChapters' => '31',
        'osisID' => '1Sam',
        'tn_abbr' => '1 சாமு',
        'tn_name' => '1 சாமுவேல்'
    ],
    10 => [
        'totalChapters' => '24',
        'osisID' => '2Sam',
        'tn_abbr' => '2 சாமு',
        'tn_name' => '2 சாமுவேல்'
    ],
    11 => [
        'totalChapters' => '22',
        'osisID' => '1Kgs',
        'tn_abbr' => '1 அர',
        'tn_name' => '1 அரசர்கள்'
    ],
    12 => [
        'totalChapters' => '25',
        'osisID' => '2Kgs',
        'tn_abbr' => '2 அர',
        'tn_name' => '2 அரசர்கள்'
    ],
    13 => [
        'totalChapters' => '29',
        'osisID' => '1Chr',
        'tn_abbr' => '1 குறி',
        'tn_name' => '1 குறிப்பேடு'
    ],
    14 => [
        'totalChapters' => '36',
        'osisID' => '2Chr',
        'tn_abbr' => '2 குறி',
        'tn_name' => '2 குறிப்பேடு'
    ],
    15 => [
        'totalChapters' => '10',
        'osisID' => 'Ezra',
        'tn_abbr' => 'எஸ்ரா',
        'tn_name' => 'எஸ்ரா'
    ],
    16 => [
        'totalChapters' => '13',
        'osisID' => 'Neh',
        'tn_abbr' => 'நெகே',
        'tn_name' => 'நெகேமியா'
    ],
    17 => [
        'totalChapters' => '10',
        'osisID' => 'Esth',
        'tn_abbr' => 'எஸ்',
        'tn_name' => 'எஸ்தர்'
    ],
    18 => [
        'totalChapters' => '42',
        'osisID' => 'Job',
        'tn_abbr' => 'யோபு',
        'tn_name' => 'யோபு'
    ],
    19 => [
        'totalChapters' => '150',
        'osisID' => 'Ps',
        'tn_abbr' => 'திபா',
        'tn_name' => 'திருப்பாடல்கள்'
    ],
    20 => [
        'totalChapters' => '31',
        'osisID' => 'Prov',
        'tn_abbr' => 'நீமொ',
        'tn_name' => 'நீதிமொழிகள்'
    ],
    21 => [
        'totalChapters' => '12',
        'osisID' => 'Eccl',
        'tn_abbr' => 'சஉ',
        'tn_name' => 'சபை உரையாளர்'
    ],
    22 => [
        'totalChapters' => '8',
        'osisID' => 'Song',
        'tn_abbr' => 'இபா',
        'tn_name' => 'இனிமைமிகு பாடல்'
    ],
    23 => [
        'totalChapters' => '66',
        'osisID' => 'Isa',
        'tn_abbr' => 'எசா',
        'tn_name' => 'எசாயா'
    ],
    24 => [
        'totalChapters' => '52',
        'osisID' => 'Jer',
        'tn_abbr' => 'எரே',
        'tn_name' => 'எரேமியா'
    ],
    25 => [
        'totalChapters' => '5',
        'osisID' => 'Lam',
        'tn_abbr' => 'புல',
        'tn_name' => 'புலம்பல்'
    ],
    26 => [
        'totalChapters' => '48',
        'osisID' => 'Ezek',
        'tn_abbr' => 'எசே',
        'tn_name' => 'எசேக்கியேல்'
    ],
    27 => [
        'totalChapters' => '12',
        'osisID' => 'Dan',
        'tn_abbr' => 'தானி',
        'tn_name' => 'தானியேல்'
    ],
    28 => [
        'totalChapters' => '14',
        'osisID' => 'Hos',
        'tn_abbr' => 'ஓசே',
        'tn_name' => 'ஒசேயா'
    ],
    29 => [
        'totalChapters' => '3',
        'osisID' => 'Joel',
        'tn_abbr' => 'யோவே',
        'tn_name' => 'யோவேல்'
    ],
    30 => [
        'totalChapters' => '9',
        'osisID' => 'Amos',
        'tn_abbr' => 'ஆமோ',
        'tn_name' => 'ஆமோஸ்'
    ],
    31 => [
        'totalChapters' => '1',
        'osisID' => 'Obad',
        'tn_abbr' => 'ஒப',
        'tn_name' => 'ஒபதியா'
    ],
    32 => [
        'totalChapters' => '4',
        'osisID' => 'Jonah',
        'tn_abbr' => 'யோனா',
        'tn_name' => 'யோனா'
    ],
    33 => [
        'totalChapters' => '7',
        'osisID' => 'Mic',
        'tn_abbr' => 'மீக்',
        'tn_name' => 'மீக்கா'
    ],
    34 => [
        'totalChapters' => '3',
        'osisID' => 'Nah',
        'tn_abbr' => 'நாகூ',
        'tn_name' => 'நாகூம்'
    ],
    35 => [
        'totalChapters' => '3',
        'osisID' => 'Hab',
        'tn_abbr' => 'அப',
        'tn_name' => 'அபக்கூக்கு'
    ],
    36 => [
        'totalChapters' => '3',
        'osisID' => 'Zeph',
        'tn_abbr' => 'செப்',
        'tn_name' => 'செப்பனியா'
    ],
    37 => [
        'totalChapters' => '2',
        'osisID' => 'Hag',
        'tn_abbr' => 'ஆகா',
        'tn_name' => 'ஆகாய்'
    ],
    38 => [
        'totalChapters' => '14',
        'osisID' => 'Zech',
        'tn_abbr' => 'செக்',
        'tn_name' => 'செக்கரியா'
    ],
    39 => [
        'totalChapters' => '4',
        'osisID' => 'Mal',
        'tn_abbr' => 'மலா',
        'tn_name' => 'மலாக்கி'
    ],
    40 => [
        'totalChapters' => '14',
        'osisID' => 'Tob',
        'tn_abbr' => 'தோபி',
        'tn_name' => 'தோபித்து'
    ],
    41 => [
        'totalChapters' => '16',
        'osisID' => 'Jdt',
        'tn_abbr' => 'யூதி',
        'tn_name' => 'யூதித்து'
    ],
    42 => [
        'totalChapters' => '10',
        'osisID' => 'EsthGr', // AddEsth
        'tn_abbr' => 'எஸ் [கி]',
        'tn_name' => 'எஸ்தர் [கி]'
    ],
    43 => [
        'totalChapters' => '19',
        'osisID' => 'Wis',
        'tn_abbr' => 'சாஞா',
        'tn_name' => 'சாலமோனின் ஞானம்'
    ],
    44 => [
        'totalChapters' => 51,
        'StartChapter' => 0,
        'osisID' => 'Sir',
        'tn_abbr' => 'சீஞா',
        'tn_name' => 'சீராக்'
    ],
    45 => [
        'totalChapters' => '6',
        'osisID' => 'Bar',
        'tn_abbr' => 'பாரூ',
        'tn_name' => 'பாரூக்கு'
    ],
    46 => [
        'totalChapters' => '3',
        'osisID' => 'AddDan',
        'tn_abbr' => 'தானி [இ]',
        'tn_name' => 'தானியேல் [இ]'
    ],
    47 => [
        'totalChapters' => '16',
        'osisID' => '1Macc',
        'tn_abbr' => '1 மக்',
        'tn_name' => '1 மக்கபேயர்'
    ],
    48 => [
        'totalChapters' => '15',
        'osisID' => '2Macc',
        'tn_abbr' => '2 மக்',
        'tn_name' => '2 மக்கபேயர்'
    ],
    49 => [
        'totalChapters' => '28',
        'osisID' => 'Matt',
        'tn_abbr' => 'மத்',
        'tn_name' => 'மத்தேயு'
    ],
    50 => [
        'totalChapters' => '16',
        'osisID' => 'Mark',
        'tn_abbr' => 'மாற்',
        'tn_name' => 'மாற்கு'
    ],
    51 => [
        'totalChapters' => '24',
        'osisID' => 'Luke',
        'tn_abbr' => 'லூக்',
        'tn_name' => 'லூக்கா'
    ],
    52 => [
        'totalChapters' => '21',
        'osisID' => 'John',
        'tn_abbr' => 'யோவா',
        'tn_name' => 'யோவான்'
    ],
    53 => [
        'totalChapters' => '28',
        'osisID' => 'Acts',
        'tn_abbr' => 'திப',
        'tn_name' => 'திருத்தூதர் பணிகள்'
    ],
    54 => [
        'totalChapters' => '16',
        'osisID' => 'Rom',
        'tn_abbr' => 'உரோ',
        'tn_name' => 'உரோமையர்'
    ],
    55 => [
        'totalChapters' => '16',
        'osisID' => '1Cor',
        'tn_abbr' => '1 கொரி',
        'tn_name' => '1 கொரிந்தியர்'
    ],
    56 => [
        'totalChapters' => '13',
        'osisID' => '2Cor',
        'tn_abbr' => '2 கொரி',
        'tn_name' => '2 கொரிந்தியர்'
    ],
    57 => [
        'totalChapters' => '6',
        'osisID' => 'Gal',
        'tn_abbr' => 'கலா',
        'tn_name' => 'கலாத்தியர்'
    ],
    58 => [
        'totalChapters' => '6',
        'osisID' => 'Eph',
        'tn_abbr' => 'எபே',
        'tn_name' => 'எபேசியர்'
    ],
    59 => [
        'totalChapters' => '4',
        'osisID' => 'Phil',
        'tn_abbr' => 'பிலி',
        'tn_name' => 'பிலிப்பியர்'
    ],
    60 => [
        'totalChapters' => '4',
        'osisID' => 'Col',
        'tn_abbr' => 'கொலோ',
        'tn_name' => 'கொலோசையர்'
    ],
    61 => [
        'totalChapters' => '5',
        'osisID' => '1Thess',
        'tn_abbr' => '1 தெச',
        'tn_name' => '1 தெசலோனிக்கர்'
    ],
    62 => [
        'totalChapters' => '3',
        'osisID' => '2Thess',
        'tn_abbr' => '2 தெச',
        'tn_name' => '2 தெசலோனிக்கர்'
    ],
    63 => [
        'totalChapters' => '6',
        'osisID' => '1Tim',
        'tn_abbr' => '1 திமொ',
        'tn_name' => '1 திமொத்தேயு'
    ],
    64 => [
        'totalChapters' => '4',
        'osisID' => '2Tim',
        'tn_abbr' => '2 திமொ',
        'tn_name' => '2 திமொத்தேயு'
    ],
    65 => [
        'totalChapters' => '3',
        'osisID' => 'Titus',
        'tn_abbr' => 'தீத்',
        'tn_name' => 'தீத்து'
    ],
    66 => [
        'totalChapters' => '1',
        'osisID' => 'Phlm',
        'tn_abbr' => 'பில',
        'tn_name' => 'பிலமோன்'
    ],
    67 => [
        'totalChapters' => '13',
        'osisID' => 'Heb',
        'tn_abbr' => 'எபி',
        'tn_name' => 'எபிரேயர்'
    ],
    68 => [
        'totalChapters' => '5',
        'osisID' => 'Jas',
        'tn_abbr' => 'யாக்',
        'tn_name' => 'யாக்கோபு'
    ],
    69 => [
        'totalChapters' => '5',
        'osisID' => '1Pet',
        'tn_abbr' => '1 பேது',
        'tn_name' => '1 பேதுரு'
    ],
    70 => [
        'totalChapters' => '3',
        'osisID' => '2Pet',
        'tn_abbr' => '2 பேது',
        'tn_name' => '2 பேதுரு'
    ],
    71 => [
        'totalChapters' => '5',
        'osisID' => '1John',
        'tn_abbr' => '1 யோவா',
        'tn_name' => '1 யோவான்'
    ],
    72 => [
        'totalChapters' => '1',
        'osisID' => '2John',
        'tn_abbr' => '2 யோவா',
        'tn_name' => '2 யோவான்'
    ],
    73 => [
        'totalChapters' => '1',
        'osisID' => '3John',
        'tn_abbr' => '3 யோவா',
        'tn_name' => '3 யோவான்'
    ],
    74 => [
        'totalChapters' => '1',
        'osisID' => 'Jude',
        'tn_abbr' => 'யூதா',
        'tn_name' => 'யூதா'
    ],
    75 => [
        'totalChapters' => '22',
        'osisID' => 'Rev',
        'tn_abbr' => 'திவெ',
        'tn_name' => 'திருவெளிப்பாடு'
    ],
    100 => [
        'totalChapters' => NULL,
        'osisID' => NULL,
        'tn_abbr' => NULL,
        'tn_name' => 'முன்னுரை'
    ],
    400 => [
        'totalChapters' => NULL,
        'osisID' => NULL,
        'tn_abbr' => NULL,
        'tn_name' => 'இணைத் திருமுறை நூல்கள்'
    ],
    540 => [
        'bn' => '540',
        'totalChapters' => NULL,
        'osisID' => NULL,
        'tn_abbr' => NULL,
        'tn_name' => 'திருமுகங்கள்'
    ],
    680 => [
        'bn' => '680',
        'totalChapters' => NULL,
        'osisID' => NULL,
        'tn_abbr' => NULL,
        'tn_name' => 'பொதுத் திருமுகங்கள்'
    ]
];