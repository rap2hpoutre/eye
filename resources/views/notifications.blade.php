<script>
    @if (isset($errors) && ($errors->first()))
        app.__vue__.showNotification('error', '{{ e($errors->first()) }}')
    @elseif (session('error'))
        app.__vue__.showNotification('error', '{{ e(session('error')) }}')
    @elseif (session('success'))
        app.__vue__.showNotification('success', '{{ e(session('success')) }}')
    @elseif (session('info'))
        app.__vue__.showNotification('info', '{{ e(session('info')) }}')
    @elseif (session('warning'))
        app.__vue__.showNotification('warning', '{{ e(session('warning')) }}')
    @endif
</script>
