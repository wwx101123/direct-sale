<?php
/*
 *  日志记录
 *  @author winsen
 *  @version 1.0.0
 */
class Logs
{
    protected $handler;
    protected $debug;

    public function __construct($debug = true, $filename = 'log.log')
    {
        if(!dir('logs'))
        {
            mkdir('logs');
        }

        $filename = 'logs/'.$filename;
        if(!file_exists($filename))
        {
            $tmp = fopen($filename, 'x');
            fclose($tmp);
            chmod($filename, 0644);
        }

        $this->handler = fopen($filename, 'a');
        $this->debug = $debug;
    }

    public function record_array($array)
    {
        if(!$this->debug) return;

        $message = json_encode($array);

        $formated = '['.date('Y-m-d H:i:s').'] %s'."\n";
        $message = sprintf($formated, $message);

        fwrite($this->handler, $message);
    }

    public function record($message, $format = true)
    {
        if(!$this->debug) return;

        $formated = '['.date('Y-m-d H:i:s').'] %s'."\n";
        $message = sprintf($formated, $message);

        fwrite($this->handler, $message);
    }

    public function __destruct()
    {
        if($this->handler)
        {
            fclose($this->handler);
        }
    }
}
