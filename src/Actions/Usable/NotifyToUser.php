<?php
namespace Condoedge\Triggerator\Actions\Usable;

use Condoedge\Triggerator\Actions\AbstractAction;
use Symfony\Component\Mime\Message;

class NotifyToUser extends AbstractAction
{
    public function execute(object $params)
    {
        \Mail::raw($params->message, function ($message) use ($params) {
            $message->to($params->email)
              ->subject($params->subject);
          });
    }

    public static function getName()
    {
        return __('translate.notify-to-user');
    }

    function getForm()
    {
        return _Rows(
            _Input('translate.email')->name('email', false)->default($this->action?->action_params?->email),
            _Input('translate.subject')->name('subject', false)->default($this->action?->action_params?->subject),
            _Textarea('translate.message')->name('message', false)->default($this->action?->action_params?->message),
        );
    }

    public function integrityValidators()
    {
        return [
            'email' => 'required|email',
            'message' => 'required|string',
            'subject' => 'required|string',
        ];
    }
}