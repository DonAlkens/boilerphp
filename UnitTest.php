<?php

include "core/engine/template/template.php";


$file = "logic{{ 2 + 2 + 2 }}";
$f2 = '{{"boy and girl"}}';
echo TemplateEngine::basic($f2);