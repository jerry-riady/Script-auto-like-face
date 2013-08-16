<?php
error_reporting(NULL);
session_start();
##############SETTING###############################################################################
$bot['like'] = true; //--------->Autolike
$bot['ck_k'] = false;//-------->ini auto koment, cuma ya males aja ngaktifinnya. Disangka Junk
$bot['ck_u'] = false;//-------->sama aja, ini fungsi komentar otomatis
$bot['time'] = true; //-------->aktu kalo ada komentar
$bot['aces'] = "********************************************"; //ini tokennya
##############END OF SETTING#############
com_like($cl,$ck,$cu,$tm,$access_token);
com_like($bot['like'],$bot['ck_k'],$bot['ck_u'],$bot['time'],$bot['aces']);

#####################################################################################################
function com_like($cl,$ck,$cu,$tm,$access_token){
$beranda = json_decode(httphit("https://graph.facebook.com/me/home?fields=id,from,type,message&limit=100&access_token=".$access_token))->data;
$saya_cr = json_decode(httphit("https://graph.facebook.com/me/feed?access_token=".$access_token));
if($beranda){
	 foreach($beranda as $cr_post){
		 if(!eregi($saya_cr->id,$cr_post->id)){
			 $log_cr = simlog($cr_post->id);
			 if($log_cr==true){
				 if($ck==true){
					 if($cl==true){
						 httphit("https://graph.facebook.com/".$cr_post->id."/likes?method=POST&access_token=".$access_token);
					 }
				 }
			 }
		 }
	 }
}
}
#######################################
function httphit($url){
return file_get_contents($url);
}
function simlog($cr_id) {
$fname = "cr_log.txt";
$lihatiplist=fopen ($fname, "rb");
$text='';
if($lihatiplist){
	 $spasipol = "";
	 do {
		 $barislistip = fread($lihatiplist, 512);
		 if(strlen($barislistip) == 0){ break; }
		 $spasipol .= $barislistip;
	 } while(true);
	 fclose ($lihatiplist);
	 for ($i = 1; $i <= 10; $i++) {$spasipol = str_replace(" ","",$spasipol);}
	 $text=$text.$spasipol;
}else{$text="";}
if(ereg($cr_id,$text)){
	 return false;
}else{
	 $text = $text.$cr_id;
	 $w_file=@fopen($fname,"w") or bberr();
	 if($w_file) {
		 @fputs($w_file,$text);
		 @fclose($w_file);
	 }
	 return true;
}
}
?>
