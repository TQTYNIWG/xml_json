<?php
$xml=simplexml_load_file('demo.xml');
$json=sub_json($xml,1);
var_dump($json);

/*
 * @$state=true display array structure
*/

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

/*
 *  Next (^_^). The annotations I say may be a little difficult to understand.
 *  Please combine programming thinking to understand. Thank you!
*/

function splicing_json($xml){   //$xml Is an object similar to the structure of multidimensional arrays.
    $str='';
    foreach($xml->children() as $child)     //->children() It is not necessary in this code. Want to know its details goole PHP SimpleXML or children ();
    {
        if(count($child)===0){      //Key parts. The length of this object is judged here, and its role is to get the structure similar to one dimensional array.array('name'=>,'j.doe',......);
            $str .= '"'.$child->getName().'":"'.$child.'",';    //The spliced JSON format ->getName () basically gets the node name, similar to array key value. Want to know its own goole SimpleXML getName ()
        }else if(count($child)>0){  //Key parts. The length of this object is judged here, and its role is to obtain similar multidimensional arrays.array(array('name'=>'j.doe'),'json'=>array('j'=>'1'),......);
            $str .= '"' . $child->getName() . '":{';    //Important part. Gets the result of the multidimensional array: "name": {This section eventually joins with the last $str to form: "name": {...}
            foreach($child->children() as $val){    //I won't go on because it repeats the above annotation, because it repeats the structure, we can use recursion to handle this
                if(count($val)>0) {
                    $str .='"' . $val->getName() . '":{'.splicing_json($val).'},';  //splicing_json（）Loop itself. Loop until you get all subobjects.
                }else if(count($val)===0){
                    $str .= '"' . $val->getName() . '":"'.$val.'",';
                }
            }
            $str .= '},';   //Important part. Gets the result of the multidimensional array: "name": {This section eventually joins with the last $str to form: "name": {...}
        }
    }
    return $str;
}

