<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace startpl\t2cmsblog\events\page;

/**
 * Category events system a.k.a hook
 *
 * @author Koperdog <koperdog.dev@gmail.com>
 */
class SearchEvent extends \yii\base\Event
{
    public $dataProvider;
}
