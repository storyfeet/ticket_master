<form method="post" action="language/switch" class="language_box">
    @csrf
    <select name="language" onchange="this.form.submit()">
        <option value="en"
            {{app()->getLocale() === 'en' ? 'selected' : ''}}
            >English </option>
        <option value="fr"
            {{app()->getLocale() === 'fr' ? 'selected' : ''}}
            >French/Français</option>
        <option value="zh"
            {{app()->getLocale() === 'zh' ? 'selected' : ''}}
            >Chinese/中文</option>
    </select>
</form>
