<?php
namespace Condoedge\Triggerator\Components;

use Condoedge\Triggerator\Facades\Models\ActionSetupModel;
use Condoedge\Triggerator\Facades\Models\TriggerSetupModel;
use Condoedge\Triggerator\Facades\Triggers;
use Illuminate\Support\Facades\Validator;
use Kompo\Auth\Common\Modal;

class ActionSetupForm extends Modal
{
    protected $_Title = 'triggerator.create-action';
    public $model = ActionSetupModel::class;

    protected $triggerId;
    protected $trigger;

    public function created()
    {
        $this->triggerId = $this->prop('trigger_id');
        $this->trigger = TriggerSetupModel::findOrFail($this->triggerId);
    }

    public function beforeSave()
    {
        $validators = $this->model->action->integrityValidators();

        if (Validator::make(request()->all(), $validators)->fails()) {
            abort(403, __('triggerator.invalid-parameters'));
        }

        $this->model->action_params = request()->except(['action_namespace']);

        $this->model->trigger_setup_id = $this->triggerId;
    }

    public function body()
    {
        $actions = Triggers::getActionBindingsForTrigger($this->trigger->trigger_namespace);
        $parsedActions = collect($actions)->mapWithKeys(function ($action) {
            return [$action => $action::getName()];
        });

        return _Rows(
            _Select('triggerator.select-action')->options($parsedActions)->name('action_namespace')
                ->selfGet('getActionForm')->inPanel('action-form')
                ->required(),

            _Panel(
                $this->model?->action?->getForm((array) $this->model->action_params),
            )->id('action-form'),

            _SubmitButton('generic.save')->refresh('actions-table')->closeModal(),
        );
    }

    
    public function getActionForm()
    {
        if (!request('action_namespace')) return;

        return request('action_namespace')::getForm([]);
    }
}