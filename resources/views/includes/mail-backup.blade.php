<div>
        <?php $counter = 0; ?>
        @foreach ($data as $test)
           
        @if($counter == 0)
        Dear <span style="font-weight: bold">{{ explode("Dear ", $test)[1] }}</span> <br>
        @else
            {{ $test }} <br>
        @endif
    <?php $counter++; ?>        
        @endforeach
    </div>