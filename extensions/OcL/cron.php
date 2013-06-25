<?
// php automated.php \
//   	username=admin \
//   	password=password \
//   	script=AutoReports/cron.php 
//		wich=daily

//Pear install Mail_Mime Net_SMTP


$c = new Color();


// white() === fg('white')
	ini_set('implicit_flush', '1');
	ini_set('display_errors', '1');
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	include "extensions/OcL/OcL.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("autoreports");
	$ocl = new OcL();


	$ct= time()-86400;
	$cc=time();
	$sql = "select id, strftime('%s', date_from) as dfrom, strftime('%s', date_to) as dto, all_day, color, worker_id, activity_level from schedule where (datetime(date_to, 'localtime')  >= '" .  date("Y-m-d H:i", $cc) . "' and datetime(date_from, 'localtime')  <= '" .  date("Y-m-d H:i", $cc) . "') or (all_day = 1 and datetime(date_from, 'localtime') like '" . date("Y-m-d") . "%')";
	
	$r = $ocl->db_schedule->query($sql);

	$wrk_states=array();
	$btl->worker_list_loop(function($wrk, $shm) use(&$c, &$ocl, &$btl, &$wrk_states) {
			if(@in_array($wrk[worker_id], $ocl->managed_users)) {
				$wrk_states[$shm][current]=$wrk[active];

                
			}
	});
    
	foreach($r as $row) {
		
		if((int)$row[activity_level] == 1) {
			$btl->worker_list_loop(function($wrk, $shm) use(&$c, &$row, &$btl, &$wrk_states) {
				if((int)$row[worker_id] == $wrk[worker_id]) {
					$wrk_states[$shm]["new"]=1;
				}
			});
			
		}
		if((int)$row[activity_level] == 2) {
			//bartlby_set_worker_state($btl->RES, $row[worker_id] ,2);
			$btl->worker_list_loop(function($wrk, $shm) use(&$c, &$row, &$btl, &$wrk_states) {
				if((int)$row[worker_id] == $wrk[worker_id]) {
					$wrk_states[$shm]["new"]=2;
				}
			});
			
		}
	}

while(list($k, $v) = @each($wrk_states)) {

    $ws = $v;
    

    $wrk=bartlby_get_worker($btl->RES, $k);

      if(!$ws["new"]) {
        if($ws[current] != 0) {
            echo $c("Deactivating user " . $wrk[name] . PHP_EOL)->red()->bold();
            bartlby_set_worker_state($btl->RES, $k, 0);
            $ocl->trigger($wrk, "You have set to be DEACTIVATED");
        }       
    } else {
        if($ws["new"] != $ws[current]) {
            if($ws["new"] == 1) {
                echo $c("Set User  " . $wrk[name] . " to ACTIVE" . PHP_EOL)->green()->bold();
                bartlby_set_worker_state($btl->RES, $k, 1);
                $ocl->fire_trigger($wrk, "You have set to be ACTIVE");
            }
            if($ws["new"] == 2 ) {
                echo $c("Set User  " . $wrk[name] . " to STANDBY" . PHP_EOL)->blue()->bold();
                bartlby_set_worker_state($btl->RES, $k, 2);
                $ocl->fire_trigger($wrk, "You have set to be STANDBY");
            }
        }    
    }

}



class Color
{
    const FORMAT_PATTERN = '#<([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)>(.*?)</\\1?>#s';
    // http://www.php.net/manual/en/functions.user-defined.php
    const STYLE_NAME_PATTERN = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';

    const ESC = "\033[";
    const ESC_SEQ_PATTERN = "\033[%sm";

    protected $initial = '';
    protected $wrapped = '';
    // italic and blink may not work depending of your terminal
    protected $styles = array(
        'reset'            => '0',
        'bold'             => '1',
        'dark'             => '2',
        'italic'           => '3',
        'underline'        => '4',
        'blink'            => '5',
        'reverse'          => '7',
        'concealed'        => '8',

        'default'          => '39',
        'black'            => '30',
        'red'              => '31',
        'green'            => '32',
        'yellow'           => '33',
        'blue'             => '34',
        'magenta'          => '35',
        'cyan'             => '36',
        'light_gray'       => '37',

        'dark_gray'        => '90',
        'light_red'        => '91',
        'light_green'      => '92',
        'light_yellow'     => '93',
        'light_blue'       => '94',
        'light_magenta'    => '95',
        'light_cyan'       => '96',
        'white'            => '97',

        'bg_default'       => '49',
        'bg_black'         => '40',
        'bg_red'           => '41',
        'bg_green'         => '42',
        'bg_yellow'        => '43',
        'bg_blue'          => '44',
        'bg_magenta'       => '45',
        'bg_cyan'          => '46',
        'bg_light_gray'    => '47',

        'bg_dark_gray'     => '100',
        'bg_light_red'     => '101',
        'bg_light_green'   => '102',
        'bg_light_yellow'  => '103',
        'bg_light_blue'    => '104',
        'bg_light_magenta' => '105',
        'bg_light_cyan'    => '106',
        'bg_white'         => '107',
    );
    protected $userStyles = array();
    protected $isStyleForced = false;

    public function __construct($string = '')
    {
        $this->setInternalState($string);
    }

    public function __invoke($string)
    {
        return $this->setInternalState($string);
    }

