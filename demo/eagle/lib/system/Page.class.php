<?php
/*
 *
 							使用方式
一、分页样式1使用示例：
use lib\system;
$page = new Page($total_num, $page_size, $cur_page, $url);//参数：总条数、每页显示条数、当前页、分页链接
//方式1：正常分页
$style_page = $page->pubPageStyleOne();//正常分页使用

//方式2：ajax分页，需要自己写js
$style_page = $page->pubPageStyleOne(true,'ajax_page');//ajax分页使用。ajax_page为js函数名称
//添加js函数，进行ajax分页
function ajax_page(ajax_url){
	//ajax请求分页代码
}

二、分页样式2使用示例：
use lib\system;
$page = new Page($total_num, $page_size, $cur_page, $url);//参数：总条数、每页显示条数、当前页、分页链接

//方式1：正常分页
$style_page = $page->pubPageStyleTwo();//正常分页使用

//方式2：ajax分页，需要自己写js
$style_page = $page->pubPageStyleTwo(true,'ajax_page','ajax_jump_page');//ajax分页使用。ajax_page为js函数名称
//添加js函数，进行ajax分页
function ajax_page(ajax_url){
	//ajax请求分页代码
}
//跳转按钮js函数
function ajax_jump_page(jump_url){
	//ajax请求分页代码
}

*/

namespace lib\system;

class Page{
	//总条数
	private $total_num;
	//每页显示条数
	private $page_size = 10;
	//总页数（也是最大页码数）
	private $total_page;
	//上一页页码数
	private $pre_page_num;
	//当前页码数
	private $cur_page_num;
	//下一页页码数
	private $next_page_num;
	//分页URL链接（传入参数：如：http://www.domain.com/home/index/page/）
	private $url = '';
	//上一页URL
	private $pre_url = '';
	//下一页URL
	private $next_url = '';

	/**
	 * 初始化参数
	 * @param integer 	$total_num 	总条数
	 * @param integer 	$page_size 	每页显示条数
	 * @param integer 	$cur_page  	当前页数
	 * @param string 	$url    	分页URL链接
	 */
	public function __construct($total_num,$page_size,$cur_page,$url){
		//总条数
		$this->total_num = !empty($total_num) ? intval($total_num) : 0;
		//每页显示条数
		$this->page_size = !empty($page_size) ? intval($page_size) : 10;
		//总页数
		$this->total_page = ceil($this->total_num / $this->page_size);

		$this->url = !empty($url) ? $url : '';

		//当前页
		if(!empty($cur_page)){
			if($cur_page >= $this->total_page){ 	//传入的当前页数  >= 最大页码数
				$this->cur_page_num = $this->total_page;
			}else{									//1 < 传入的当前页数  < 最大页码数
				$this->cur_page_num = intval($cur_page);
			}
		}else{
			$this->cur_page_num = 1;
		}

		//上一页页码数
		if($this->total_page >= 2 && $this->cur_page_num > 1){
			$this->pre_page_num = $this->cur_page_num - 1;
		}else{
			$this->pre_page_num = 1;
		}

		//下一页页码数
		if($this->total_page >= 2 && $this->cur_page_num < $this->total_page){
			$this->next_page_num = $this->cur_page_num + 1;
		}else{
			$this->next_page_num = 1;
		}

	}
	
	/**
	 * 处理上一页分页:样式1上一页
	 * @param string $ajax_page   是否ajax分页
	 * @param string $ajax_fun    ajax分页时函数名
	 */
	private function priDealPrePageOne($ajax_page,$ajax_fun){
		//判断是否是ajax分页
		$is_ajax = $ajax_page && !empty($ajax_fun) ? true : false;
		$page_style = '';
		$page_style .= '<p>';
		//上一页
		if($this->cur_page_num == 1){
			$this->pre_url = '<a href="javascript:;" class="three_prev" style="color:#ababab;">上一页</a>';
		}else{
			if($is_ajax){
				$this->pre_url = '<a '.$this->priAjaxUrl($ajax_fun,$this->pre_page_num).' href="javascript:;" class="three_prev">上一页</a>';
			}else{
				$this->pre_url = '<a '.$this->priHrefUrl($this->pre_page_num).' class="three_prev">上一页</a>';
			}
		}
		$page_style .= $this->pre_url;
		return $page_style;
	}
	
