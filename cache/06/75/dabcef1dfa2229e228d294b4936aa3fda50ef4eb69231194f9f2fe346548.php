<?php

/* Shared/master.html.twig */
class __TwigTemplate_0675dabcef1dfa2229e228d294b4936aa3fda50ef4eb69231194f9f2fe346548 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"utf-8\">
    <title>Template &middot; Bootstrap</title>
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <meta name=\"description\" content=\"\">
    <meta name=\"author\" content=\"\">

    <link rel=\"stylesheet\" type=\"text/css\" href=\"/bootstrap/css/bootstrap.css\" />
    <link rel=\"stylesheet/less\" type=\"text/css\" href=\"/less/styles.less\" />
</head>

<body>

<div class=\"container\">

    <div class=\"masthead\">
        <h3 class=\"muted\">Project name</h3>
        <div class=\"navbar\">
            <div class=\"navbar-inner\">
                <div class=\"container\">
                    <ul class=\"nav\">
                        <li class=\"active\"><a href=\"/\">Home</a></li>
                        <li><a href=\"#\" title=\"TODO\">Projects</a></li>
                        <li><a href=\"#\" title=\"TODO\">Services</a></li>
                        <li><a href=\"#\" title=\"TODO\">Downloads</a></li>
                        <li><a href=\"#\" title=\"TODO\">About</a></li>
                        <li><a href=\"#\" title=\"TODO\">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div><!-- /.navbar -->
    </div>

    <div id=\"flash-messages\">
        ";
        // line 37
        if (array_key_exists("flash", $context)) {
            // line 38
            echo "            ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "flash"));
            foreach ($context['_seq'] as $context["type"] => $context["message"]) {
                // line 39
                echo "                <div class=\"alert alert-success\">";
                echo twig_escape_filter($this->env, $this->getContext($context, "message"), "html", null, true);
                echo "</div>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['type'], $context['message'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 41
            echo "        ";
        }
        // line 42
        echo "    </div>

    ";
        // line 44
        $this->displayBlock('content', $context, $blocks);
        // line 45
        echo "
    <hr>

    <div class=\"footer\">
        <p>&copy; Company 2013</p>
    </div>

</div> <!-- /container -->


<script src=\"//cdnjs.cloudflare.com/ajax/libs/less.js/1.3.3/less.min.js\" type=\"text/javascript\"></script>
<script src=\"//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js\"></script>
<script src=\"/js/holder.js\"></script>
<script src=\"/js/NoteForm.js\"></script>

";
        // line 60
        $this->displayBlock('javascript', $context, $blocks);
        // line 61
        echo "
</body>
</html>
";
    }

    // line 44
    public function block_content($context, array $blocks = array())
    {
    }

    // line 60
    public function block_javascript($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "Shared/master.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  115 => 60,  110 => 44,  103 => 61,  101 => 60,  84 => 45,  82 => 44,  78 => 42,  75 => 41,  66 => 39,  61 => 38,  59 => 37,  21 => 1,  105 => 35,  102 => 34,  97 => 31,  90 => 29,  77 => 28,  74 => 27,  56 => 26,  32 => 4,  29 => 3,);
    }
}
