<?php header( 'Content-Type: application/javascript' );
if ( isset( $_GET['id'] ) ) {
	$tracking_id = strval( $_GET['id'] );
} else {
	exit();
}

$anonymize_ip = '';
if ( isset( $_GET['anonymize'] ) && ( intval( $_GET['anonymize'] ) === 1 ) ) {
	$anonymize_ip = ', { \'anonymize_ip\': true }';
}

if ( preg_match( '/^(ua-\d{4,11}-\d{1,4})$/i', $tracking_id ) ) :
	?>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', '<?php echo $tracking_id; ?>', 'auto'<?php echo $anonymize_ip; ?>);
ga('send', 'pageview');<?php
elseif ( preg_match( '/^(g(tm)?-[a-z0-9]{4,11})$/i', $tracking_id ) ) :
	?>
(function(i,s,o,g,r,a,m){a=s.createElement(o);m=s.getElementsByTagName(o)[0];
a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.googletagmanager.com/gtag/js?id=<?php echo $tracking_id; ?>');
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '<?php echo $tracking_id; ?>'<?php echo $anonymize_ip; ?>);<?php
endif;
