<?php

namespace WPML\Core;

use \WPML\Core\Twig\Environment;
use \WPML\Core\Twig\Error\LoaderError;
use \WPML\Core\Twig\Error\RuntimeError;
use \WPML\Core\Twig\Markup;
use \WPML\Core\Twig\Sandbox\SecurityError;
use \WPML\Core\Twig\Sandbox\SecurityNotAllowedTagError;
use \WPML\Core\Twig\Sandbox\SecurityNotAllowedFilterError;
use \WPML\Core\Twig\Sandbox\SecurityNotAllowedFunctionError;
use \WPML\Core\Twig\Source;
use \WPML\Core\Twig\Template;

/* layout-slot-edit-button.twig */
class __TwigTemplate_91a7190692204ab891ce71173480375b13001fc0ec8a4a71f727983aa2ced06e extends \WPML\Core\Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        ob_start(function () { return ''; });
        // line 2
        if ((($context["action"] ?? null) == "edit")) {
            // line 3
            echo "\t";
            $context["class"] = "otgs-ico-edit";
        } else {
            // line 5
            echo "\t";
            $context["class"] = "otgs-ico-add";
        }
        // line 7
        echo "
<p class=\"wpml-ls-slot-management-link-wrapper\">
\t<a href=\"";
        // line 9
        echo \WPML\Core\twig_escape_filter($this->env, ($context["url"] ?? null), "html", null, true);
        echo "\" class=\"js-wpml-ls-slot-management-link button-secondary\">
\t\t<span class=\"";
        // line 10
        echo \WPML\Core\twig_escape_filter($this->env, ($context["class"] ?? null), "html", null, true);
        echo "\"></span> ";
        echo \WPML\Core\twig_escape_filter($this->env, ($context["label"] ?? null), "html", null, true);
        echo "</a>
</p>
";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    public function getTemplateName()
    {
        return "layout-slot-edit-button.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  52 => 10,  48 => 9,  44 => 7,  40 => 5,  36 => 3,  34 => 2,  32 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "layout-slot-edit-button.twig", "/home/u651353481/domains/explorelogicsit.net/public_html/bestworldvision/wp-content/plugins/sitepress-multilingual-cms/templates/language-switcher-admin-ui/layout-slot-edit-button.twig");
    }
}
