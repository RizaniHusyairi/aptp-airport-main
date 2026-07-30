{{--
    Akordeon FAQ. Dipakai bersama oleh halaman /faq, beranda, dan halaman layanan.
    Variabel: $faqs (Collection), $accordionId (string unik per halaman).

    data-bs-parent sengaja TIDAK dipakai agar beberapa jawaban bisa terbuka
    sekaligus — perilaku yang lebih pas untuk FAQ.
--}}
<div class="accordion faq-accordion" id="{{ $accordionId }}">
    @foreach($faqs as $faq)
        <div class="accordion-item faq-item"
             style="--i: {{ $loop->index }}"
             data-category="{{ $faq->category }}"
             data-search="{{ Str::lower($faq->question . ' ' . $faq->plain_answer) }}">
            <h3 class="accordion-header" id="faq-h-{{ $accordionId }}-{{ $faq->id }}">
                <button class="accordion-button faq-question collapsed" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#faq-c-{{ $accordionId }}-{{ $faq->id }}"
                        aria-expanded="false"
                        aria-controls="faq-c-{{ $accordionId }}-{{ $faq->id }}">
                    <span class="faq-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="faq-question-text">{{ $faq->question }}</span>
                    <i class="bi bi-chevron-down faq-chevron"></i>
                </button>
            </h3>
            <div id="faq-c-{{ $accordionId }}-{{ $faq->id }}"
                 class="accordion-collapse collapse"
                 aria-labelledby="faq-h-{{ $accordionId }}-{{ $faq->id }}">
                <div class="accordion-body faq-answer">
                    {!! $faq->answer !!}
                </div>
            </div>
        </div>
    @endforeach
</div>
