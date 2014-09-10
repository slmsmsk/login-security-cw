<?php
/*
Plugin Name: Login Security CW
Plugin URI: http://www.arduinoturk.com
Description: Bu eklenti wordpress giriş sayfanıza fazladan parametre oluşturup tipik wordpress giriş adresinden kurtulmanızı ve brute force ihtimalini ortadan kaldırmak için oluşturulmuştur.
Version: 1.0.1
Author: Selim Şimşek
Author URI: http://www.arduinoturk.com
License: GNU
*/

// en-US , tr-TR
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])){ die('You are not allowed to call this page directly.'); }



function kontrol(){
	$d_adi = get_option("d_adi");
	$d_deger = get_option("d_deger");
	if($_GET["email"] and $_GET["email"] == get_option('admin_email')){
		link_goster();
	}else if(!$_GET[$d_adi]  or $_GET[$d_adi] != $d_deger){
		form_gizle();
	}
}

function form_gizle(){
	print '<p style="padding:0 20px; color:#f00; font-weight:bold; margin:100px auto 0 auto; width:400px; text-align:center;">Giriş başarısız, doğru şekilde giriş yapmayı deneyeniz..</p><p style="margin:0 auto; width:400px; text-align:center; font-weight:bold; font-size:10px; color:#21C233;">Login Security CW</p>';
	exit;
}
function link_goster(){
	$d_adi = get_option("d_adi");
	$d_deger = get_option("d_deger");
	print '<p style="padding:0 20px; color:#666; font-weight:bold; margin:100px auto 0 auto; width:500px; text-align:center;">'.home_url().'/wp-login.php?'.$d_adi.'='.$d_deger.'</p><p style="margin:0 auto; width:400px; text-align:center; font-weight:bold; font-size:10px; color:#21C233;">Login Security CW</p>';
	exit;
}

function lscw_style() {
	print '<style type="text/css">
	.login-security-cw{
		margin:20px 0;
		padding:0 20px 20px 20px;
		width:560px;
		background-color:#fff;
		border:1px solid #ddd;
	}
	.login-security-cw input[type=text]{
		display:block;
		padding:2px 8px;
		font-size:15px;
		border:1px solid #ddd;
		box-shadow:none;
		width:540px;
		line-height:25px;
	}
	.login-security-cw input[type=text]:focus{
		outline:none;
		border-color:#111;
	}
	.login-security-cw input[type=submit]{
		margin-top:20px;
		border:1px solid #ddd;
		background-color:#f1f1f1;
		border-radius:2px;
		color:#000;
		cursor:pointer;
		padding:3px 10px;
	}
	.login-security-cw label{
		color:#f00;
		font-weight:bold;
		font-size:13px;
		display:block;
		margin-top:15px;
		margin-bottom:4px;
		cursor:default;
	}
	.login-security-cw .updated{
		width:520px;
		padding:2px 10px;
		background-color:#ACFA58;
		border-left:5px solid #088A08;
		color:#000;
		margin-left:0;
		margin-top:20px;
	}
	.login-security-cw .link{
		background-color:#f9f9f9;
		border:1px solid #000;
		border-left:5px solid #000;
		width:497px;
		padding:10px 20px;
		color:#000;
		margin-top:5px;
	}
	.login-security-cw .link_title{
		color:#f00;
		font-weight:bold;
		font-size:13px;
		margin-top:20px;
	}
	</style>';
}

function secenekleri_tanimla(){
	add_option('d_adi', 'cyber');
	add_option('d_deger', 'warrior');
}

register_deactivation_hook(__FILE__, 'secenekleri_kaldir');

function secenekleri_kaldir(){
	delete_option('d_adi');
	delete_option('d_deger');
}


function plugin_add_settings_link($links){
    $settings_link = '<a href="options-general.php?page=login-security-cw">Ayarlar</a>';
  	array_push( $links, $settings_link );
  	return $links;
}

function t_karakter_temizle($kelime){
	$eski=array("ş","Ş","ı","ü","Ü","ö","Ö","ç","Ç","ş","Ş","ı","ğ","Ğ","İ","ö","Ö","Ç","ç","ü","Ü","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","R","S","T","U","V","Y","Z","W","Q");
	$yeni=array("s","s","i","u","u","o","o","c","c","s","s","i","g","g","i","o","o","c","c","u","u","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","r","s","t","u","v","y","z","w","q");
	
	$kelime=str_replace($eski,$yeni,$kelime);
	$kelime = preg_replace("@[^a-z0-9\-_şıüğçİŞĞÜÇ]+@i","-",$kelime);
	return $kelime;
}

function lgcw_yonetim(){ add_options_page('login-security-cw','Login Security CW', '8', 'login-security-cw', 'lscw'); }

function lscw(){
?>
<div class="login-security-cw">
	<h2>Login Security CW</h2>
<?php
	if(isset($_POST["ayarlar"]) and $_POST["ayarlar"] == 'guncellendi'){
		$d_adi = strip_tags(htmlspecialchars(t_karakter_temizle($_POST['d_adi'])));
		$d_deger = strip_tags(htmlspecialchars(t_karakter_temizle($_POST['d_deger'])));
        update_option('d_adi', $d_adi);
        update_option('d_deger', $d_deger);
?>
<div class="updated"><p><strong><?php _e('Değişiklikler kaydedildi.'); ?></strong></p></div>
<?php
	}
?>
<form method="post" action="">
	<label for="d_adi">Değişken Adı:</label>
	<input type="text" id="d_adi" name="d_adi" value="<?php print get_option("d_adi"); ?>"/>
	<label for="d_degeri">Değişken Değeri:</label>
	<input type="text" id="d_deger" name="d_deger" value="<?php print get_option("d_deger"); ?>"/>
	<input type="hidden" id="ayarlar"name="ayarlar" value="guncellendi" />
	<input type="submit" id="submit" name="submit" value="<?php _e('Save Changes'); ?>"/>
	<div class="link_title">Login Adresi :</div>
	<div class="link"><?php bloginfo("url"); ?>/wp-login.php?<?php echo get_option("d_adi"); ?>=<?php echo get_option("d_deger"); ?></div>
</form>
</div>

<?php }

register_activation_hook(__FILE__, 'secenekleri_tanimla');
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );
add_action('admin_head', 'lscw_style');
add_action('admin_menu', 'lgcw_yonetim');
add_action('login_form','kontrol');
add_action('login_head', 'kontrol');

 ?>
