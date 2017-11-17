@extends('handheld.layout')

@section('title', 'Hand held')

@section('content')
<div id="example-basic">
    <h3>Keyboard</h3>
    <section>
        <p>uno</p>
    </section>
    <h3>Effects</h3>
    <section>
        <p>dos</p>
    </section>
    <h3>Pager</h3>
    <section>
        <p>tres</p>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function() {

$("#example-basic").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    autoFocus: true
});

    });
</script>


@endsection