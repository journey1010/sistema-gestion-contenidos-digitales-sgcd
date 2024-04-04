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
                'mimes:png,jpg,jpeg,bmp,gif,jiff'
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

    public function postFile()
    {
        return [
            'file' => 'required|mimes:png,jpg,jpeg,bmp,gif,jiff',
        ];
    }

    public function title()
    {
        return ['title'=>'required|string'];
    }

    public function description()
    {
        return ['description' => 'required|string|max:600'];
    }

    public function userId()
    {
        return ['userId' => 'required|numeric'];
    }

    public function page()
    {
        return ['page' => 'required|numeric'];
    }

    public function postId()
    {
        return ['postId' => 'required|numeric'];
    }
}