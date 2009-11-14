<?php
//>php -l %FILE%
/**
 * Sloth_Iterator_Interface
 *
 * @package     Sloth
 * @author      Hiroyuki OHARA <Hiroyuki.no22@gmail.com>
 * @since       2009-08-25
 */    
interface Sloth_Iterator_Interface
{
    public function first();
    public function rest();
    public function each($fnCallback);
    public function eachWithIndex($fnCallback);
    public function reduce($fnCallback, $mInit = null);
    public function map($fnCallback);
    public function filter($fnCallback);
    public function take($iCount);
    public function drop($iCount);
    public function slice($iBegin,$iEnd);
    public function cycle();
    public function takeWhile($fnCallback);
    public function dropWhile($fnCallback);
    public function zip($seq);
    public function zipArray($aSeq);
    public function zipWith($fnCallback, $seq);
    public function zipArrayWith($fnCallback, $aSeq);
    public function cat($seq);
    public function catArray($aSeq);
    public function chunk($iSize);
    public function scan($mInit, $fnCallback);
    public function toArray();
    public function dup();
}
