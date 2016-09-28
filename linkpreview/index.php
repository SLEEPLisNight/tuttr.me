<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>99Points.info :Fresh Tutorial: Facebook Like URL data Extract Using jQuery PHP and Ajax </title>

<link rel="stylesheet" href="css.css" type="text/css">
<script type="text/javascript" src="jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="jquery.livequery.js"></script>
<script type="text/javascript" src="jquery.watermarkinput.js"></script>
</head>
<script type="text/javascript">
	// <![CDATA[	
	$(document).ready(function(){	
	
		// delete event
		$('#attach').livequery("click", function(){
		
			if(!isValidURL($('#url').val()))
			{
				alert('Please enter a valid url.');
				return false;
			}
			else
			{
				$('#load').show();
				$.post("fetch.php?url="+$('#url').val(), {
				}, function(response){
					$('#loader').html($(response).fadeIn('slow'));
					$('.images img').hide();
					$('#load').hide();
					$('img#1').fadeIn();
					$('#cur_image').val(1);
				});
			}
		});	
		// next image
		$('#next').livequery("click", function(){
		
			var firstimage = $('#cur_image').val();
			$('#cur_image').val(1);
			$('img#'+firstimage).hide();
			if(firstimage <= $('#total_images').val())
			{
				firstimage = parseInt(firstimage)+parseInt(1);
				$('#cur_image').val(firstimage);
				$('img#'+firstimage).show();
			}
		});	
		// prev image
		$('#prev').livequery("click", function(){
		
			var firstimage = $('#cur_image').val();
			
			$('img#'+firstimage).hide();
			if(firstimage>0)
			{
				firstimage = parseInt(firstimage)-parseInt(1);
				$('#cur_image').val(firstimage);
				$('img#'+firstimage).show();
			}
			
		});	
		// watermark input fields
		jQuery(function($){
		   
		   $("#url").Watermark("http://");
		});
		jQuery(function($){

		    $("#url").Watermark("watermark","#369");
			
		});	
		function UseData(){
		   $.Watermark.HideAll();
		   $.Watermark.ShowAll();
		}

	});	
	
	function isValidURL(url){
		var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
	
		if(RegExp.test(url)){
			return true;
		}else{
			return false;
		}
	}

	// ]]>
</script>
<body>
<div align="center">
<br clear="all" /><br clear="all" />
<div style="font-size:30px;">Facebook Like URL data Extract Using jQuery PHP and Ajax</div>
<br clear="all" />
<a style="color:#000000; font-size:14px" href="http://www.99points.info/2010/07/youtube-style-share-button-with-url-shortening-using-curl-jquery-and-php">Back To Tutorial</a>
<br clear="all" />

<input type="hidden" name="cur_image" id="cur_image" />
<div class="wrap" align="center">

	<div class="box" align="left">
	
		<div class="head">Link</div>
		<div class="close" align="right">
			<div class="closes"></div>
		</div>
		
		<br clear="all" /><br clear="all" />
		
		<input type="text" name="url" size="64" id="url" />
		<input type="button" name="attach" value="Attach" id="attach" />
		<br clear="all" />
		
		<div id="loader">
			
			<div align="center" id="load" style="display:none"><img src="load.gif" /></div>
			
		</div>
		<br clear="all" />
	</div>

</div>
</div>
  <br clear="all" />
</body>
</html>
