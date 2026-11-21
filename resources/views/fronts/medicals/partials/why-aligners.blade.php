<section class="md-section md-section--mint md-reveal">
    <div class="container">
        <h2 class="md-section__title text-center">Why should you go for clear aligners?</h2>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle md-compare" style="border-radius: var(--md-radius); overflow: hidden;">
                <thead>
                    <tr>
                        <th class="fw-bold fs-5 text-start p-3">The facts</th>
                        <th class="md-compare__aligners">
                            <div class="fw-bold fs-5">Clear aligners</div>
                        </th>
                        <th class="fw-bold fs-5">Braces</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $compareRows = [
                            ['text' => 'Conveniently removable during meals and brushing', 'clear' => true, 'braces' => false],
                            ['text' => 'Virtually invisible for a discreet look', 'clear' => true, 'braces' => false],
                            ['text' => 'No dietary limitations — enjoy your favorite foods', 'clear' => true, 'braces' => false],
                            ['text' => 'Simple to maintain oral hygiene — brush and floss with ease!', 'clear' => true, 'braces' => false],
                            ['text' => 'Requires frequent dental appointments', 'clear' => false, 'braces' => true],
                            ['text' => 'Offers a more comfortable experience', 'clear' => true, 'braces' => false],
                            ['text' => 'Delivers quicker results (typically 6–8 months)', 'clear' => true, 'braces' => false],
                        ];
                    @endphp
                    @foreach($compareRows as $row)
                        <tr style="background-color: {{ $loop->even ? 'var(--md-warm)' : 'var(--md-white)' }};">
                            <td class="text-start p-3">{{ $row['text'] }}</td>
                            <td class="md-compare__aligners">
                                @if($row['clear'])
                                    <span class="text-success fs-4">&#10003;</span>
                                @else
                                    <span class="text-danger fs-4">&#10007;</span>
                                @endif
                            </td>
                            <td>
                                @if($row['braces'])
                                    <span class="text-success fs-4">&#10003;</span>
                                @else
                                    <span class="text-danger fs-4">&#10007;</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
