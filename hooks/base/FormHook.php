<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace startpl\t2cmsblog\hooks\base;

/**
 * Category form hook
 *
 * @author Koperdog <koperdog.dev@gmail.com>
 */
class FormHook
{
    protected static $sections    = [];
    protected static $fields      = [];
    protected static $mainSections = [];
    protected static $seoSections  = [];
    
    public static function getSections(): ?array
    {
        return static::$sections;
    }
    
    public static function addSection(string $title, string $section): void
    {
        static::$sections[$title] = $section;
    }
    
    public static function getFields(): ?array
    {
        return static::$fields;
    }
    
    public static function addField(string $field): void
    {
        static::$fields[] = $field;
    }
    
    public static function getMainSections(): ?array
    {
        return static::$mainSections;
    }
    
    public static function addSectionToMain(string $title, string $section): void
    {
        static::$mainSections[$title] = $section;
    }
    
    public static function getSeoSections(): ?array
    {
        return static::$seoSections;
    }
    
    public static function addSectionToSeo(string $title, string $section): void
    {
        static::$seoSections[$title] = $section;
    }
}
