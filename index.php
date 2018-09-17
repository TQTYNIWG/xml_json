<?php
$xml=simplexml_load_file('demo.xml');
var_dump(sub_json($xml,1));

function sub_json($xml=NULL,$state=false){
    if(!empty($xml)){
        $json=splicing_json($xml);
        $sub_json='{'.substr($json,0,strlen($json)-1).'}';
        $str_json=str_replace(',}','}',$sub_json);
        if($state){
            $str_json=json_decode($str_json,1);
        }
        return $str_json;
    }
}


function splicing_json($xml){
    $str='';
    foreach($xml->children() as $child)
    {
        if(count($child)===0){
            $str .= '"'.$child->getName().'":"'.$child.'",';
        }else if(count($child)>0){
            $str .= '"' . $child->getName() . '":{';
            foreach($child->children() as $val){
                if(count($val)>0) {
                    $str .='"' . $val->getName() . '":{'.splicing_json($val).'},';
                }else if(count($val)===0){
                    $str .= '"' . $val->getName() . '":"'.$val.'",';
                }
            }
            $str .= '},';
        }
    }
    return $str;
}