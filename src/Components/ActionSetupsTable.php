<?php
namespace Condoedge\Triggerator\Components;

use Condoedge\Triggerator\Facades\Models\TriggerSetupModel;
use Kompo\Auth\Common\Table;

class ActionSetupsTable extends Table
{
    public $id = 'actions-table';

    protected $triggerId;
    protected $trigger;

    public function created()
    {
        $this->triggerId = $this->prop('trigger_id');
        $this->trigger = TriggerSetupModel::findOrFail($this->triggerId);
    }

    public function top()
    {
        return _ButtonOutlined('+')->class('text-3xl')->selfGet('getActionForm')->inModal();
    }

    public function query()
    {
        return $this->trigger->actions();
    }

    public function headers()
    {
        return [
            _Th('action'),
            _Th('params'),
            _Th('')->class('w-8')
        ];
    }

    public function render($actionSetup)
    {
        return _TableRow(
            _Html($actionSetup->action->getName()),
            _Html(collect($actionSetup->action_params)->map(function ($value, $key) {
                return $key . ': ' . $value;
            })->implode('<br>')),

            _Delete($actionSetup)->class('hover:text-red-600'),
        )->selfGet('getActionForm', ['action_id' => $actionSetup->id ])->inModal();
    }

    public function getActionForm($actionId = null)
    {
        return new ActionSetupForm($actionId, [
            'trigger_id' => $this->triggerId,
        ]);
    }
}