	/**
	 * 处理上一页分页:样式2上一页
	 * @param string $ajax_page   是否ajax分页
	 * @param string $ajax_fun    ajax分页时函数名
	 */
	private function priDealPrePageTwo($ajax_page,$ajax_fun){
		//判断是否是ajax分页
		$is_ajax = $ajax_page && !empty($ajax_fun) ? true : false;
		$page_style = '';
		$page_style .= '<div class="three_acc_pageleft">';
		//上一页
		if($this->cur_page_num == 1){
			$this->pre_url = '<a href="javascript:;" class="prev" style="color:#ababab;">上一页</a>';
		}else{
			if($is_ajax){
				$this->pre_url = '<a '.$this->priAjaxUrl($ajax_fun,$this->pre_page_num).' href="javascript:;" class="prev">上一页</a>';
			}else{
				$this->pre_url = '<a '.$this->priHrefUrl($this->pre_page_num).' class="prev">上一页</a>';
			}
		}
		$page_style .= $this->pre_url;
		return $page_style;
	}
	
	/**
	 * 处理中间数字分页：样式1中间分页
	 * @param string $ajax_page   是否ajax分页
	 * @param string $ajax_fun    ajax分页时函数名
	 */
	private function priDealCenterPageOne($ajax_page = false, $ajax_fun = ''){
		$is_ajax = $ajax_page && !empty($ajax_fun) ? true : false;
		$center_page = '';
		//step1:省略号前面的链接（显示前面2页。条件是：总条数>=5 && 不是最后4页时 ）
		if($this->total_page >= 5 && $this->total_page - 3 > $this->cur_page_num){
			//当前页下一页页码
			$cur_next_num = $this->cur_page_num + 1;
			//当前页
			$center_page .= '<a class="cur" href="javascript:;">'.$this->cur_page_num.'</a>';
			//当前页的下一页
			if($is_ajax){
				$center_page .= '<a '.$this->priAjaxUrl($ajax_fun, $cur_next_num).' href="javascript:;">'.$cur_next_num.'</a>';
			}else{
				$center_page .= '<a '.$this->priHrefUrl($cur_next_num).'>'.$cur_next_num.'</a>';
			}
		}else{
			if($this->total_page < 5){
				$i = 1;
			}else{
				$i = $this->total_page - 3;
			}
			$count = $this->total_page;
	
			for($step=$i;$step<=$count;$step++){
				if($step == $this->cur_page_num){
					//当前页的下一页
					$center_page .= '<a class="cur" href="javascript:;">'.$step.'</a>';
				}else{
					if($is_ajax){
						$center_page .= '<a '.$this->priAjaxUrl($ajax_fun, $step).' href="javascript:;">'.$step.'</a>';
					}else{
						$center_page .= '<a '.$this->priHrefUrl($step).'>'.$step.'</a>';
					}
				}
			}
		}
	
		//step2:中间省略号（总页数>=5 && 不是最后4页时 ，显示省略号；总页数<5 || 是最后4页时不显示省略号）
		if($this->total_page >= 5 && $this->total_page - 3 > $this->cur_page_num){
			$center_page .= '<a href="javascript:;">...</a>';
		}
	
		//step3:省略号后面的链接（显示最后2页。条件是：总条数>=5）
		if($this->total_page >= 5 && $this->total_page - 3 > $this->cur_page_num){
			//倒数第二页页码
			$two_last_num = $this->total_page - 1;
			
			//当前页是倒数第二页
			if($this->cur_page_num == $two_last_num){
				$center_page .= '<a class="cur" href="javascript:;">'.$two_last_num.'</a>';
			}else{
				if($is_ajax){
					$center_page .= '<a '.$this->priAjaxUrl($ajax_fun, $two_last_num).' href="javascript:;">'.$two_last_num.'</a>';
				}else{
					$center_page .= '<a '.$this->priHrefUrl($two_last_num).'>'.$two_last_num.'</a>';
				}
			}
	
			//当前页是最后一页
			if($this->cur_page_num == $this->total_page){
				$center_page .= '<a class="cur" href="javascript:;">'.$this->total_page.'</a>';
			}else{
				if($is_ajax){
					$center_page .= '<a '.$this->priAjaxUrl($ajax_fun, $this->total_page).' href="javascript:;">'.$this->total_page.'</a>';
				}else{
					$center_page .= '<a '.$this->priHrefUrl($this->total_page).'>'.$this->total_page.'</a>';
				}
			}
		}
	
		return $center_page;
	}
	
