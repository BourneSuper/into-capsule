<?php

namespace BS\IC\resource\impl;

use BS\IC\resource\impl\CarBase;
use BS\IC\resource\Radio;

/**
 * @author BourneSuper
 *
 */
class SmallCar extends CarBase {
    
    protected $radio;
    
    /**
     * @param Radio $radio
     */
    public function __construct( $radio ){
        $this->radio = $radio;
    }
    
    /**
     * {@inheritDoc}
     * @see \BS\resource\i\IMovable::move()
     */
    public function move( $direction, $distance ){
        
        echo sprintf( "move to %s by %s meters", $direction, $distance );
        
    }

    public function introduction(){
        echo sprintf( "Color is %s", $this->color );
    }
    
    public function playRadioMessage(){
        $this->radio->play();
    }
    
    public function playAnotherRadioMessage(){
        $radio = new Radio();
        $radio->play();
    }
    
    
    
    
}

?>