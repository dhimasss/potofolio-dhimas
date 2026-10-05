@props(['text'])

{{--
    Mengubah teks biasa dari textarea menjadi paragraf rapi:
    - baris kosong  -> paragraf baru
    - enter tunggal -> <br>
    Teks di-escape dulu (e()) sehingga aman dari XSS.
--}}
<div {{ $attributes->merge(['class' => 'space-y-5 text-lg leading-relaxed text-body break-words']) }}>
    @foreach (preg_split('/\R\s*\R/', trim($text)) as $paragraph)
        <p>{!! nl2br(e(trim($paragraph))) !!}</p>
    @endforeach
</div>
