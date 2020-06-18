<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace startpl\t2cmsblog\interfaces;

/**
 *
 * @author Koperdog <koperdog.dev@gmail.com>
 */
interface IEventRepository 
{
    const EVENT_SEARCH = 'searc';
    const EVENT_SHOW   = 'show';
    
    const EVENT_GET    = 'get';
    const EVENT_SAVE   = 'save';
    const EVENT_DELETE = 'delete';
    
    const EVENT_GET_ALL = 'getAll';
}
