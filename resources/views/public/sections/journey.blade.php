{{--
    MY JOURNEY: cerita & filosofi kerja.
    ✏️ Teks di bawah masih umum — tambahkan detail pribadi Anda (awal mula, momen penting, pencapaian).
--}}
@php
    $principles = [
        ['title' => 'Cerita sebelum piksel', 'body' => 'Sebelum membuka kanvas atau editor kode, saya mencari pesannya dulu: siapa yang ingin dijangkau, dan apa yang perlu mereka rasakan?'],
        ['title' => 'Bentuk mengikuti makna', 'body' => 'Warna, tipografi, dan tata letak bukan hiasan. Setiap pilihan visual harus membantu pesan tersampaikan lebih jernih.'],
        ['title' => 'Indah dan berfungsi', 'body' => 'Desain yang memukau harus juga cepat, mudah dipakai, dan bisa diandalkan. Di sinilah desain dan kode bertemu.'],
    ];
@endphp

<section id="journey" class="scroll-mt-20 border-t border-line">
    <div class="mx-auto grid max-w-6xl gap-12 px-4 py-24 sm:px-6 sm:py-32 lg:grid-cols-12">
        <div class="lg:col-span-4" data-reveal>
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-accent">01 — My Journey</p>
            <h2 class="mt-4 font-display text-4xl font-semibold leading-tight sm:text-5xl">
                Di antara <em class="font-normal text-accent">rasa</em> dan logika.
            </h2>
        </div>

        <div class="lg:col-span-7 lg:col-start-6">
            <div class="space-y-6 text-lg leading-relaxed text-body" data-reveal>
                <p>
                    Perjalanan saya berawal dari dua hal yang sering dianggap berseberangan: kecintaan pada
                    visual dan rasa penasaran tentang <em>bagaimana sesuatu bekerja</em>. Satu sisi mengajarkan
                    saya merangkai warna, bentuk, dan tipografi; sisi lain mengajarkan saya menyusun logika
                    hingga sebuah ide benar-benar bisa dipakai.
                </p>
                <p>
                    Lama-kelamaan saya sadar keduanya saling membutuhkan. Desain tanpa fungsi hanya menjadi
                    gambar yang indah, dan kode tanpa rasa hanya menjadi mesin yang dingin. Karena itu saya
                    merancang dan membangun dengan satu tujuan: membuat karya yang <em>terlihat</em> baik,
                    <em>terasa</em> tepat, dan <em>bekerja</em> dengan semestinya bagi orang yang memakainya.
                </p>
            </div>

            <ol class="mt-14 space-y-8">
                @foreach ($principles as $i => $principle)
                    <li class="flex gap-6 border-t border-line pt-8" data-reveal>
                        <span class="font-display text-2xl text-accent">0{{ $i + 1 }}</span>
                        <div>
                            <h3 class="text-xl font-semibold">{{ $principle['title'] }}</h3>
                            <p class="mt-2 leading-relaxed text-muted">{{ $principle['body'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>
