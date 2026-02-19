<?php

namespace App\View\Components\Front;

use App\Models\FormBuilder;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormBuilderComponent extends Component
{
    public ?FormBuilder $form;
    public string $identifier;
    public $widget;
    public ?string $title;
    public ?string $subtitle;
    public ?string $wrapperClass;
    public ?string $formClass;

    /**
     * Create a new component instance.
     * 
     * Usage examples:
     * <x-front.form-builder identifier="contact_form" />
     * <x-front.form-builder identifier="contact_form" title="Contact Us" />
     * <x-front.form-builder identifier="contact_form" :form="$form" />
     * <x-front.form-builder :widget="$widget" />  <!-- From page builder -->
     */
    public function __construct(
        $widget = null,
        string $identifier = 'contact_form',
        ?FormBuilder $form = null,
        ?string $title = null,
        ?string $subtitle = null,
        ?string $wrapperClass = null,
        ?string $formClass = null
    ) {
        $this->widget = $widget;
        
        // Priority: widget template_parameter > widget settings > direct identifier
        if ($widget && $widget->template_parameter) {
            $this->identifier = $widget->template_parameter;
        } elseif ($widget && isset($widget->settings['form_builder_identifier'])) {
            $this->identifier = $widget->settings['form_builder_identifier'];
        } else {
            $this->identifier = $identifier;
        }
        
        // Load form by identifier
        $this->form = $form ?? FormBuilder::getByIdentifier($this->identifier);
        
        // Get title and subtitle from widget or form
        if ($widget) {
            $this->title = $title ?? ($widget->title ?? ($this->form ? $this->form->title : null));
            $this->subtitle = $subtitle ?? ($widget->subtitle ?? ($this->form ? $this->form->description : null));
        } else {
            $this->title = $title ?? ($this->form ? $this->form->title : null);
            $this->subtitle = $subtitle ?? ($this->form ? $this->form->description : null);
        }
        
        $this->wrapperClass = $wrapperClass;
        $this->formClass = $formClass;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.front.form-builder-component');
    }
}

