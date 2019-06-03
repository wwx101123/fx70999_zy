 window.onload=function(){ 
	document.getElementsByClassName('table')[0].classList.add('ltable')
	document.getElementsByClassName('ibox-title')[0].setAttribute('style', 'background-color:white')
	document.getElementsByClassName('ibox-title')[0].setAttribute('style', 'border-color:white')
	document.getElementsByClassName('btn')[0].setAttribute('style', 'color:white') 
	
	var dc=getRootPath_dc()+'/Main';
	
	document.write('<link rel="stylesheet" type="text/css" href="'+dc+'/Public/scripts/artdialog/ui-dialog.css" />');


document.write('<link rel="stylesheet" type="text/css" href="'+dc+'/Public/Adm/skin/icon/iconfont.css" />');
document.write('<link rel="stylesheet" type="text/css" href="'+dc+'/Public/Adm/skin/default/style.css" />');
document.write('<script type="text/javascript" charset="utf-8" src="'+dc+'/Public/scripts/jquery/jquery-1.11.2.min.js"></script>');
document.write('<script type="text/javascript" charset="utf-8" src="'+dc+'/Public/scripts/jquery/jquery.nicescroll.js"></script>');
document.write('<script type="text/javascript" charset="utf-8" src="'+dc+'/Public/scripts/artdialog/dialog-plus-min.js"></script>');
document.write('<script type="text/javascript" charset="utf-8" src="'+dc+'/Public/Adm/js/layindex.js"></script>');
document.write('<script type="text/javascript" charset="utf-8" src="'+dc+'/Public/Adm/js/common.js"></script>');
	
 }
 function getRootPath_dc() {
            var pathName = window.location.pathname.substring(1);
            var webName = pathName == '' ? '' : pathName.substring(0, pathName.indexOf('/'));
            if (webName == "") {
                return window.location.protocol + '//' + window.location.host;
            }
            else {
                return window.location.protocol + '//' + window.location.host + '/' + webName;
            }
        }