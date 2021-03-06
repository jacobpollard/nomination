<?php

/**
 * DoEmail
 *
 *   Actually send emails.
 *
 * @author Daniel West <dwest at tux dot appstate dot edu>
 * @package nomination
 */

PHPWS_Core::initModClass('nomination', 'Command.php');
PHPWS_Core::initModClass('nomination', 'NominationEmail.php');

class DoEmail extends Command {
    public $from;
    public $list;
    public $subject;
    public $message;

    public function getRequestVars()
    {
        $vars = array('action'=>'DoEmail');

        if(isset($this->from)){
            $vars['from'] = $this->from;
        }
        if(isset($this->list)){
            $vars['list'] = $this->list;
        }
        if(isset($this->subject)){
            $vars['subject'] = $this->subject;
        }
        if(isset($this->message)){
            $vars['message'] = $this->message;
        }

        return $vars;
    }

    public function execute(Context $context)
    {
        if(!UserStatus::isAdmin()){
            throw new PermissionException('You are not allowed to do this!');
        }
        
        try{
            $msgType = $context['list'];
            $mail = new NominationEmail(NominationEmail::getListMembers($msgType), $context['subject'], $context['message'], $msgType);
            $mail->send();
            NQ::simple('nomination', NOMINATION_SUCCESS, 'Emails sent');
        } catch(DatabaseException $e){
            NQ::simple('nomination', NOMINATION_ERROR, $e->getMessage());
        }
    }
}
?>
