@extends(Helper::acclayout())


@section('style')
    {{ HTML::style('css/redactor.css') }}
@stop


@section('content')

    <?
    $create_title = "Редактировать запись:";
    $edit_title   = "Добавить запись:";

    $url =
        @$element->id
        ? action(is_numeric($dic_id) ? 'dicval.update' : 'entity.update', array('dic_id' => $dic_id, 'id' => $element->id))
        : action(is_numeric($dic_id) ? 'dicval.store'  : 'entity.store',  array('dic_id' => $dic_id));
    $method     = @$element->id ? 'PUT' : 'POST';
    $form_title = @$element->id ? $create_title : $edit_title;
    ?>

    @include($module['tpl'].'/menu')

    {{ Helper::ta_($element) }}

    {{ Form::model($element, array('url' => $url, 'class' => 'smart-form', 'id' => $module['entity'].'-form', 'role' => 'form', 'method' => $method, 'files' => true)) }}

    <!-- Fields -->
	<div class="row">

        <!-- Form -->
        <section class="col col-6">
            <div class="well">
                <header>{{ $form_title }}</header>

                <fieldset>

                    @if (!$dic->hide_slug)
                    <section>
                        <label class="label">Системное имя (необязательно)</label>
                        <label class="input">
                            {{ Form::text('slug', null, array()) }}
                        </label>
                    </section>
                    @endif

                    <section>
                        <label class="label">{{ $dic->name_title ?: 'Название' }}</label>
                        <label class="input">
                            {{ Form::text('name', null, array()) }}
                        </label>
                    </section>

                </fieldset>

                {{ Helper::dd_($dic_settings) }}

                @if (@is_callable($dic_settings['fields']) && NULL !== ($fields_general = $dic_settings['fields']()))
                <?
                #Helper::ta($element);
                $onsuccess_js = array();
                if (isset($element->fields) && is_object($element->fields) && count($element->fields)) {
                    $element_fields = $element->fields->lists('value', 'key');
                } elseif (isset($element->allfields) && is_object($element->allfields) && count($element->allfields)) {
                    $element_fields = $element->allfields->lists('value', 'key');
                } else {
                    $element_fields = array();
                }
                #Helper::d($element_fields);
                #$fields_general = $dic_settings['fields'];
                ?>
                    <fieldset class="padding-top-10 clearfix">
                        @foreach ($fields_general as $field_name => $field)
                        <?
                        $field['_name'] = $field_name;
                        if (@$field['after_save_js'])
                            $onsuccess_js[] = $field['after_save_js'];
                        ?>
                        <section>
                            @if (!@$field['no_label'])
                            <label class="label">{{ @$field['title'] }}</label>
                            @endif
                            <div class="input {{ @$field['type'] }} {{ @$field['label_class'] }}">
                                {{ Helper::formField('fields[' . @$field_name . ']', @$field, @$element_fields[$field_name], $element) }}
                            </div>
                        </section>
                        @endforeach
                    </fieldset>
                @endif


                @if (count($locales) > 1)
                <fieldset class="clearfix">
                    <section>
                        {{--
                        <label class="label">Индивидуальные настройки для разных языков (необязательно)</label>
                        --}}

                        <div class="widget-body">
                            <ul id="myTab1" class="nav nav-tabs bordered">
                                <? $i = 0; ?>
                                @foreach ($locales as $locale_sign => $locale_name)
                                <li class="{{ !$i++ ? 'active' : '' }}">
                                    <a href="#locale_{{ $locale_sign }}" data-toggle="tab">
                                        {{ $locale_name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            <div id="myTabContent1" class="tab-content padding-10">
                                <? $i = 0; ?>
                                @foreach ($locales as $locale_sign => $locale_name)
                                <div class="tab-pane fade {{ !$i++ ? 'active in' : '' }}" id="locale_{{ $locale_sign }}">

                                    @include($module['tpl'].'_dicval_meta', compact('locale_sign', 'locale_name', 'element'))

                                </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                </fieldset>

                @else

                @foreach ($locales as $locale_sign => $locale_name)
                @include($module['tpl'].'_dicval_meta', compact('locale_sign', 'locale_name', 'element'))
                @endforeach

                @endif

                <footer>
                	<a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{ link::previous() }}">
                		<i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
                	</a>
                	<button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
                		<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сохранить</span>
                	</button>
                </footer>

		    </div>
    	</section>

        @if (count($locales) < 2 && Config::get('dic.seo.' . $dic->slug))
        <section class="col col-6">
            {{ ExtForm::seo('seo', $element->seo) }}
        </section>
        @endif


        @if (Config::get('dic/' . $dic->slug . '.seo'))
        <section class="col col-6">
            <div class="well">
                <header>Поисковая оптимизация</header>
                <fieldset class="padding-bottom-15">
                    <div class="widget-body">
                        @if (count($locales) > 1)
                        <ul id="myTab2" class="nav nav-tabs bordered">
                            <? $i = 0; ?>
                            @foreach ($locales as $locale_sign => $locale_name)
                            <li class="{{ !$i++ ? 'active' : '' }}">
                                <a href="#seo_locale_{{ $locale_sign }}" data-toggle="tab">
                                    {{ $locale_name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @endif

                        {{ Helper::ta_($element) }}

                        <div id="myTabContent2" class="tab-content @if(count($locales) > 1) padding-10 @endif">
                            <? $i = 0; ?>
                            @foreach ($locales as $locale_sign => $locale_name)
                            <div class="tab-pane fade {{ !$i++ ? 'active in' : '' }} clearfix" id="seo_locale_{{ $locale_sign }}">

                                {{ ExtForm::seo('seo[' . $locale_sign . ']', @$element->seos[$locale_sign]) }}

                            </div>
                            @endforeach
                        </div>
                    </div>
                </fieldset>
                <footer>
                	<a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{ link::previous() }}">
                		<i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
                	</a>
                	<button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
                		<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сохранить</span>
                	</button>
                </footer>
            </div>
        </section>
        @endif


        <!-- /Form -->
   	</div>

    @if(@$element->id)
    @else
    {{ Form::hidden('redirect', action(is_numeric($dic_id) ? 'dicval.index' : 'entity.index', array('dic_id' => $dic_id)) . (Request::getQueryString() ? '?' . Request::getQueryString() : '')) }}
    @endif

    {{ Form::close() }}

@stop


@section('scripts')
    <script>
    var essence = '{{ $module['entity'] }}';
    var essence_name = '{{ $module['entity_name'] }}';
	var validation_rules = {
		name:              { required: true },
	};
	var validation_messages = {
		name:              { required: "Укажите название" },
	};
    </script>

    <script>
        var onsuccess_function = function() {

            // UPLOAD
            $('input[type=file].file_upload').each(function(){
                //console.log($(this).val());
                if ($(this).val() != '')
                    if (!$('input[type=hidden][name=redirect]').val())
                        location.href = location.href;
            });

            // VIDEO
            $('input[type=file].video_image_upload').each(function(){
                //console.log($(this).val());
                if ($(this).val() != '')
                    if (!$('input[type=hidden][name=redirect]').val())
                        location.href = location.href;
            });

            @if (@count($onsuccess_js))
                {{ implode("\n", @$onsuccess_js) }}
            @endif
        }
    </script>

	{{ HTML::script('js/modules/standard.js') }}

	<script type="text/javascript">
		if(typeof pageSetUp === 'function'){pageSetUp();}
		if(typeof runFormValidation === 'function') {
			loadScript("{{ asset('js/vendor/jquery-form.min.js'); }}", runFormValidation);
		} else {
			loadScript("{{ asset('js/vendor/jquery-form.min.js'); }}");
		}        
	</script>

    {{ HTML::script('js/vendor/redactor.min.js') }}
    {{ HTML::script('js/system/redactor-config.js') }}

    {{ HTML::script('js/modules/gallery.js') }}
    {{ HTML::script('js/plugin/select2/select2.min.js') }}

@stop