    public function __call($method, $args)
    {
        if (count($args) >= 1) {
            return $this->apply($method, $args[0]);
        }

        return $this->apply($method);
    }

    public function __get($name)
    {
        return $this->apply($name);
    }

    public function __toString()
    {
        return $this->wrapped;
    }

    public function setForceStyle($force)
    {
        $this->isStyleForced = (bool) $force;
    }

    public function isStyleForced()
    {
        return $this->isStyleForced;
    }

    /**
     * https://github.com/symfony/Console/blob/master/Output/StreamOutput.php#L93-112
     * @codeCoverageIgnore
     */
    public function isSupported()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return false !== getenv('ANSICON');
        }

        return function_exists('posix_isatty') && @posix_isatty(STDOUT);
    }

    /**
     * @codeCoverageIgnore
     */
    public function are256ColorsSupported()
    {
        return DIRECTORY_SEPARATOR === '/' && false !== strpos(getenv('TERM'), '256color');
    }

    protected function setInternalState($string)
    {
        $this->initial = $this->wrapped = (string) $string;
        return $this;
    }

    protected function stylize($style, $text)
    {
        if (!$this->shouldStylize()) {
            return $text;
        }

        $style = strtolower($style);

        if ($this->isUserStyleExists($style)) {
            return $this->applyUserStyle($style, $text);
        }

        if ($this->isStyleExists($style)) {
            return $this->applyStyle($style, $text);
        }

        if (preg_match('/^((?:bg_)?)color\[([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\]$/', $style, $matches)) {
            $option = $matches[1] == 'bg_' ? 48 : 38;
            return $this->buildEscSeq("{$option};5;{$matches[2]}") . $text . $this->buildEscSeq($this->styles['reset']);
        }

        //throw new NoStyleFoundException("Invalid style $style");
    }

    protected function shouldStylize()
    {
        return $this->isStyleForced() || $this->isSupported();
    }

    protected function isStyleExists($style)
    {
        return array_key_exists($style, $this->styles);
    }

    protected function applyStyle($style, $text)
    {
        return $this->buildEscSeq($this->styles[$style]) . $text . $this->buildEscSeq($this->styles['reset']);
    }

    protected function buildEscSeq($style)
    {
        return sprintf(self::ESC_SEQ_PATTERN, $style);
    }

    protected function isUserStyleExists($style)
    {
        return array_key_exists($style, $this->userStyles);
    }

    protected function applyUserStyle($userStyle, $text)
    {
        $styles = (array) $this->userStyles[$userStyle];

        foreach ($styles as $style) {
            $text = $this->stylize($style, $text);
        }

        return $text;
    }

    public function apply($style, $text = null)
    {
        if ($text === null) {
            $this->wrapped = $this->stylize($style, $this->wrapped);
            return $this;
        }

        return $this->stylize($style, $text);
    }

    public function fg($color, $text = null)
    {
        return $this->apply($color, $text);
    }

    public function bg($color, $text = null)
    {
        return $this->apply('bg_' . $color, $text);
    }

    public function highlight($color, $text = null)
    {
        return $this->bg($color, $text);
    }

    public function reset()
    {
        $this->wrapped = $this->initial;
        return $this;
    }

    public function center($width = 80, $text = null)
    {
        if ($text === null) {
            $text = $this->wrapped;
        }

        $centered = '';
        foreach (explode(PHP_EOL, $text) as $line) {
            $line = trim($line);
            $lineWidth = strlen($line) - mb_strlen($line, 'UTF-8') + $width;
            $centered .= str_pad($line, $lineWidth, ' ', STR_PAD_BOTH) . PHP_EOL;
        }

        $this->setInternalState(trim($centered, PHP_EOL));
        return $this;
    }

    protected function stripColors($text)
    {
        return preg_replace('/' . preg_quote(self::ESC) . '\d+m/', '', $text);
    }

    public function clean($text = null)
    {
        if ($text === null) {
            $this->wrapped = $this->stripColors($this->wrapped);
            return $this;
        }

        return $this->stripColors($text);
    }

    public function strip($text = null)
    {
        return $this->clean($text);
    }

    public function isAValidStyleName($name)
    {
        return preg_match(self::STYLE_NAME_PATTERN, $name);
    }

    /**
     * @deprecated
     */
    public function setTheme(array $theme)
    {
        return $this->setUserStyles($theme);
    }

    public function setUserStyles(array $userStyles)
    {
        foreach ($userStyles as $name => $styles) {
            if (!$this->isAValidStyleName($name)) {
                //throw new InvalidStyleNameException("$name is not a valid style name");
            }
        }

        $this->userStyles = $userStyles;
        return $this;
    }

    protected function colorizeText($text)
    {
        return preg_replace_callback(self::FORMAT_PATTERN, array($this, 'replaceStyle'), $text);
    }

    /**
     * https://github.com/symfony/Console/blob/master/Formatter/OutputFormatter.php#L124-162
     */
    public function colorize($text = null)
    {
        if ($text === null) {
            $this->wrapped = $this->colorizeText($this->wrapped);
            return $this;
        }

        return $this->colorizeText($text);
    }

    protected function replaceStyle($matches)
    {
        return $this->apply($matches[1], $this->colorize($matches[2]));
    }
}