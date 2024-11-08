<?php

namespace Condoedge\Triggerator\Components;

use Condoedge\Triggerator\Facades\Models\TriggerSetupModel;
use Condoedge\Triggerator\Facades\Triggers;
use Condoedge\Triggerator\Models\TriggerSetup;
use Illuminate\Support\Facades\Validator;
use Kompo\Auth\Common\Form;
use Kompo\Komponents\Form\FormDisplayer;

class TriggerSetupFormPage extends Form
{
    /**
     * @var TriggerSetup $model
     */
    public $model = TriggerSetupModel::class;
    public $refreshAfterSubmit = true;

    public function beforeSave()
    {
        $validators = $this->model->trigger->integrityValidators();

        $validation = Validator::make(request()->all(), $validators);

        if ($validation->fails()) {
            throw \Illuminate\Validation\ValidationException::withMessages($validation->errors()->toArray());
        }

        $this->model->trigger_params = request()->except(['name', 'trigger_namespace']);
    }

    public function afterSave()
    {
        $this->model->trigger->afterSetup(['trigger' => $this->model]);

        cache()->forget(TriggerSetup::getCacheKey($this->model->trigger_namespace));
    }

    public function response()
    {
        if($this->model->actions()->count()) {
            return redirect()->route('triggerator.dashboard', ['tab_number' => 1]);
        }

        return response()->json(FormDisplayer::displayElements($this), 202);
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
                        $this->model?->trigger?->getForm((array) $this->model->trigger_params ?? []),
                    )->id('trigger-form'),
                ),

                _Rows(
                    _Html('translate.actions')->class('text-xl mb-6'),

                    !$this->model->id ? _Html('translate.save-the-trigger-first')->class('text-xl') : 
                        _Rows(
                            new ActionSetupsTable([
                                'trigger_id' => $this->model->id,
                            ]),
                        ),
                ),
            ),
        );
    }

    public function getTriggerForm()
    {
        if(!request('trigger_namespace')) return null;

        return request('trigger_namespace')::getForm([]);
    }
}