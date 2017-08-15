<?php

/* index/index.phtml */
class __TwigTemplate_115575fe940fc3c3cc4a25c7da62d7f2becf5a68ebcba7482487555bd7caf8f8 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!doctype html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Document</title>


</head>
<body>
<div id=\"container\">
    <?php
    echo \$content, \" I am \", \$name;
    ?>
</div>

</body>
</html>

";
    }

    public function getTemplateName()
    {
        return "index/index.phtml";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<!doctype html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Document</title>


</head>
<body>
<div id=\"container\">
    <?php
    echo \$content, \" I am \", \$name;
    ?>
</div>

</body>
</html>

", "index/index.phtml", "/iamseeData/webspace/truesign/apps/demo/application/views/index/index.phtml");
    }
}
