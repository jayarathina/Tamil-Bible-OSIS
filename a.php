<?php 
use Medoo\Medoo;

include_once 'lib/medoo.php';
include_once 'lib/bibleConfig.php';
include_once 'lib/redletter_osis.php';

include_once 'utils.php';

$db = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'liturgy_bible',
    'server' => 'localhost',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8'
]);

$d = new Utils();


echo "\r\n". $d->getReferenceTag("எபி 4:4அ, 7-8");
echo "\r\n". $d->getReferenceTag("எண் 13:25-14:38; இச 1:21,26; எபி 3:16");
echo "\r\n". $d->getReferenceTag("1 மக் 7");
echo "\r\n". $d->getReferenceTag("2 அர 23: 36-24 :6");//Cross Chapter
echo "\r\n". $d->getReferenceTag("பில 1,3-4,5");


echo "\r\n". $d->getReferenceTag("1 மக் 7:49");
echo "\r\n". $d->getReferenceTag("எஸ் (கி) 9:17-22");
echo "\r\n". $d->getReferenceTag("பில 1,3-4");


echo "\r\n". $d->getReferenceTag("காண். பாரூ 1,3-4");
echo "\r\n". $d->getReferenceTag("1 மக் 7:49; காண். எஸ் (கி) 9:17-22; 8:11");

echo "\r\n". $d->getReferenceTag("இச 4:2-6,7,9,12; 12:32");
echo "\r\n". $d->getReferenceTag("1 மக் 5:9-54; 12:15; யோசு 6:1-21");
echo "\r\n". $d->getReferenceTag("எபி 4:4அ, 7-8");