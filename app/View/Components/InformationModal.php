<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InformationModal extends Component {

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $modalId;
    public $modalHeading;
    public $modalMessage;

    public function __construct($modalId, $modalHeading, $modalMessage) {
        $this->modalId = $modalId;
        $this->modalHeading = $modalHeading;
        $this->modalMessage = $modalMessage;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render() {
        return view('components.information-modal');
    }

}
