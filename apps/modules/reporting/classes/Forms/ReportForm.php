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


class ReportForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
         // In edition the id is hidden
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
            $this->add($id);
        } /*else {
            //$id = new Text('id');
        }*/

        


        $desc = new Text('desc', array(
            'placeholder' => 'Description', 
            "class"=>"form-control", 
            "id"=>"descInput"
        ));

        $desc->setLabel("Description");

        $desc->addValidators(array(
            new PresenceOf(array(
                'message' => 'The description is required'
            ))
        ));

        $this->add($desc);        
        

        $notes = new TextArea('notes', array(
            'placeholder' => 'Notes', 
            "class"=>"form-control", 
            "id"=>"notesInput"
        ));

        $notes->setLabel("Notes");        

        $this->add($notes);               

        
    }
}
