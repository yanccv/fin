<?php

?>
<html>
<head>
<script type="text/javascript" src="../../scripts/jquery/jquery.js"></script>
<script type="text/javascript" src="../../scripts/jquery/jqueryui.js" ></script>
<script type="text/javascript" src="magnific-popup.js"></script>
<link rel='stylesheet prefetch' href='magnific-popup.css'>
<script>
$(document).ready(function() {
	$('.popup-gallery').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			titleSrc: function(item) {
				return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
			}
		}
	});
});
</script>
</head>
<body>
<div class="popup-gallery" style="width: 700px; height;">
	<a href="1.png" title="The Cleaner"><img src="1.png" height="75" width="75"></a>
	<a href="2.png" title="Winter Dance"><img src="2.png" height="75" width="75"></a>
	<a href="3.png" title="The Uninvited Guest"><img src="3.png" height="75" width="75"></a>
	<a href="4.png" title="Oh no, not again!"><img src="4.png" height="75" width="75"></a>
</div>
</body>
</html>