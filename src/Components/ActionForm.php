<?php
namespace Condoedge\Triggerator\Components;

use Condoedge\Triggerator\Facades\Models\ActionModel;
use Condoedge\Triggerator\Facades\Models\TriggerModel;
use Condoedge\Triggerator\Facades\Triggers;
use Illuminate\Support\Facades\Validator;
use Kompo\Auth\Common\Form;
use Kompo\Auth\Common\Modal;

class ActionForm extends Modal
{
    protected $_Title = 'translate.create-action';
    public $model = ActionModel::class;

    protected $triggerId;
    protected $trigger;

    public function created()
    {
        $this->triggerId = $this->prop('trigger_id');
        $this->trigger = TriggerModel::findOrFail($this->triggerId);
    }

    public function beforeSave()
    {
        $validators = $this->model->action->integrityValidators();

        if (Validator::make(request()->all(), $validators)->fails()) {
            abort(403, __('translate.invalid-parameters'));
        }

        $this->model->action_params = request()->except(['action_namespace']);

        $this->model->trigger_id = $this->triggerId;
    }

    public function body()
    {
        $actions = Triggers::getActionBindingsForTrigger($this->trigger->trigger_namespace);
        $parsedActions = collect($actions)->mapWithKeys(function ($action) {
            return [$action => $action::getName()];
        });

        return _Rows(
            _Select('translate.select-action')->options($parsedActions)->name('action_namespace')
                ->selfGet('getActionForm')->inPanel('action-form')
                ->required(),

            _Panel(
                $this->model?->action?->getForm(),
            )->id('action-form'),

            _SubmitButton('generic.save')->refresh('actions-table')->closeModal(),
        );
    }

    
    public function getActionForm()
    {
        $action = new (request('action_namespace'))($this->model);

        return $action->getForm();
    }
}