<div class=form-horizontal>
        <form name='pp' action='bartlby_action.php' method=POST>
        <?
              
                echo $layout->FormBox(
                                array(
                                        0=>"State",
                                        1=>$plcs[state_dropdown]
                                        )
                        ,true);
      
                   echo $layout->FormBox(
                                array(
                                        0=>"Text",
                                        1=>$layout->Field("action", "hidden", "submit_passive") .                     $layout->Field("service_id", "hidden", $plcs[service][service_id]) .
                                        $layout->Field("passive_text", "text") . $layout->Field("Submit", "submit", "Store") . "<br><i>state is not permanent, next intervall may set the true value again</i>"
                                        )
                        ,true);
        ?>
        </form>
</div>

