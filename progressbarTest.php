<?php

	/**
	* 
	*/
	class progressbarTest extends PHPUnit_Framework_TestCase
	{
		
		function test_constructor()
		{
			$progressbar=new ProgressBar();
			$this->assertEquals("pb",$progressbar->pbid);
			$this->assertEquals("progress-bar",$progressbar->pbarid);
			$this->assertEquals("transparent-bar",$progressbar->tbarid);
			$this->assertEquals("pb_text", $progressbar->textid);
			$this->assertEquals(0,$progressbar->percentDone);
			return $progressbar;
		}
		/**
		*@depends test_constructor
		**/
		function test_getContent(ProgressBar $progressbar)
		{
             $progressbar->percentDone = floatval($progressbar->percentDone);
             $percentDone = number_format($progressbar->percentDone, $progressbar->decimals, '.', '') .'%';
			 $result='<div id="'.$progressbar->pbid.'" class="pb_container">
                        <div id="'.$progressbar->textid.'" class="'.$progressbar->textid.'">'.$percentDone.'</div>
                        <div class="pb_bar">
                                <div id="'.$progressbar->pbarid.'" class="pb_before"
                                style="width: '.$percentDone.';"></div>
                                <div id="'.$progressbar->tbarid.'" class="pb_after"></div>
                        </div>
                        <br style="height: 1px; font-size: 1px;"/>
                </div>
                <style>
                        .pb_container {
                                position: relative;
                        }
                        .pb_bar {
                                width: 100%;
                                height: 1.3em;
                                border: 1px solid silver;
                                -moz-border-radius-topleft: 5px;
                                -moz-border-radius-topright: 5px;
                                -moz-border-radius-bottomleft: 5px;
                                -moz-border-radius-bottomright: 5px;
                                -webkit-border-top-left-radius: 5px;
                                -webkit-border-top-right-radius: 5px;
                                -webkit-border-bottom-left-radius: 5px;
                                -webkit-border-bottom-right-radius: 5px;
                        }
                        .pb_before {
                                float: left;
                                height: 1.3em;
                                background-color: #43b6df;
                                -moz-border-radius-topleft: 5px;
                                -moz-border-radius-bottomleft: 5px;
                                -webkit-border-top-left-radius: 5px;
                                -webkit-border-bottom-left-radius: 5px;
                        }
                        .pb_after {
                                float: left;
                                background-color: #FEFEFE;
                                -moz-border-radius-topright: 5px;
                                -moz-border-radius-bottomright: 5px;
                                -webkit-border-top-right-radius: 5px;
                                -webkit-border-bottom-right-radius: 5px;
                        }
                        .pb_text {
                                padding-top: 0.1em;
                                position: absolute;
                                left: 48%;
                        }
                </style>'."\r\n";
        //$this->assertEquals($result,$progressbar->getContent());
	}
	/**
	*@depends test_constructor
	**/
	function test_setProgressBarProgress(ProgressBar $progressbar)
	{

		$output='
                <script type="text/javascript">
                if (document.getElementById("'.$progressbar->pbarid.'")) {
                        document.getElementById("'.$progressbar->pbarid.'").style.width = "'.'25'.'%";'.
                        'document.getElementById("'.$progressbar->tbarid.'").style.width = "'.'75'.'%";'.
						'document.getElementById("pb_text").innerHTML = "25%";'.
                        '}</script>'."\n\n";
		//$this->expectOutputString($output);
		$progressbar->setProgressBarProgress(25);
		$this->assertEquals(25,$progressbar->percentDone);	
	}
}
?>
