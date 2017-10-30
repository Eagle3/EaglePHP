<?php
/* Smarty version 3.1.30, created on 2017-10-30 22:01:38
  from "D:\workspace\zendstudio_workspace\9.1-EaglePHP\demo\App\Home\View\Index\upload.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_59f730c2e13a31_10769400',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '11cee70071f5cae249c4f6a7c306f2a53780a746' => 
    array (
      0 => 'D:\\workspace\\zendstudio_workspace\\9.1-EaglePHP\\demo\\App\\Home\\View\\Index\\upload.html',
      1 => 1509370925,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59f730c2e13a31_10769400 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>多文件上传</title>
</head>
<body>
	<h3 style="color:red;">上传限制：最多能上传5个文件</h3>	
	<form id="form1" action="http://eagle.test/index.php?r=home&c=index&a=upload" method="post" enctype="multipart/form-data">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<input type="submit" name="submit" value="点击上传"><br><br>
		<span>请选择文件：</span><input type="file" name="pic[]" />  <input type="button" value="点击添加更多" onclick='addNewLine()' /><br>
	</form>
	
	<?php echo '<script'; ?>
 type="text/javascript">
		function addNewLine(){
			var form=document.getElementById('form1');//获取父节点
			
			var span=document.createElement('span');//创建元素
			form.appendChild(span);//追加子节点
			span.innerHTML='请选择文件：';//为子节点属性赋值

			var input=document.createElement('input');//创建元素
			form.appendChild(input);//追加子节点
			input.type='file';//为子节点属性赋值
			input.name='pic[]';//为子节点属性赋值
			
			var del=document.createElement('input');//创建元素
			form.appendChild(del);//追加子节点
			del.type='button';//为子节点属性赋值
			del.value='删除';//为子节点属性赋值
			
			var br=document.createElement('br');//创建元素
			form.appendChild(br);//追加子节点
			
			del.onclick=function(){
				form.removeChild(span);
				form.removeChild(br);
				form.removeChild(del);
				form.removeChild(input);
			}	
		}
	<?php echo '</script'; ?>
>
</body>
</html><?php }
}
