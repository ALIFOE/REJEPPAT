{{-- Accordéon FAQ du template. Paramètre : $questions (liste de ['question' => ..., 'reponse' => ...]) --}}
<ul class="accordion-box clearfix">
    @foreach ($questions as $item)
        <li class="accordion block {{ $loop->first ? 'active-block' : '' }}">
            <div class="acc-btn {{ $loop->first ? 'active' : '' }}">
                <div class="title">
                    <h3>{{ $item['question'] }}</h3>
                </div>
            </div>
            <div class="acc-content {{ $loop->first ? 'current' : '' }}">
                <div class="text-box">
                    <p>{{ $item['reponse'] }}</p>
                </div>
            </div>
        </li>
    @endforeach
</ul>
