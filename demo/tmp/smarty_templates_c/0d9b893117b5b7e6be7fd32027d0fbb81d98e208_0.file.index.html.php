<?php
/* Smarty version 3.1.30, created on 2017-10-30 11:18:09
  from "D:\eclipse_workplace\9.1-EaglePHP\demo\app\home\view\Index\index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_59f699f1394915_43284737',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0d9b893117b5b7e6be7fd32027d0fbb81d98e208' => 
    array (
      0 => 'D:\\eclipse_workplace\\9.1-EaglePHP\\demo\\app\\home\\view\\Index\\index.html',
      1 => 1509328495,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../Common/header.html' => 1,
  ),
),false)) {
function content_59f699f1394915_43284737 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Common/header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo $_smarty_tpl->tpl_vars['name']->value;?>


<?php echo '<script'; ?>
>

/*
 //为浏览器回退按钮添加监听事件

 pushHistory(); 
//清空浏览器历史记录  
function pushHistory() {  
    var url = "#";  
    var state = {  
        title: "title",  
        url: "#"  
    };  
    window.history.pushState(state, "title", "#");  
}

window.onload = function (){
	//监听浏览器后退事件  
	window.addEventListener("popstate",  
	    function(e) {  
	    //转向指定的URL  
		 alert( 1 );
	    window.history.go(-2);
	    
	    
	    }, false);  
	    
};

 */

/* 	eagle.ajaxPost({
		url : 'http://eagle.test/index.php?r=home&c=index&a=ajax',
		data : {
			name: 'jack'
		},
		success : function (res){
			console.log(res);
			console.log(JSON.parse(res));
		}
	}); 
*/

<?php echo '</script'; ?>
>


</body>
</html><?php }
}
