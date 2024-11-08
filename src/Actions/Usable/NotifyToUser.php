<?php

namespace Condoedge\Triggerator\Actions\Usable;

use Condoedge\Triggerator\Actions\AbstractAction;

class NotifyToUser extends AbstractAction
{
    public static function execute(array $params)
    {
        $params = (object) $params;

        \Mail::raw($params->message, function ($message) use ($params) {
            $message->to($params->email)
              ->subject($params->subject);
          });
    }

    public static function getName()
    {
        return __('triggerator.notification-to-user');
    }

    static function getForm(array $params)
    {
        return _Rows(
            _Input('triggerator.email')->name('email', false)->default($params['email'] ?? ''),
            _Input('triggerator.subject')->name('subject', false)->default($params['subject'] ?? ''),
            _Textarea('triggerator.message')->name('message', false)->default($params['message'] ?? ''),
        );
    }

    public static function integrityValidators()
    {
        return [
            'email' => 'required|email',
            'message' => 'required|string',
            'subject' => 'required|string',
        ];
    }
}