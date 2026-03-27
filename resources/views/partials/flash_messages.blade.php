@if($errors->any() || session('success') || !empty($info ?? null))
    <div class="flash-stack">
        @if(session('success'))
            <div class="flash flash-success">
                {{ session('success') }}
            </div>
        @endif

        @if(!empty($info ?? null))
            <div class="flash flash-info">
                {{ $info }}
            </div>
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                <div class="flash flash-danger">
                    {{ $error }}
                </div>
            @endforeach
        @endif
    </div>
@endif