	/**
	 * 处理中间数字分页：样式2中间分页
	 * @param string $ajax_page   是否ajax分页
	 * @param string $ajax_fun    ajax分页时函数名
	 */
	private function priDealCenterPageTwo($ajax_page = false, $ajax_fun = ''){
		$is_ajax = $ajax_page && !empty($ajax_fun) ? true : false;
		$center_page = '';
		//step1:省略号前面的链接（显示前面2页。条件是：总条数>=5 && 不是最后4页时 ）
		if($this->total_page >= 5 && $this->total_page - 3 > $this->cur_page_num){
			//当前页下一页页码
			$cur_next_num = $this->cur_page_num + 1;
			//当前页下下一页页码
			$cur_next_next_num = $this->cur_page_num + 2;
			//当前页
			$center_page .= '<a class="cur" href="javascript:;">'.$this->cur_page_num.'</a>';
			//当前页的下一页
			if($is_ajax){
				$center_page .= '<a '.$this->priAjaxUrl($ajax_fun, $cur_next_num).' href="javascript:;">'.$cur_next_num.'</a>';
			}else{
				$center_page .= '<a '.$this->priHrefUrl($cur_next_num).'>'.$cur_next_num.'</a>';
			}
			//当前页的下下一页
			if($is_ajax){
				$center_page .= '<a '.$this->priAjaxUrl($ajax_fun, $cur_next_next_num).' href="javascript:;">'.$cur_next_next_num.'</a>';
			}else{
				$center_page .= '<a '.$this->priHrefUrl($cur_next_next_num).'>'.$cur_next_next_num.'</a>';
			}
		}else{
			if($this->total_page < 5){
				$i = 1;
			}else{
				$i = $this->total_page - 3;
			}
			$count = $this->total_page;
	
			for($step=$i;$step<=$count;$step++){
				if($step == $this->cur_page_num){
					//当前页的下一页
					$center_page .= '<a class="cur" href="javascript:;">'.$step.'</a>';
				}else{
					if($is_ajax){
						$center_page .= '<a '.$this->priAjaxUrl($ajax_fun, $step).' href="javascript:;">'.$step.'</a>';
					}else{
						$center_page .= '<a '.$this->priHrefUrl($step).'>'.$step.'</a>';
					}
				}
			}
		}
	
		//step2:中间省略号（总页数>=5 && 不是最后4页时 ，显示省略号；总页数<5 || 是最后4页时不显示省略号）
		if($this->total_page >= 5 && $this->total_page - 3 > $this->cur_page_num){
			$center_page .= '<a href="javascript:;">...</a>';
		}
	
		//step3:省略号后面的链接（显示最后2页。条件是：总条数>=5）
		if($this->total_page >= 5 && $this->total_page - 3 > $this->cur_page_num){
			//当前页是最后一页
			if($this->cur_page_num == $this->total_page){
				$center_page .= '<a class="cur" href="javascript:;">'.$this->total_page.'</a>';
			}else{
				if($is_ajax){
					$center_page .= '<a '.$this->priAjaxUrl($ajax_fun, $this->total_page).' href="javascript:;">'.$this->total_page.'</a>';
				}else{
					$center_page .= '<a '.$this->priHrefUrl($this->total_page).'>'.$this->total_page.'</a>';
				}
			}
		}
	
		return $center_page;
	}
	
	/**
	 * 处理下一页分页:样式1下一页
	 * @param string $ajax_page   是否ajax分页
	 * @param string $ajax_fun    ajax分页时函数名
	 */
	private function priDealNextPageOne($ajax_page,$ajax_fun){
		$page_style = '';
		//判断是否是ajax分页
		$is_ajax = $ajax_page && !empty($ajax_fun) ? true : false;
		//下一页
		if($this->cur_page_num == $this->total_page){
			$this->next_url = '<a href="javascript:;" class="three_next" style="color:#ababab;">下一页</a>';
		}else{
			if($is_ajax){
				$this->next_url = '<a '.$this->priAjaxUrl($ajax_fun,$this->next_page_num).' href="javascript:;" class="three_next">下一页</a>';
			}else{
				$this->next_url = '<a '.$this->priHrefUrl($this->next_page_num).' class="three_next">下一页</a>';
			}
		}
		$page_style .= $this->next_url;
		$page_style .= '</p>';
		return $page_style;
	}
	
	/**
	 * 处理下一页分页:样式2下一页
	 * @param string $ajax_page   是否ajax分页
	 * @param string $ajax_fun    ajax分页时函数名
	 */
	private function priDealNextPageTwo($ajax_page,$ajax_fun){
		$page_style = '';
		//判断是否是ajax分页
		$is_ajax = $ajax_page && !empty($ajax_fun) ? true : false;
		//下一页
		if($this->cur_page_num == $this->total_page){
			$this->next_url = '<a href="javascript:;" class="next" style="color:#ababab;">下一页</a>';
		}else{
			if($is_ajax){
				$this->next_url = '<a '.$this->priAjaxUrl($ajax_fun,$this->next_page_num).' href="javascript:;" class="next">下一页</a>';
			}else{
				$this->next_url = '<a '.$this->priHrefUrl($this->next_page_num).' class="next">下一页</a>';
			}
		}
		$page_style .= $this->next_url;
		$page_style .= '</div>';
		return $page_style;
	}
	
