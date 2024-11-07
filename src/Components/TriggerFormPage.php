<?php

namespace Condoedge\Triggerator\Components;

use Condoedge\Triggerator\Facades\Models\TriggerModel;
use Condoedge\Triggerator\Facades\Triggers;
use Condoedge\Triggerator\Models\Trigger;
use Illuminate\Support\Facades\Validator;
use Kompo\Auth\Common\Form;

class TriggerFormPage extends Form
{
    /**
     * @var Trigger $model
     */
    public $model = TriggerModel::class;
    public $refreshAfterSubmit = true;

    public function beforeSave()
    {
        $validators = $this->model->trigger->integrityValidators();

        if (Validator::make(request()->all(), $validators)->fails()) {
            abort(403, __('translate.invalid-parameters'));
        }

        $this->model->trigger_params = request()->except(['name', 'trigger_namespace']);
    }

    public function afterSave()
    {
        $this->model->trigger->afterSetup($this->model);
    }

    public function response()
    {
        if($this->model->actions()->count()) {
            return redirect()->route('triggerator.dashboard', ['tab_number' => 1]);
        }
    }
    
    public function render()
    {
        $triggers = Triggers::getTriggers();
        $parsedTriggers = collect($triggers)->mapWithKeys(function ($trigger) {
            return [$trigger => $trigger::getName()];
        });

        return _Rows(
            _FlexBetween(
                _Html('translate.create-trigger')->class('text-xl'),
                _SubmitButton('generic.save'),
            )->class('mb-2'),

            _Input('translate.name')->name('name')->class('mb-4'),

            _Columns(
                _Rows(
                    _Html('translate.triggers')->class('text-xl mb-6'),

                    _Select('translate.select-trigger')->options($parsedTriggers)->name('trigger_namespace')->required()
                        ->onChange(fn($e) => $e->selfGet('getTriggerForm')->inPanel('trigger-form'),
                    ),

                    _Panel(
                        $this->model?->trigger?->getForm(),
                    )->id('trigger-form'),
                ),

                _Rows(
                    _Html('translate.actions')->class('text-xl mb-6'),

                    !$this->model->id ? _Html('translate.save-the-trigger-first')->class('text-xl') : 
                        _Rows(
                            new ActionsTable([
                                'trigger_id' => $this->model->id,
                            ]),
                        ),
                ),
            ),
        );
    }

    public function getTriggerForm()
    {
        $trigger = new (request('trigger_namespace'))($this->model);

        return $trigger->getForm();
    }
}