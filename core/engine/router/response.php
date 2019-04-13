<?php 

function view($filename, $content=null) {

    include "./Config.php";

    $path = "./".$viewPath."/";
    $ext = $viewEngine.".".$extension;
    $filename = $path.$filename.".".$ext;


    if(file_exists($filename)) {
        $fcontent = file_get_contents($filename);

        $template = new TemplateEngine;
        $fcontent = $template->extendLayout($fcontent, $viewPath, $ext);
        $fcontent = $template->render($fcontent, $content);        

        return $fcontent;
    } 
    else {
        throw new Error($filename." does not exists");
    }

}

function Content($text){
    return $text;
}

function Json($content){
    return json_encode($content);
}

function Redirect($location) {
    $location = trim($location,"/");
    $location = "/".$location;
    return header("location:".$location);
}

function unhandledPost() {
    return file_get_contents("core/errors/unhadledPost.fish.php");
}