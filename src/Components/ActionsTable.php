<?php
namespace Condoedge\Triggerator\Components;

use Condoedge\Triggerator\Facades\Models\TriggerModel;
use Kompo\Auth\Common\Table;

class ActionsTable extends Table
{
    public $id = 'actions-table';

    protected $triggerId;
    protected $trigger;

    public function created()
    {
        $this->triggerId = $this->prop('trigger_id');
        $this->trigger = TriggerModel::findOrFail($this->triggerId);
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

    public function render($action)
    {
        return _TableRow(
            _Html($action->action::getName()),
            _Html(collect($action->action_params)->map(function ($value, $key) {
                return $key . ': ' . $value;
            })->implode('<br>')),

            _Delete($action)->class('hover:text-red-600'),
        )->selfGet('getActionForm', ['action_id' => $action->id ])->inModal();
    }

    public function getActionForm($actionId)
    {
        return new ActionForm($actionId, [
            'trigger_id' => $this->triggerId,
        ]);
    }
}