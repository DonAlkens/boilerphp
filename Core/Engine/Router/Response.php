<?php 

use App\Config\ViewsConfig;


function responseHeader($status) {

    switch($status) {
        case 200: 
            header("HTTP/1.1 200 Ok!");
            break;
        case 400:
            header("HTTP/1.1 404 Not Found");
            break;
        case 500:
            header("HTTP/1.1 500 Server Error");
            break;
        case 301:
            header("HTTP/1.1 301 Redirected");
            break;
        default: 
            header("HTTP/1.1 200 Ok!");
            break;
    }
}

function get_view_path($filename) 
{

    $view_path = ViewsConfig::$views_path;

    $extension = "fish.html";
    $full_path = $view_path."/".$filename.".".$extension;

    return [ 
        "fullpath" => $full_path, 
        "viewpath" => $view_path,
        "extension" => $extension
    ];
}

function view($view_file, $content=null) 
{

    $path = get_view_path($view_file);

    $full_path = $path["fullpath"]; 
    $view_path = $path["viewpath"];
    $extension = $path["extension"];

    if(file_exists($full_path)) 
    {
        $fcontent = file_get_contents($full_path);

        $template = new TemplateEngine($extension);
        $fcontent = $template->extendLayout($fcontent, $view_path, $extension);
        $fcontent = $template->render($fcontent, $content);      
        return $template;
    } 
    else 
    {
        throw new Error($view_file." does not exists");
    }

}

function content($text)
{
    echo $text;
}

function Json($content, $status = 200)
{
    header('Content-Type: application/json; charset=utf-8');
    responseHeader($status);
    echo json_encode($content);
}

function redirect($location) 
{
    $location = trim($location,"/");
    $location = "/".$location;
    return header("location:".$location);
}

function redirectToHost($location) 
{
    return header("location:".$location);
}

function unhandledPost() 
{
    return view("core/errors/unhadledPost");
}

function error404()
{
    responseHeader(400);
    return view("errors/404");
}

function mailPage($view_file, $data = null) 
{

    $path = get_view_path($view_file);

    $full_path = $path["fullpath"]; 
    $view_path = $path["viewpath"];
    $extension = $path["extension"];

    if(file_exists($full_path)) 
    {
        $fcontent = file_get_contents($full_path);

        $template = new TemplateEngine($extension);
        $fcontent = $template->extendLayout($fcontent, $view_path, $extension);
        $fcontent = $template->content($fcontent, $data);      
        return $fcontent;
    } 
    else 
    {
        throw new Error($view_file." does not exists");
    }

}
