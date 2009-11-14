<?php
//>php %FILE%
/**
 * Sloth_AutoLoad.php
 * 
 * @package     Sloth
 * @author      Hiroyuki OHARA <Hiroyuki.no22@gmail.com>
 * @since       2008-11-07
 */    
class Sloth_Autoload
{
    static $slothPath = null;

    public static function autoload($sClass)
    {
        if (is_null(self::$slothPath)) {
            self::$slothPath = dirname(dirname(__FILE__)).'/';
        }
        $sPath = self::$slothPath . strtr($sClass, array('_'=>'/')).'.php';
        file_exists($sPath) and include($sPath);
    }
}

function_exists('__autoload') and spl_autoload_register('__autoload');
spl_autoload_register('Sloth_Autoload::autoload');

class Sloth
{
    /**
     * fn
     * @param string $sExp
     * @return callback
     */
    public static function fn($sExp)
    {
        $sArg = '';
        if (preg_match_all('/\$\w+/', $sExp, $matches)) {
            $sArg = implode(',', array_unique($matches[0]));
        }
        return create_function($sArg, 'return '.$sExp.';');
    }

    /**
     * op
     * @param string $sOperator
     * @return callback
     */
    public static function op($sOperator)
    {
        return create_function('$a,$b', 'return $a'.$sOperator.'$b;');
    }

    /**
     * iter
     * @param mixed $mFirst
     * @param func $fnCallback
     * @return object Sloth_Iterator
     */
    public static function iter($mFirst, $fnCallback = null)
    {
        if (is_null($fnCallback)) {
            if (is_array($mFirst)) {
                return new Sloth_Iterator($mFirst);
            }
            else if ($mFirst instanceof Sloth_Iterator_Interface) {
                return $mFirst;
            }
            else if ($mFirst instanceof Iterator) {
                return new Sloth_Iterator($mFirst);
            }
            else if ($mFirst instanceof IteratorAggregate) {
                $iter = $mFirst->getIterator();
                if ($iter instanceof Sloth_Iterator_Interface) {
                    return $iter;
                }
                else if ($iter instanceof Iterator) {
                    return new Sloth_Iterator($iter);
                }
            }
            else {
                throw new InvalidArgumentException;
            }
        }
        else {
            return new Sloth_Follow($mFirst, $fnCallback);
        }
    }

    /**
     * ref
     * @param object $obj
     * @return object
     */
    public static function ref($obj)
    {
        return $obj;
    }

    /**
     * doctest
     * @param string $sFile
     * @return void
     */
    public static function doctest($sFile)
    {
        $oTest = new Sloth_DocTest($sFile);
        $oTest->invoke();
    }

}

if (!function_exists('fn')) {
    function fn($sExp) { return Sloth::fn($sExp); }
}
if (!function_exists('op')) {
    function op($sOp) { return Sloth::op($sOp); }
}
if (!function_exists('iter')) {
    function iter($fst, $fn = null) { return Sloth::iter($fst, $fn); }
}
if (!function_exists('ref')) {
    function ref($o) { return Sloth::ref($o); }
}
if (!function_exists('doctest')) {
    function doctest($file) { return Sloth::doctest($file); }
}

//
// DocTest
//
!count(debug_backtrace()) and Sloth::doctest(__FILE__);

