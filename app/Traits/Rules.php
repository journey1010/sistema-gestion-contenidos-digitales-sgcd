<?php 

namespace App\Traits;

trait Rules {

    public function combine(...$rulesSets)
    {
        $rules = [];
    
        foreach ($rulesSets as $rulesSet) {
            if (is_string($rulesSet)) {
                if (method_exists($this, $rulesSet)) {
                    $rules = array_merge($rules, $this->{$rulesSet}());
                }
            } elseif (is_callable($rulesSet)) {
                $rules = array_merge($rules, call_user_func($rulesSet));
            } elseif (is_array($rulesSet)) {
                $rules = array_merge($rules, $rulesSet);
            }
        }
        return $rules;
    }
    

    public function banner()
    {
        return [
            'files.*' => [
                'required',
                'image',
                'mimetypes:image/*', 
                'dimensions: min-width=1000|min-height:500', 
              ],
        ];
    }

    public function numberItems()
    {
        return ['numberItems' => 'required|numeric' ];
    }

    public function bannerId()
    {
        return ['bannerId' => 'required|numeric'];
    }
}