<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* admin‎/view/template/common/footer.twig */
class __TwigTemplate_751718d104b45f950ec42d7788533c52e16b3ca6931901a94ba1eb2738acee08 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<footer id=\"footer\">";
        echo ($context["text_footer"] ?? null);
        echo "<br />";
        echo ($context["text_version"] ?? null);
        echo "</footer></div>
<script src=\"";
        // line 2
        echo ($context["bootstrap"] ?? null);
        echo "\" type=\"text/javascript\"></script>
</body></html>
";
    }

    public function getTemplateName()
    {
        return "admin‎/view/template/common/footer.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  44 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "admin‎/view/template/common/footer.twig", "C:\\wamp64\\www\\opencart4\\upload\\admin‎\\view\\template\\common\\footer.twig");
    }
}
