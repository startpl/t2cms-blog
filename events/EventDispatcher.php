<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace startpl\t2cmsblog\events;

use startpl\t2cmsblog\interfaces\IEventRepository;

/**
 * Description of EventDispatcher
 *
 * @author Koperdog <koperdog.dev@gmail.com>
 */
class EventDispatcher extends \yii\base\Component implements IEventRepository
{
    public function trigger($name, \yii\base\Event $event = null) {
        try {
            parent::trigger($name, $event);
        } catch (\Exception $e) {
            // do nothing
        }
    }
}
