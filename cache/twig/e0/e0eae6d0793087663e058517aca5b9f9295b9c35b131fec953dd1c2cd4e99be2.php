<?php

/* index/index.twig */
class __TwigTemplate_a0f501212f9827e6c89f73ee55dca938efc00a341a03e3eb1c08c78358ea7f5e extends Twig_Template
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
        echo twig_escape_filter($this->env, ($context["content"] ?? null), "html", null, true);
        echo "
<hr>
";
        // line 3
        echo twig_escape_filter($this->env, ($context["name"] ?? null), "html", null, true);
    }

    public function getTemplateName()
    {
        return "index/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  24 => 3,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{{ content }}
<hr>
{{ name }}", "index/index.twig", "/iamseeData/webspace/truesign/apps/demo/application/views/index/index.twig");
    }
}
