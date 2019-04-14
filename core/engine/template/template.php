<?php

class TemplateEngine {

    public static $content = [];

    public function render($fileContent, $content=null) {

        self::$content = $content;

        $fcontent = TemplateEngine::editFile($fileContent, $content);

        # echo $fcontent; exit;

        $fcontent = eval("?>". $fcontent ."<?php");
        return $fcontent;
    }


    public function basic($fcontent) {
        $fcontent = preg_replace("/@\{\{(.*)\}\}/", '<?php echo $1; ?>',$fcontent);
        $fcontent = preg_replace("/@\{\{(.*)\"(.*)\"(.*)\}\}/", '<?php echo "$2"; ?>',$fcontent);
        
        return $fcontent;
    }


    public function extendLayout($fcontent, $viewPath, $ext) {

        if(str_replace("@{extends","",$fcontent)) {

            $layout = substr($fcontent, strpos($fcontent,"@{extends "), strpos($fcontent, "}"));
            $layout = str_replace("@{extends \"","",$layout);
            $cleaned = str_replace("\"","",trim($layout," "));
            $layoutPath = $viewPath."/".$cleaned."."."$ext";

            $layout = file_get_contents($layoutPath);

            $fcontent = preg_replace("/@\{extends (.*)\}/","",$fcontent);
            
            $layout = preg_replace("/@\{(.*)content(.*)\}/", $fcontent, $layout);
            
            return $layout;
        }

        return $fcontent;
  
    }

  
    public function editFile($fileContent, $content=null){
        
        $fcontent = $fileContent;

        $fcontent = preg_replace("/\{\% load (.*) \%\}/",'<?php echo TemplateEngine::loadFile("views/".($1).".fish.php", $content); ?>',$fcontent);
        
        $fcontent = TemplateEngine::basic($fcontent);
        $fcontent = TemplateEngine::sessions($fcontent);
        
        # conditional statements
        if(is_array($content)){
            
            $fcontent = TemplateEngine::conditionalStatement($fcontent, $content);
            $fcontent = TemplateEngine::keys($fcontent, $content);
            $fcontent = TemplateEngine::forLoops($fcontent);

        } else {
            $fcontent = TemplateEngine::emptyParameter($fcontent);
        }

        return $fcontent;
    }


    static function loadFile($filename, $content){
        $fcontent = file_get_contents($filename);

        $fcontent = TemplateEngine::basic($fcontent);
        $fcontent = TemplateEngine::sessions($fcontent);
        $fcontent = TemplateEngine::keys($fcontent, $content);
        $fcontent = TemplateEngine::forLoops($fcontent);
        $fcontent = TemplateEngine::conditionalStatement($fcontent, $content);
  
        $fcontent = eval("?>". $fcontent ."");
        return $fcontent;
    }

    static function conditionalStatement($fcontent, $content=null){
        $fcontent = preg_replace("/\{\% if (.*) \%\}/", '<?php if(array_key_exists(("$1"),$content) && $content[("$1")]) { ?>',$fcontent);
        $fcontent = preg_replace("/\{\%(.*)elif (.*) (.*)\%\}/", 
            '<?php } else if(array_key_exists(("$2"),$content) && !$content[("$2")]) { ?>',
        $fcontent);
        $fcontent = preg_replace("/\{\%(.*)else(.*)\%\}/", '<?php } else { ?>',$fcontent);
        $fcontent = preg_replace("/\{\%(.*)endif(.*)\%\}/", '<?php } ?>',$fcontent);

        return $fcontent;
    }

    static function emptyParameter($fcontent){
            $fcontent = preg_replace("/\{\%(.*)if (.*) \%\}/", '<?php function nocall() { ?>',$fcontent);
            $fcontent = preg_replace("/\{\%(.*)else(.*)\%\}/", '<?php "" ?>',$fcontent);
            $fcontent = preg_replace("/\{\{(.*)\}\}/", '<?php "" ?>',$fcontent);
            $fcontent = preg_replace("/\{\%(.*)endif(.*)\%\}/", '<?php } ?>',$fcontent);


        return $fcontent;
    }

    static function keys($fcontent, $content=null) {
        if(!is_null($content)){
            foreach ($content as $key => $value) {
                if(!is_array($value)){
                    $fcontent = preg_replace("/\{\{ ".$key." \}\}/", $value, $fcontent);
                } 
                
                if(is_array($value)) {
                    foreach ($value as $k => $v) {
                        if(!is_array($v)){
                            $fcontent = preg_replace("/\{\{ ".$key."\.".$k." \}\}/", $v, $fcontent);
                        }
                    }
                } 
            }
        }

        return $fcontent;
    }

    static function forLoops($fcontent){
        $fcontent = preg_replace("/\{\% foreach (.*) as key \| value \%\}/", 
            '<?php if(array_key_exists("$1", $content)) {
                foreach($content[("$1")] as $key => $value) { ?>'
        ,$fcontent);

        $fcontent = preg_replace("/\{\{ key \}\}/", '<?php echo $key; ?>',$fcontent);
        $fcontent = preg_replace("/\{\{ value \}\}/", '<?php echo $value; ?>',$fcontent);
        $fcontent = preg_replace("/\{\% endforeach \%\}/", '<?php } } ?>',$fcontent);

        $fcontent = preg_replace("/\{\% loop (.*) \%\}/",
            '<?php  if(array_key_exists("$1", $content)) {
                    for($i = 0; $i < count($content["$1"]); $i++) { ?>'
        ,$fcontent);
        
        $fcontent = preg_replace("/\{\{ \[index\+\+\] \}\}/", '<?php echo $i + 1; ?>',$fcontent);
        $fcontent = preg_replace("/\{\{ \[index\] \}\}/",'<?php echo $i; ?>',$fcontent);
        $fcontent = preg_replace("/\{\{ (.*)\[index\] \}\}(.*)\{\{/", '<?php echo $content["$1"][$i]; ?>$2{{',$fcontent);
        $fcontent = preg_replace("/\{\{ (.*)\[index\]\.(.*) \}\}(.*)\{\{/", '<?php echo $content["$1"][$i]["$2"]; ?>$3{{',$fcontent);
        $fcontent = preg_replace("/\{\{ (.*)\[index\]\.(.*) \}\}(.*)\{/", '<?php echo $content["$1"][$i]["$2"]; ?>/{{',$fcontent);
        $fcontent = preg_replace("/\{\{ (.*)\[index\]\.(.*) \}\}/", '<?php echo $content["$1"][$i]["$2"]; ?>',$fcontent);    
        $fcontent = preg_replace("/\{\{ (.*)\[index\] \}\}/",'<?php echo $content["$1"][$i]; ?>',$fcontent);
        $fcontent = preg_replace("/\{\% endloop \%\}/", '<?php } } ?>',$fcontent);

        
        return $fcontent;

    }

    static function sessions($fcontent){
        
        foreach($_SESSION as $key => $value) {

            if(!is_array($value)){
                $fcontent = preg_replace("/\{\{(.*)".$key."(.*)\}\}/", $value, $fcontent);
            } 

            if(is_array($value)){
                foreach ($value as $k => $v) {
                    # code...
                    $fcontent = preg_replace("/\{\{(.*)".$key."\.".$k."(.*)\}\}/", $v, $fcontent);
                }
            }
        }
        

        return $fcontent;
    }

}