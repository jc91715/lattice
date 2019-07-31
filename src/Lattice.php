<?php

namespace Jc91715\Lattice;

class  Lattice{

    protected $fontWidth=16;
    protected $fontHeight=16;
    protected $byteCount;
    protected $str;
    protected $charRowCount;
    protected $dot;
    protected $positions;
    protected $sections;
    protected $spreadSections;
    protected $out;

    public function __construct($str,$charRowCount=null)
    {
        $this->str = iconv("utf-8","gb2312//IGNORE", $str);
        $this->charRowCount = $charRowCount?$charRowCount:4;
        $this->init();
    }

    protected function init()
    {
        $this->byteCount=$this->fontWidth*$this->fontHeight/8;
    }

    protected function run()
    {
        $this->getDot();
        $this->getPositionsSections();
    }

    public function getResult()
    {
        if(!$this->positions){
            $this->run();
        }
        if(!$this->out){
            $this->out=new OutHtml();
        }
       return  $this->out->outAll( $this->positions, $this->sections , $this->spreadSections ,$this->charRowCount);

    }

    public function setOut(OutInterface $out)
    {
        $this->out=$out;
    }

    protected function getDot(){
        $dot='';
        $fontFileName =  __DIR__.'/HZK16';//字库名字
        $fp = fopen($fontFileName, "rb");
        $length=strlen($this->str);
        for ($i=0;$i<$length;$i++){

            if(ord($this->str[$i])<160){//非汉字
                $location=(ord($this->str{$i}) + 156-1) * $this->byteCount;
            }else {//汉字
                $qh = ord($this->str[$i]) - 32 - 128;//区码
                $wh = ord($this->str[++$i]) - 32 - 128;//位码
                $location = (94 * ($qh - 1) + ($wh - 1)) * $this->byteCount; /* 计算汉字字模在文件中的位置 */
            }
            fseek($fp, $location, SEEK_SET);//定位到汉字或字母指针开始的地方
            $dot.= fread($fp, $this->byteCount);//读取32字节的长度，一个字节8位，一行依次放两个字节组成16*16的点阵
        }
        fclose($fp);
        return $this->dot=$dot;
    }
    protected function getPositionsSections(){

        $count= strlen($this->dot)/$this->byteCount;//多少个字
        $positions=[];
        $sections =[];
        $sectionCount=$count;

        for ($i=0;$i<$sectionCount;$i++){
            $sections[]=[];

        }

        $yHeight=(intval($count/$this->charRowCount)*16+16);
        $xWeight=16*$this->charRowCount;
        for ($i=0;$i<$yHeight;$i++){
            for ($j=0;$j<$xWeight;$j++){
                $positions []=[$j,$i];
                $x=ceil(($j+1)/16);
                $y=ceil(($i+1)/16);
                $y--;
                $x--;
                $sections[(($y)*$this->charRowCount+$x)][] = [$j,$i];

            }
        }

        for ($b=0;$b<$count;$b++){//每一个字占用的点阵
            $str = substr($this->dot,($b)*32,$this->byteCount);//第几个字
            $dot_string='';
            for ($c = 0; $c < $this->byteCount; $c++){
                $dot_string .= sprintf("%08b", ord($str[$c]));
                if ($c % 2 == 1) {

                    for($a=0;$a<strlen($dot_string);$a++){
                        if($dot_string[$a]){//和平面坐标系关联起来
                            $sections[$b][intval(16*floor($c/2)+$a)][]=1;
                        }
                    }
                    $dot_string = '';
                }
            }
        }
        $spreadSections=[];//每一个字块的的点展开到数组中
        foreach ($sections as $section){
            $spreadSections  = array_merge($spreadSections,$section);
        }
        $this->positions = $positions;
        $this->sections = $sections;
        $this->spreadSections = $spreadSections;

    }


}