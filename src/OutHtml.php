<?php


namespace Jc91715\Lattice;


class OutHtml implements OutInterface
{
    public function outAll($positions,$sections,$spreadSections,$charRowCount)
    {
        $str="<table border='1' width='100%' style='text-align: center'>";
        foreach (array_chunk($positions,16*$charRowCount) as $row){

            $str.=$this->outRow($row,$spreadSections);
        }
        $str .= "</table>";
        return $str;
    }

    public function outRow($row,$spreadSections)
    {
        $str="<tr>";
        foreach ($row as $td) {
            if (!in_array($td,$spreadSections)){//不在平面坐标系中说明这个位置是一个点
                $str .= "<td style='color: white;background-color: red;'>O</td>";
            }else {
                $str .= "<td>O</td>";
            }
        }
        $str.="<tr>";
        return $str;
    }
}