<?php

/* Home/index.html.twig */
class __TwigTemplate_61a8f95727604e8588803c9093a51780fe85f5cf389dfcc6f7d6580e4a23e063 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("Shared/master.html.twig");

        $this->blocks = array(
            'content' => array($this, 'block_content'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "Shared/master.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "    <div id=\"notes-container\">
    <form action=\"/add\" method=\"post\" class=\"form-horizontal\">
        <h3>Add a note</h3>
        <div class=\"control-group\">
            <label class=\"control-label\" for=\"note_title\">Title</label>
            <div class=\"controls\">
                <input type=\"text\" id=\"note_title\" name=\"note[title]\" placeholder=\"Title\">
            </div>
        </div>
        <div class=\"control-group\">
            <label class=\"control-label\" for=\"note_message\">Message</label>
            <div class=\"controls\">
                <textarea id=\"note_message\" name=\"note[message]\" placeholder=\"Message\"></textarea>
            </div>
        </div>
        <div class=\"control-group\">
            <div class=\"controls\">
                <button type=\"submit\" class=\"btn\">Add note</button>
            </div>
        </div>
    </form>
        <h3>Current notes</h3>
        ";
        // line 26
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "notes"));
        $context['_iterated'] = false;
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["note"]) {
            // line 27
            echo "            ";
            $this->env->loadTemplate("Home/_note.html.twig")->display($context);
            // line 28
            echo "        ";
            $context['_iterated'] = true;
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        if (!$context['_iterated']) {
            // line 29
            echo "            No notes! Add one using the form above.
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['note'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 31
        echo "    </div>
";
    }

    // line 34
    public function block_javascript($context, array $blocks = array())
    {
        // line 35
        echo "    <script>
        var note_form = new NoteForm('#notes-container');
        note_form.observe();
    </script>
";
    }

    public function getTemplateName()
    {
        return "Home/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  105 => 35,  102 => 34,  97 => 31,  90 => 29,  77 => 28,  74 => 27,  56 => 26,  32 => 4,  29 => 3,);
    }
}
