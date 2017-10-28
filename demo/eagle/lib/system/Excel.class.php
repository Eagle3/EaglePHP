<?php
namespace lib\system;

use lib\system\Import;

class Excel
{

    private static function init()
    {
        Import::load('eagle/lib/plugin/PHPExcel/PHPExcel.php');
        
        // 用于输出.xls文件
        Import::load('eagle/lib/plugin/PHPExcel/PHPExcel/Writer/Excel5.php');
        
        // 用于输出.xlsx
        Import::load('eagle/lib/plugin/PHPExcel/PHPExcel/Writer/Excel2007.php');
        Import::load('eagle/lib/plugin/PHPExcel/PHPExcel/IOFactory.php');
        
        // 用于修改输出样式
        Import::load('eagle/lib/plugin/PHPExcel/PHPExcel/Style/Color.php');
        Import::load('eagle/lib/plugin/PHPExcel/PHPExcel/Style/Font.php');
        Import::load('eagle/lib/plugin/PHPExcel/PHPExcel/Style/Alignment.php');
        Import::load('eagle/lib/plugin/PHPExcel/PHPExcel/Style/Border.php');
    }

    /**
     * excel导出 （精简版，去掉不必要的样式，数据最好在500以内）
     * 
     * @param array $data
     *            导出的数据 必须
     * @param string $version
     *            导出的版本 2003或者2007 可选
     * @param string $fileName
     *            导出的文件名 可选
     * @param array $headFields
     *            导出的字段格式化后名称（表格头部列的名称） 可选
     * @param unknown $keys
     *            导出的字段名称 可选
     */
    public static function import($data, $version = '2003', $fileName = '', $headFields = array(), $keys = array())
    {
        self::init();
        $objPHPExcel = new \PHPExcel();
        
        if (! $fileName) {
            $fileName = date('YmdHis');
        }
        
        if (! $keys) {
            $keys = array_keys($data[0]);
        }
        
        if (! $headFields) {
            $headFields = $keys;
        }
        
        // 字段总数
        $totalFields = count($headFields);
        // A字母ASCII码
        $asciiA = ord('A');
        // 设置excel表头
        for ($i = $asciiA; $i < ($asciiA + $totalFields); $i ++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($i) . '1', $headFields[$i - $asciiA]);
            $objPHPExcel->getActiveSheet()
                ->getColumnDimension(chr($i))
                ->setWidth(15);
        }
        
        // 插入excel内容
        $len = count($data);
        for ($i = 0; $i < $len; $i ++) {
            for ($j = $asciiA; $j < ($asciiA + $totalFields); $j ++) {
                $objPHPExcel->getActiveSheet(0)->setCellValue(chr($j) . ($i + 2), $data[$i][$keys[$j - $asciiA]]);
            }
        }
        
        // 导出下载
        if ($version == '2003') {
            $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/vnd.ms-execl");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");
            
            $encoded_filename = urlencode($fileName);
            $ua = $_SERVER["HTTP_USER_AGENT"];
            if (preg_match("/MSIE/", $ua)) {
                header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
            } else 
                if (preg_match("/Firefox/", $ua)) {
                    header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
                } else {
                    header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
                }
            
            header("Content-Transfer-Encoding:binary");
            $objWriter->save('php://output');
            exit();
        } elseif ($version == '2007') {
            $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/vnd.ms-execl");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");
            
            $encoded_filename = urlencode($fileName);
            $ua = $_SERVER["HTTP_USER_AGENT"];
            if (preg_match("/MSIE/", $ua)) {
                header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xlsx"');
            } else 
                if (preg_match("/Firefox/", $ua)) {
                    header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xlsx"');
                } else {
                    header('Content-Disposition: attachment; filename="' . $fileName . '.xlsx"');
                }
            
            header("Content-Transfer-Encoding:binary");
            $objWriter->save('php://output');
            exit();
        }
    }
}