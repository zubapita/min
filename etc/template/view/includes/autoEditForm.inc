<!-- <!--{$pageName}-->/includes/autoEditForm.inc -->
<form id="<!--{$className}-->Form">
{$v = $<!--{$modelName}-->}
<!--{foreach $columns as $column}-->
<!--{if $column['name']!='id'}-->
<div class="form-group has-feedback">
    <label class="sr-only" for="<!--{$className}--><!--{ucfirst($column['name'])}-->">{$l['<!--{$column['name']}-->']}</label>
    <div>
        <input type="text" class="validate[required] text-input form-control input-lg" id="<!--{$className}--><!--{ucfirst($column['name'])}-->" name="<!--{$className}--><!--{ucfirst($column['name'])}-->" placeholder="{$l['<!--{$column['name']}-->']}" value="{$v['<!--{$column['name']}-->']}" />
        <span class="form-control-feedback" aria-hidden="true"></span>
    </div>
</div>

<!--{/if}-->
<!--{/foreach}-->
<button type="button" id="<!--{$className}-->SaveButton" class="btn btn-primary btn-block">{$l['save_button']}</button>
<button type="button" id="<!--{$className}-->CancelButton" class="btn btn-default btn-block">{$l['cancel_button']}</button>
<input type="hidden" id="<!--{$className}-->Id" name="<!--{$className}-->Id" value="{$v['id']}" />
<input type="hidden" id="Session-token" name="Session-token" value="{$token}" />
</form>
<!-- end of <!--{$pageName}-->/includes/autoEditForm.inc -->

{*<!--
# バリデータ記述ルール
https://github.com/posabsolute/jQuery-Validation-Engine


## required

<input value="" class="validate[required]" type="text" name="email" id="email" />
<input class="validate[required]" type="checkbox" id="agree" name="agree"/>

<select name="sport" id="sport" class="validate[required]" id="sport">
   <option value="">Choose a sport</option>
   <option value="option1">Tennis</option>
   <option value="option2">Football</option>
   <option value="option3">Golf</option>
</select>


## groupRequired

<input value="" class="validate[groupRequired[payments]]" type="text" name="creditcard" id="creditcard" />
<input class="validate[groupRequired[payments]]" type="text" id="paypal" name="paypal"/>

## custom[regex_name]

<input value="someone@nowhere.com" class="validate[required,custom[requiredInFunction]]" type="text" name="email" id="email" />

* phone
* date
* number
* integer
* onlyNumberSp // Only numbers and spaces characters
* onlyLetterSp // Only letters and space characters
* onlyLetterNumber // Only letters and numbers, no space

## others

* equals[field.id]
* min[float]
* max[float]
* minSize[integer]
* maxSize[integer]
* past[NOW, a date or another element's name]
* future[NOW, a date or another element's name]
* minCheckbox[integer]
* maxCheckbox[integer]

-->*}
