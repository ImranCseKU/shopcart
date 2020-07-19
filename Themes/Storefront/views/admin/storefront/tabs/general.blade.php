<div class="row">
    <div class="col-md-8">
        {{ Form::text('translatable[storefront_welcome_text]', trans('storefront::attributes.storefront_welcome_text'), $errors, $settings) }}
        {{ Form::select('storefront_theme', trans('storefront::attributes.storefront_theme'), $errors, trans('storefront::themes'), $settings) }}
        {{ Form::select('storefront_mail_theme', trans('storefront::attributes.storefront_mail_theme'), $errors, trans('storefront::themes'), $settings) }}
        {{ Form::select('storefront_slider', trans('storefront::attributes.storefront_slider'), $errors, $sliders, $settings) }}
        {{ Form::select('storefront_terms_page', trans('storefront::attributes.storefront_terms_page'), $errors, $pages, $settings) }}
        {{ Form::select('storefront_privacy_page', trans('storefront::attributes.storefront_privacy_page'), $errors, $pages, $settings) }}
        {{ Form::text('translatable[storefront_address]', trans('storefront::attributes.storefront_address'), $errors, $settings) }}
    </div>
</div>