	//跳转分页
	private function priDealJumpPage($ajax_page = false, $ajax_jump_fun = ''){
		$is_ajax = $ajax_page && !empty($ajax_jump_fun) ? true : false;
		$page_style = '';
		$page_style .= '<div class="three_acc_pageright">';
		$page_style .= '<p>'.$this->cur_page_num.'/'.$this->total_page.'页</p>';
		$page_style .= '<input type="text" class="jump_page" name="jump_page" value="" />';
		if($is_ajax){
			$page_style .= '<a class="jump_aid" href="javascript:;" '.$this->priAjaxJumpUrl($ajax_jump_fun).'>跳转</a>';
		}else{
			$page_style .= '<a class="jump_aid" href="javascript:;" url="'.$this->url.'">跳转</a>';
		}
		$page_style .= '</div>';
		return $page_style;
	}
	
	/**
	 * ajax分页链接
	 * @param string $ajax_fun    ajax分页函数
	 * @param integer $page        页码
	 * @return string 			     返回onclick
	 */
	private function priAjaxUrl($ajax_fun, $page = 1){
		$ajax_url = ' onClick="';
		$ajax_url .= $ajax_fun."('";
		$ajax_url .= $this->url.$page;
		$ajax_url .= "')";
		$ajax_url .= '"';
		return $ajax_url;
	}
	
	private function priAjaxJumpUrl($ajax_jump_fun = ''){
		if(empty($ajax_jump_fun)){
			$ajax_jump_url = ' url="';
			$ajax_jump_url .= $this->url;
			$ajax_jump_url .= '"';
		}else{
			$ajax_jump_url = ' onClick="';
			$ajax_jump_url .= $ajax_jump_fun."('";
			$ajax_jump_url .= $this->url;
			$ajax_jump_url .= "')";
			$ajax_jump_url .= '"';
		}
		return $ajax_jump_url;
	}
	
	/**
	 * href分页链接
	 * @param integer $page 页码
	 * @return string       返回href链接
	 */
	private function priHrefUrl($page = 1){
		$href = ' href="'.$this->url.$page.'" ';
		return $href;
	}
	
	/**
	 * 输出分页样式1:  上一页 1 2 ... 13 14 下一页
	 * @param string $ajax_page   是否ajax分页，默认否；true时，需传入函数名
	 * @param string $ajax_fun    ajax分页时函数名
	 * @return string  返回分页样式
	 */
	public function pubPageStyleOne($ajax_page = false, $ajax_fun = ''){
		if($this->total_page <= 1){
			return '';
		}
		//上一页
		$style_page_pre = $this->priDealPrePageOne($ajax_page,$ajax_fun);
		//中间数字页样式 1 2 ... 13 14
		$center_page = $this->priDealCenterPageOne($ajax_page, $ajax_fun);
		//下一页
		$style_page_next = $this->priDealNextPageOne($ajax_page,$ajax_fun);
		
		$page_style = $style_page_pre.$center_page.$style_page_next;
		
		return $page_style;
	}
	
	/**
	 * 输出分页样式2:   上一页 1 2 3 ...  14 下一页    1/14 页 [输入]跳转
	 * @param string $ajax_page   			是否ajax分页，默认否；true时，需传入函数名
	 * @param string $ajax_fun    			ajax分页时函数名
	 * @param string $ajax_jump_fun    		ajax跳转时函数名
	 * @return string  返回分页样式
	 */
	public function pubPageStyleTwo($ajax_page = false, $ajax_fun = '', $ajax_jump_fun = ''){
		if($this->total_page <= 1){
			return '';
		}
		$is_ajax = $ajax_page && !empty($ajax_fun) && !empty($ajax_jump_fun) ? true : false;
		//上一页
		$style_page_pre = $this->priDealPrePageTwo($ajax_page,$ajax_fun);
		//中间数字页样式 1 2 3... 14
		$center_page = $this->priDealCenterPageTwo($ajax_page, $ajax_fun);
		//下一页
		$style_page_next = $this->priDealNextPageTwo($ajax_page,$ajax_fun);
		//跳转
		$style_page_jump = $this->priDealJumpPage($ajax_page,$ajax_jump_fun);
		
		//ajax跳转js
		if($is_ajax){
			$jump_js = '';
		}else{ //非ajax跳转
			$jump_js = <<<JS
			<script>
				$(function(){
					$(".jump_aid").click(function(){
						var jump_page = $(".jump_page").val();
						var url = $('.jump_aid').attr('url');
						window.location.href = url+jump_page;
					});
				});
			</script>
JS;
		}
		
		$page_style = $style_page_pre.$center_page.$style_page_next.$style_page_jump.$jump_js;
		return $page_style;
	}
}