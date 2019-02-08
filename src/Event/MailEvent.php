<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;

class MailEvent extends Event
{
    const SEND_MAIL = 'event.send.mail.connector';

    /**
     * @var string
     */
    protected $from = null;

    /**
     * @var string
     */
    protected $to = null;

    /**
     * @var string
     */
    protected $object = null;

    /**
     * @var string
     */
    protected $content = null;


    public function __construct($from, $to, $object, $content)
    {
        $this->from = $from;
        $this->to = $to;
        $this->object = $object;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }
	
	    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }
	
	/**
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param string $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }
	
	/**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}