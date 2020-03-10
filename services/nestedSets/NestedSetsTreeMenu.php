<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace startpl\t2cmsblog\services\nestedSets;

use Yii;

/**
 * Description of NestedSetsTreeMenu
 *
 * @author Koperdog <koperdog.dev@gmail.com>
 * @version 1.0
 */
class NestedSetsTreeMenu extends \startpl\yii2NestedSetsMenu\base\NestedSetsTree
{

    /**
     * @var string
     */
    public $childrenOutAttribute = 'items'; //children

    /**
     * @var string
     */
    public $labelOutAttribute = 'label'; //title


    /**
     * Добавляет в массив дополнительные элементы
     * @param $node
     * @return array
     */
    public function addItem($node): array
    {
        $this->renameTitle($node); //переименование элемента массива
        $this->changeUrl($node);
        $this->visible($node); //видимость элементов меню
        $this->makeActive($node); //выделение активного пункта меню

        return $node;
    }
    
    protected function changeUrl(&$node){
        $url = ['PageSearch' => ['category' => $node['id']]];
        $node['url'] = \yii\helpers\ArrayHelper::merge(\Yii::$app->request->queryParams, $url);
    }


    /**
     * Переименовываем элемент "name" в "label" (создаем label, удаляем name)
     * требуется для yii\widgets\Menu
     * @param $node
     * @return array
     */
    protected function renameTitle(&$node)
    {
        $node[$this->labelOutAttribute] = $node['categoryContent'][$this->labelAttribute];
        unset($node[$this->labelAttribute]);
    }


    /**
     * Видимость пункта меню (visible = false - скрыть элемент)
     * @param $node
     * @return array
     */
    protected function visible(&$node)
    {

        //Гость
        if (Yii::$app->user->isGuest) {

            //Действие logout по-умолчанию проверяется на метод POST.
            //При использовании подкорректировать VerbFilter в контроллере (удалить это действие или добавить GET).
            if ($node['url'] === '/logout') {
                $node['visible'] = false;
            }

        //Авторизованный пользователь
        } else {
            if ($node['url'] === '/login' || $node['url'] === '/signup') {
                $node['visible'] = false;
            }
        }
    }



    /**
     * Добавляет элемент "active" в массив с url соответствующим текущему запросу
     * для назначения отдельного класса активному пункту меню
     *
     * @param $node
     * @return array
     */
    private function makeActive(&$node)
    {
        $path = Yii::$app->request->get();
        
        if($path['PageSearch']['category'] == $node['id']){ //for not active !isset
            $node['active'] = true;
        }
    }

}