<x-mail::message>
   
   <section> {!!$newsletter->text!!} </section>

    @lang('Pozdrawiamy'),

@lang('Zespół '){{ config('app.name') }}
    
</x-mail::message>