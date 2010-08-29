<?php
/**
 * Событие для записи в БД
 *
 * @see sfEvent
 * @package    myDoctrineLoggerPlugin
 * @subpackage event
 * @author     Svel.Sontz <svel.sontz@gmail.com>
 * @version    0.1
 */
class myLogEvent extends sfEvent
{
    /**
     * Конструктор
     */
    public function __construct($subject, $state = 'info', $component = 'global', $name = false, $description = '')
    {
        parent::__construct($subject, 'app.activity', array(
            'state'       => (string) $state,
            'component'   => (string) $component,
            'name'        => $name ? (string) $name : 'user activity event',
            'description' => (string) $description,
        ));
    }

}
