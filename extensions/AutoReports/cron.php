<?
// php automated.php \
//   	username=admin \
//   	password=password \
//   	script=AutoReports/cron.php 
//		wich=daily

//Pear install Mail_Mime Net_SMTP


$c = new Color();

$_GLO[debug_commands]=true;

// highlight('green') === bg('green') === bg_green()
// white() === fg('white')
	ini_set('implicit_flush', '1');
	ini_set('display_errors', '1');
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	include "Mail.php";
	include "Mail/mime.php";
	include "extensions/AutoReports/AutoReports.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("autoreports");
	$ar = new AutoReports();
	
	$local_smtp_host=$ar->storage->load_key("local_smtp_host");
	$local_mail_from=$ar->storage->load_key("local_mail_from");
	
	switch($_GET[wich]) {
		case 'daily':
			$r = $ar->db->query("select * from autoreports where daily=1");	
			$d_from=date("d.m.Y", time()-86400);
			$d_to=date("d.m.Y", time()-86400);
		break;
		case 'weekly':
			$r = $ar->db->query("select * from autoreports where weekly=1");
		break;
		case 'monthly':
			$r = $ar->db->query("select * from autoreports where monthly=1");
		break;

	}

	$lstate=0;

	foreach($r as $row) {
			echo "sending report to:" . $row[receipient] . " \n";

			$svcel = explode("|", $row[service_var]);
			$rap = "";
			$rap .="<html><head><style>td{font-size:12px; font-family:tahoma}</style></head><body>";
			for($x=0; $x<count($svcel); $x++) {
				if($svcel[$x] == "") continue;
				$svcid_a=explode("=", $svcel[$x]);
				$svc_id=$svcid_a[0];

				
				$defaults=bartlby_get_service_by_id($btl->RES, $svc_id);
				$rap .= "<h1>Report for: " . $defaults[server_name] . "/" . $defaults[service_name] . "</h1>\n";
				$rep = $btl->do_report($d_from, $d_to, $lstate, $svc_id);		
				$rap .= format_report($rep, "html");
				
				$btl->updatePerfHandler($defaults[server_id], $svc_id);
				$path=bartlby_config($btl->CFG, "performance_rrd_htdocs");
				$rap .= "<h2>Graphs for " . $defaults[server_name] . "/" . $defaults[service_name] . "<h2>";
	            foreach(glob($path . "/" . $svc_id . "_*.png") as $fn) {
	            	
	            	$b64=base64_encode(file_get_contents($fn));
                	$rap .= '<img src="data:image/gif;base64,' . $b64 . '">';
		        }



			}
			$rap .= "</body></html>";
			
			$tmpfname = tempnam ("/tmp", "AutoReports");

			$fp = fopen($tmpfname, "w");
			fwrite($fp, $rap);
			fclose($fp);

			copy($tmpfname, $tmpfname . ".html");
			unlink($tmpfname);

			$btl_sub="Bartlby Report for: ";
			$headers = array('From' =>  $ar->storage->load_key("local_mail_from") , 'To' => $row[receipient],
						   'Subject' => $btl_subj);


			$smtp = Mail::factory('smtp',
		                	array ('host' =>  $ar->storage->load_key("local_smtp_host"),
              		          		'auth' => false,
                        		   	'timeout' => 10,
                        			'debug' => false
                			));


			$mime = new Mail_Mime();
			$mime->setTxtBody("see the attachment for details");
			$mime->addAttachment($tmpfname . ".html", "text/html", "report.html");
			$mime->setHTMLBody($file);
	        $body=$mime->get();
			$hdrs=$mime->headers($headers);
			$mail = $smtp->send($row[receipient], $hdrs, $body);
			$c("Sent Report to " . $row[receipient] . " with " . $x . " Services" . PHP_EOL)->green->bold;

	}


function format_report($rep, $type='html', $hdr) {
		global $btl;



		$svc=$rep[svc];
		$state_array=$rep[state_array];
		$notify=$rep[notify];
		$files_scanned=$rep[files_scanned];

		$hun=$svc[0]+$svc[1]+$svc[2];




		switch($type) {
			case 'html':
				
				$rap .= "<table width=100% border=3>";
			break;	
		}
		switch($type) {
			case 'html':
				$rap .= "<tr><td colspan=3>" . $hdr . "</td></tr>";
			break;	
		}

		switch($type) {
			case 'html':
				$rap .= "<tr><td colspan=3><b>Service Availability</b></td></tr>";
			break;	
		}

		while(list($state, $time) = @each($svc)) {


			$perc =   (($hun-$time) * 100 / $hun);
			$perc =100-$perc;


			switch($type) {
				case 'html':
					$rap .= "<tr>";
					$rap .= "<td>";
					$rap .= "<font color=" . $btl->getColor($state) . ">" . $btl->getState($state) . "</font>";
					$rap .= "</td>";
					$rap .= "<td>";
					$rap .= $btl->intervall($time);
					$rap .= "</td>";
					$rap .= "<td>";
					$rap .= round($perc,2);
					$rap .= "%</td>";
					$rap .= "</tr>";
				break;	
			}




		}

		switch($type) {
			case 'html':
				$rap .= "<tr><td colspan=3><b>Notifications</b></td></tr>";
			break;	
		}
		while(list($worker, $dd) = @each($notify)) {

			switch($type) {
				case 'html':
					$rap .= "<tr><td colspan=2>" . $worker . "</td><td>";
				break;	
			}


			while(list($trigger, $dd1) = @each($dd)) {
				$rap .= "\t" . $trigger . "<br>";
				while(list($k, $ts) = @each($dd1)) {

					switch($type) {
						case 'html':
							$rap .= "<li>" . date("d.m.Y H:i:s", $ts[0]) . " (" . $btl->getState($ts[1]) . ")<br>";
						break;	
					}
				}
			}
			switch($type) {
				case 'html':
					$rap .= "</td></tr>";
				break;	
			}

		}
		switch($type) {
			case 'html':
				$rap .= "<tr><td colspan=3><b>Output</b></td></tr>";
			break;	
		}
		for($xy=0; $xy<count($state_array);$xy++) {
				switch($type) {
					case 'html':
						$o1 .= "<tr>";
						$o1 .= "<td>" . date("d.m.Y H:i:s", $state_array[$xy][end]) . "</td>";
						$o1 .= "<td>" .  $btl->getState($state_array[$xy][lstate]) . " </td>";
						$o1 .= "<td>" . $state_array[$xy][msg] . " </td>";
						$o1 .= "</tr>";

					break;	
				}

		}

		$rap .= $o1;

		switch($type) {
			case 'html':
				$rap .= "</table></body></html>";
			break;	
		}

		return $rap;

}


//https://github.com/kevinlebrun/colors.php/blob/master/src/Colors/Color.php

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
?>
