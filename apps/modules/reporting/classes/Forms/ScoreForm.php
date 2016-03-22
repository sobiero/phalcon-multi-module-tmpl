<?php
namespace Pims\Reporting\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;

use Pims\Reporting\Models\ProjectAssessmentScale;


class ScoreForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        
        $itemID = new Hidden('itemIdInput');
        $this->add($itemID);

        $scoreID = new Hidden('scoreIdInput');
        $this->add($scoreID);

        $score = new Select("score", 
            [
                "0" => "0", 
                "1" => "1", 
                "2" => "2", 
                "3" => "3", 
                "4" => "4", 
                "5" => "5", 
                "6" => "6", 
                "7" => "7", 
                "8" => "8", 
                "9" => "9"
            ],
            [
               "class"=>"form-control", 
                "id"=>"scoreInput"
            ]
        );

        
        $this->add($score);        
        

        $comments = new TextArea('comments', array(
            'placeholder' => 'Comments', 
            "class"=>"form-control", 
            "id"=>"commentsInput"
        ));

        $comments->setLabel("Comments");

        $comments->addValidators(array(
            new PresenceOf(array(
                'message' => 'Comments are required'
            ))
        ));

        $this->add($comments);               

        
    }
}
