<?php
/* Smarty version 3.1.30, created on 2017-10-30 21:43:28
  from "D:\workspace\zendstudio_workspace\9.1-EaglePHP\demo\App\Home\View\Index\index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_59f72c804dac82_98989300',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ed748a26ef6abdadeddf1696dfd8173082c09f8f' => 
    array (
      0 => 'D:\\workspace\\zendstudio_workspace\\9.1-EaglePHP\\demo\\App\\Home\\View\\Index\\index.html',
      1 => 1509370925,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../Common/header.html' => 1,
  ),
),false)) {
function content_59f72c804dac82_98989300 (Smarty_Internal_Template $_smarty_tpl) {
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
