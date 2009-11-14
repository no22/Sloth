<?php
//>php %FILE%
/**
 * Sloth_Zip
 *
 * @package     Sloth
 * @author      Hiroyuki OHARA <Hiroyuki.no22@gmail.com>
 * @since       2009-08-25
 */    

!count(debug_backtrace()) and require "./AutoLoad.php";

/**
 * Sloth_Zip
 * >>>>
 * $seq = iter(1,fn('$n+1'));
 * $seq2 = iter(array(1,2,3,4,5));
 * foreach ($seq->map(fn('$x*$x'))->zipWith(op('+'),$seq2) as $elt) {
 *     $e = print_r($elt,true);
 *     echo "{$e}\n";
 * }
 *|2
 *|6
 *|12
 *|20
 *|30
 * <<<<
 */

if (version_compare(PHP_VERSION, '5.3', '>=')) {
    class Sloth_Zip extends Sloth_Iterator
    {
        protected $callback = null;

        public function __construct($seq, $aSeq, $callback = null)
        {
            $iterator = Sloth::iter($seq);
            $itMulti = new MultipleIterator(
                MultipleIterator::MIT_NEED_ALL | MultipleIterator::MIT_KEYS_NUMERIC
            );
            $itMulti->attachIterator($iterator);
            foreach ($aSeq as $mSeq) {
                $itMulti->attachIterator(Sloth::iter($mSeq));
            }
            $this->iterator = $itMulti;
            if (!is_null($callback)) {
                if (!is_callable($callback)) throw new InvalidArgumentException;
                $this->callback = $callback;
            }
        }

        public function __clone()
        {
            parent::__clone();
            $this->callback = clone $this->callback;
        }

        public function current()
        {
            $callback = $this->callback;
            if (is_null($callback)) {
                return $this->iterator->current();
            }
            return call_user_func_array($callback, $this->iterator->current());
        }
    }
}
else {
    class Sloth_Zip extends Sloth_Iterator
    {
        protected $callback = null;
        protected $iterator2 = null;
        
        function __construct($seq, $aSeq, $callback = null)
        {
            parent::__construct($seq);
            $this->iterator2 = Sloth::iter($aSeq[0]);
            if (!is_callable($callback)) throw new InvalidArgumentException;
            $this->callback = $callback;
        }

        public function __clone()
        {
            parent::__clone();
            $this->iterator2 = clone $this->iterator2;
        }

        public function current()
        {
            return call_user_func($this->callback, $this->iterator->current(), $this->iterator2->current());
        }
        
        public function next()
        {
            $this->iterator->next();
            $this->iterator2->next();
        }
        
        public function valid()
        {
            return $this->iterator->valid() && $this->iterator2->valid();
        }
        
        public function rewind()
        {
            $this->iterator->rewind();
            $this->iterator2->rewind();
        }

    }
}

//
// DocTest
//
!count(debug_backtrace()) and Sloth::doctest(__FILE__);
